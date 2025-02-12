<?php

namespace App\Http\Controllers\Api\V1\Materials;

use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use App\Models\Materials\CostType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\V1\Materials\CostTypeRequest;
use App\Http\Resources\V1\Materials\CostTypeResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\V1\Materials\UpdateCostTypeRequest;

class CostTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = Carbon::today();

        $costType = CostType::where('effective_from', '<=', $now)
            ->where('effective_to', '>=', $now)
            ->get();

        return DataTables::of($costType)->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CostTypeRequest $request)
    {
        $costType = CostType::firstOrNew([
            "cost_type_description" => $request->description,
        ]);
        if ($costType->exists && !Carbon::parse($costType->effective_to)->isPast()) {
            return $this->errorResponse(
                "Cost Type is still effective.",
                new CostTypeResource($costType),
                Response::HTTP_CONFLICT
            );
        } else if ($costType->exists && Carbon::parse($costType->effective_to)->isPast()) {
            $costType->effective_to = $request->effectiveTo;
            $costType->modified_by = $request->modifiedBy;
            $costType->save();
            return $this->successResponse("Previous Cost Type has been expired! Cost Type created.", new CostTypeResource($costType));
        } else if (!$costType->exists) {
            $costType->cost_type_code = "CST" . str_pad((CostType::max('id')) + 1, 4, '0', STR_PAD_LEFT);
            $costType->effective_to = $request->effectiveTo;
            $costType->modified_by = $request->modifiedBy;
            $costType->save();
            return $this->successResponse("Cost Type created successfully!", new CostTypeResource($costType));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => ['required', 'alpha_num']],
            [
                'id.required' => 'The ID field is required.',
                'id.alpha_num' => 'The ID field must be a alpha numeric.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse(
                [
                    "response" => "Invalid ID parameter!",
                    "errors" => $validator->errors()
                ],
                [
                    "id" => $id
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $costType = CostType::where(["cost_type_code" => $id])->first();
            return $this->successResponse("Cost Type(s) fetched successfully!", new CostTypeResource($costType));
        } catch (ErrorException $exception) {
            return $this->errorResponse(
                [
                    "response" => "Cost Type(s) not found!"
                ],
                [
                    "id" => $id
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => ['required', 'alpha_num']],
            [
                'id.required' => 'The ID field is required.',
                'id.alpha_num' => 'The ID field must be a alpha numeric.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse(
                [
                    "response" => "Invalid ID parameter!",
                    "errors" => $validator->errors()
                ],
                [
                    "id" => $id
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $validator = Validator::make($request->all(), (new UpdateCostTypeRequest())->rules($request));

        if ($validator->fails()) {
            return $this->errorResponse(
                [
                    "response" => "Invalid Cost Type body content!",
                    "errors" => $validator->errors()
                ],
                [
                    "id" => $id
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $validatedData = $validator->validated();
        try {
            CostType::where('cost_type_code', $id)->firstOrFail();
            $costType = CostType::where('cost_type_code', $id)->first();
            if (isset($validatedData["description"])) {
                $costType->cost_type_description = $request->description;
            }
            if (isset($validatedData["effectiveTo"])) {
                $costType->effective_to = $request->effectiveTo;
            }
            if (isset($validatedData["modifiedBy"])) {
                $costType->modified_by = $request->modifiedBy;
            }
            $costType->save();

            return $this->successResponse("Cost Type(s) updated successfully!", new CostTypeResource($costType));
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse(
                [
                    "response" => "Cost Type(s) not found!", "exceptionMessage" => "No query results for Cost Type."
                ],
                [
                    "id" => $id
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
