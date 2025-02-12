<?php

namespace App\Http\Controllers\Api\V1\Materials;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Entities\Store;
use App\Models\Materials\Plant;
use Yajra\DataTables\DataTables;
use App\Models\Entities\CostCenter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\V1\Entities\StoreRequest;
use App\Http\Requests\V1\Materials\PlantRequest;
use App\Http\Resources\V1\Materials\PlantResource;
use App\Http\Resources\V1\Entities\StoreCollection;
use App\Http\Resources\V1\Materials\PlantCollection;
use App\Http\Requests\V1\Materials\UpdatePlantRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Api\V1\Entities\StoreController;

class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = Carbon::today();

        $plants = Plant::query()
            ->whereDate('effective_from', '<=', $now)
            ->whereDate('effective_to', '>=', $now)
            ->get();

        return DataTables::of($plants)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function byCostCenter($costCenterCode)
    {
        try {
            $validator = Validator::make(
                ["costCenterCode" => $this->parseParameter($costCenterCode)],
                ["costCenterCode" => ['required', 'alpha_num', 'min:3', 'max:9']],
            );
            $validator->validate();

            $costCenter = CostCenter::where("cost_center_code", "=", $costCenterCode)->firstOrFail();
            $plants = Store::where('cost_center_id', '=', $costCenter->id)->with('plants')->first();
            return $this->successResponse("Plants fetched successfully!", new PlantCollection($plants->plants));
        } catch (ModelNotFoundException $exception) {
            return $this->modelExceptionResponse($exception, ["costCenterCode" => $costCenterCode]);
        } catch (ValidationException $exception) {
            return $this->validationExceptionReponse($exception, ["costCenterCode" => $costCenterCode]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlantRequest $request)
    {
        $plant = Plant::firstOrNew([
            "plant_description" => $request->plantDescription,
        ]);

        if ($plant->exists && !Carbon::parse($plant->effective_to)->isPast()) {
            return $this->errorResponse(
                "Plant(s) alraedy exists and is still effective.",
                new PlantResource($plant),
                Response::HTTP_CONFLICT
            );
        } else if ($plant->exists && Carbon::parse($plant->effective_to)->isPast()) {
            $plant->effective_to = $request->effectiveTo;
            $plant->modified_by = $request->modifiedBy;
            $plant->save();

            return $this->successResponse("Previous plant has been expired! Ordering Group(s) created. ", new PlantResource($plant));
        } else if (!$plant->exists) {
            $plant->plant_code = $request->plantCode;
            $plant->sales_org = $request->salesOrg;
            $plant->effective_to = $request->effectiveTo;
            $plant->modified_by = $request->modifiedBy;
            $plant->save();

            return $this->successResponse("Plant(s) created successfully!", new PlantResource($plant));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($plantCode)
    {
        try {
            // PARAMETER VALIDATION
            $validator = Validator::make(
                ["plantCode" => $this->parseParameter($plantCode)],
                ["plantCode" => ['required', 'alpha_num', 'min:4']],
                [
                    'plantCode.required' => 'The Plant Code parameter is required.',
                    'plantCode.alpha_num' => 'The Plant Code parameter must only contain letters and numbers.',
                    'plantCode.min' => 'The Plant Code parameter must be at least 4 characters.',
                ],
            );
            $validator->validate();

            // MODEL QUERY VALIDATION
            $plant = Plant::where(['plant_code' => $plantCode])->firstOrFail();
            return $this->successResponse("Plant(s) fetched successfully!", new PlantResource($plant));
        } catch (ValidationException $exception) {
            return $this->errorResponse(
                [
                    "response" => "Invalid Plant Code parameter!",
                    "errors" => $exception->errors()
                ],
                [
                    "plantCode" => $plantCode
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (ModelNotFoundException $exception) {
            return $this->modelExceptionResponse($exception, ["plant_code" => $plantCode]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $plantCode)
    {
        try {
            // PARAMETER VALIDATION
            $validator = Validator::make(
                ["plantCode" => $this->parseParameter($plantCode)],
                ["plantCode" => ['required', 'alpha_num', 'min:4']],
                [
                    'plantCode.required' => 'The Plant Code parameter is required.',
                    'plantCode.alpha_num' => 'The Plant Code parameter must only contain letters and numbers.',
                    'plantCode.min' => 'The Plant Code parameter must be at least 4 characters.',
                ],
            );
            $validator->validate();

            // MODEL QUERY VALIDATION
            $plant = Plant::where(['plant_code' => $plantCode])->firstOrFail();

            // MODEL BODY VALIDATION
            $bodyValidator = Validator::make($request->all(), (new UpdatePlantRequest())->rules($request->all()));
            $bodyValidator->validate();

            if ($request->plantCode) {
                $plant->plant_code = $request->plantCode;
            }
            if ($request->plantDescription) {
                $plant->plant_description = $request->plantDescription;
            }
            if ($request->salesOrg) {
                $plant->sales_org = $request->salesOrg;
            }
            if ($request->effectiveTo) {
                $plant->effective_to = $request->effectiveTo;
            }
            if ($request->modifiedBy) {
                $plant->modified_by = $request->modifiedBy;
            }
            $plant->save();

            return $this->successResponse("Plant(s) updated successfully!", new PlantResource($plant));
        } catch (ValidationException $exception) {
            return $this->errorResponse(
                [
                    "response" => "Invalid Plant Code parameter!",
                    "errors" => $exception->errors()
                ],
                [
                    "plantCode" => $plantCode
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse(
                [
                    "response" => "Ordering Group(s) not found!"
                ],
                [
                    "plantCode" => $plantCode
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
