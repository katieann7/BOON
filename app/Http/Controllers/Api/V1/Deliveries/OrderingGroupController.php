<?php

namespace App\Http\Controllers\Api\V1\Deliveries;

use Carbon\Carbon;
use ErrorException;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Deliveries\OrderingGroup;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\V1\Deliveries\OrderingGroupSetupRequest;
use App\Http\Requests\V1\Deliveries\UpdateOrderingGroupRequest;
use App\Http\Resources\V1\Deliveries\OrderingGroupCollection;
use App\Http\Resources\V1\Deliveries\OrderingGroupSetupResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OrderingGroupController extends Controller
{
    /**
     * Display a listing of the resource in datatable server-side.
     *
     * @return Yajra\DataTables\DataTables
     */
    public function index(Request $request)
    {
        $now = Carbon::today();

        $orderingGroup = OrderingGroup::query()
            ->whereDate('effective_from', '<=', $now)
            ->whereDate('effective_to', '>=', $now)
            ->get();

        return DataTables::of($orderingGroup)->make(true);
    }

    /**
     * Fetching all Ordering Group of a Store that has Schedule for dropdown menu
     *
     * @return \Illuminate\Http\Response
     */
    public function showByStoreCode($storeCode)
    {
        try {
            $validator = Validator::make(
                ["storeCode" => $this->parseParameter($storeCode)],
                ["storeCode" => ['required', 'alpha_num', 'min:6', 'max:6']],
            );
            $validator->validate();

            $orderingGroups = DB::select('CALL sp_deliveries_get_ordering_materials_of_a_store(?)', array($storeCode));

            if (sizeof($orderingGroups) == 0) {
                throw new ModelNotFoundException("Ordering Group");
            }

            return $this->successResponse("Ordering Group(s) fetched successfully!", new OrderingGroupCollection($orderingGroups));
        } catch (ValidationException $exception) {
            return $this->validationExceptionReponse($exception, ["storeCode" => $storeCode]);
        } catch (ModelNotFoundException $exception) {
            return $this->modelExceptionResponse($exception, ["storeCode" => $storeCode]);
        }
    }

    /**
     * Store a newly created ordering group in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderingGroupSetupRequest $request)
    {
        $orderingGroup = OrderingGroup::firstOrNew([
            "ordering_group_description" => $request->orderingGroupDescription,
        ]);

        if ($orderingGroup->exists && !Carbon::parse($orderingGroup->effective_to)->isPast()) {
            return $this->errorResponse(
                "Ordering Group(s) alraedy exists and is still effective.",
                new OrderingGroupSetupResource($orderingGroup),
                Response::HTTP_CONFLICT
            );
        } else if ($orderingGroup->exists && Carbon::parse($orderingGroup->effective_to)->isPast()) {
            $orderingGroup->effective_to = $request->effectiveTo;
            $orderingGroup->modified_by = $request->modifiedBy;
            $orderingGroup->save();
            return $this->successResponse("Previous ordering group has been expired! Ordering Group(s) created. ", new OrderingGroupSetupResource($orderingGroup));
        } else if (!$orderingGroup->exists) {
            $orderingGroup->ordering_group_code = "OGC" . str_pad((OrderingGroup::max('id')) + 1, 4, '0', STR_PAD_LEFT);
            $orderingGroup->effective_to = $request->effectiveTo;
            $orderingGroup->modified_by = $request->modifiedBy;
            $orderingGroup->save();
            return $this->successResponse("Ordering Group(s) created successfully!", new OrderingGroupSetupResource($orderingGroup), Response::HTTP_CREATED);
        }
    }

    /**
     * Display the specified ordering group.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($orderingGroupCode)
    {
        try {
            // PARAMETER VALIDATION
            $validator = Validator::make(
                ["orderingGroupCode" => $this->parseParameter($orderingGroupCode)],
                ["orderingGroupCode" => ['required', 'string', 'min:7']],
                [
                    'orderingGroupCode.required' => 'The Ordering Group Code parameter is required.',
                    'orderingGroupCode.string' => 'The Ordering Group Code parameter must be a string.',
                    'orderingGroupCode.min' => 'The Ordering Group Code parameter must be at least 7 characters.',
                ],
            );
            $validator->validate();
            $orderingGroup = OrderingGroup::where(["ordering_group_code" => $orderingGroupCode])->first();
            return $this->successResponse("Ordering Group(s) fetched successfully!", new OrderingGroupSetupResource($orderingGroup));
        } catch (ValidationException $exception) {
            return $this->errorResponse(["response" => "Invalid Ordering Group Code request!", "errors" => $exception->errors()], ["orderingGroupCode" => $orderingGroupCode], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (ErrorException $exception) {
            return $this->errorResponse(["response" => "Ordering Group(s) not found!"], ["orderingGroupCode" => $orderingGroupCode], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified ordering group in storage. PUT and PATCH method
     *y
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $orderingGroupCode)
    {
        try {
            // PARAMETER VALIDATION
            $parameterValidator = Validator::make(
                ["orderingGroupCode" => $this->parseParameter($orderingGroupCode)],
                ["orderingGroupCode" => ['required', 'alpha_num', 'min:7']],
                [
                    'orderingGroupCode.required' => 'The Ordering Group Code parameter is required.',
                    'orderingGroupCode.alpha_num' => 'The Ordering Group Code parameter must only contain letters and numbers.',
                    'orderingGroupCode.min' => 'The Ordering Group Code parameter must be at least 7 characters.',
                ],
            );
            $parameterValidator->validate();

            // MODEL QUERY VALIDATION
            $orderingGroup = OrderingGroup::where(["ordering_group_code" => $orderingGroupCode])->firstOrFail();

            // MODEL BODY VALIDATION
            $bodyValidator = Validator::make($request->all(), (new UpdateOrderingGroupRequest())->rules($request->all()));
            $bodyValidator->validate();

            if ($request->orderingGroupDescription) {
                $orderingGroup->ordering_group_description = $request->orderingGroupDescription;
            }
            if ($request->effectiveTo) {
                $orderingGroup->effective_to = $request->effectiveTo;
            }
            if ($request->modifiedBy) {
                $orderingGroup->modified_by = $request->modifiedBy;
            }
            $orderingGroup->save();

            return $this->successResponse("Ordering Group(s) updated successfully!", new OrderingGroupSetupResource($orderingGroup));
        } catch (ValidationException $exception) {
            return $this->errorResponse(
                [
                    "response" => "Invalid Ordering Group Code request!",
                    "errors" => $exception->errors()
                ],
                [
                    "id" => $orderingGroupCode
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse(
                [
                    "response" => "Ordering Group(s) not found!", "exceptionMessage" => "No query results for Ordering Group."
                ],
                [
                    "orderingGroupCode" => $orderingGroupCode,
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
