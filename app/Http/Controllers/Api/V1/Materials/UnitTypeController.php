<?php

namespace App\Http\Controllers\Api\V1\Materials;

use ErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Materials\UnitTypeRequest;
use App\Http\Requests\V1\Materials\UpdateUnitTypeRequest;
use App\Http\Resources\V1\Materials\UnitTypeResource;
use Yajra\DataTables\DataTables;
use App\Models\Materials\UnitType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UnitTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = Carbon::today();

        $unitType = UnitType::where('effective_from', '<=', $now)
            ->where('effective_to', '>=', $now)
            ->get();

        return DataTables::of($unitType)->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnitTypeRequest $request)
    {
        $unitType = UnitType::firstOrNew([
            "unit_type_description" => $request->description,
        ]);
        if ($unitType->exists && !Carbon::parse($unitType->effective_to)->isPast()) {
            if ($unitType->order == $request->order) {
                return $this->errorResponse(
                    "Unit is still effective.",
                    new UnitTypeResource($unitType),
                    Response::HTTP_CONFLICT
                );
            } else {
                return $this->errorResponse(
                    "Order does not match the fetch data! Please check the inputted data.",
                    null,
                    Response::HTTP_CONFLICT
                );
            }
        } else if ($unitType->exists && Carbon::parse($unitType->effective_to)->isPast()) {
            if ($unitType->order == $request->order) {
                $unitType->effective_to = $request->effectiveTo;
                $unitType->modified_by = $request->modifiedBy;
                $unitType->save();
                return $this->successResponse("Previous unit type has been expired! Unit Type created.", new UnitTypeResource($unitType));
            } else {
                return $this->errorResponse(
                    "Order does not match the fetch data! Please check the inputted data.",
                    null,
                    Response::HTTP_CONFLICT
                );
            }
        } else if (!$unitType->exists) {
            $unitTypeOrder = UnitType::where("order", "=", $request->order)->first();
            if (!$unitTypeOrder) {
                $unitType->unit_type_code = "UTY" . str_pad((UnitType::max('id')) + 1, 4, '0', STR_PAD_LEFT);
                $unitType->order = $request->order;
                $unitType->effective_to = $request->effectiveTo;
                $unitType->modified_by = $request->modifiedBy;
                $unitType->save();
                return $this->successResponse("Unit Type created successfully!", new UnitTypeResource($unitType));
            } else {
                return $this->errorResponse(
                    "Order has already been taken",
                    null,
                    Response::HTTP_CONFLICT
                );
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($unitTypeCode)
    {
        $validator = Validator::make(
            ['unitTypeCode' => $this->parseParameter($unitTypeCode)],
            ['unitTypeCode' => ['required', 'alpha_num', 'min:7']],
            [
                'unitTypeCode.required' => 'The Unit Type Code parameter is required.',
                'unitTypeCode.alpha_num' => 'The Unit Type Code parameter must only contain letters and numbers.',
                'unitTypeCode.min' => 'The Unit Type Code parameter must be at least 7 characters.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse(
                [
                    "response" => "Invalid ID parameter!",
                    "errors" => $validator->errors()
                ],
                [
                    "id" => $unitTypeCode
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $unitType = UnitType::where(["unit_type_code" => $unitTypeCode])->first();
            return $this->successResponse("Unit(s) fetched successfully!", new UnitTypeResource($unitType));
        } catch (ErrorException $exception) {
            return $this->errorResponse(
                [
                    "response" => "Unit(s) not found!"
                ],
                [
                    "id" => $unitTypeCode
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
            ['id' => ['required', 'numeric', 'gt:0']],
            [
                'id.required' => 'The ID field is required.',
                'id.numeric' => 'The ID field must be a number.',
                'id.gt' => 'The ID field must be greater than 0.',
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

        $validator = Validator::make($request->all(), (new UpdateUnitTypeRequest())->rules($request));

        if ($validator->fails()) {
            return $this->errorResponse(
                [
                    "response" => "Invalid Unit Type body content!",
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
            UnitType::findOrFail($id);
            $unitType = UnitType::find($id);
            if (isset($validatedData["description"])) {
                $unitType->unit_type_description = $request->description;
            }
            if (isset($validatedData["order"])) {
                $unitType->order = $request->order;
            }
            if (isset($validatedData["effectiveTo"])) {
                $unitType->effective_to = $request->effectiveTo;
            }
            if (isset($validatedData["modifiedBy"])) {
                $unitType->modified_by = $request->modifiedBy;
            }
            $unitType->save();

            return $this->successResponse("Unit Type(s) updated successfully!", new UnitTypeResource($unitType));
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse(
                [
                    "response" => "Unit Type(s) not found!", "exceptionMessage" => "No query results for Unit Type."
                ],
                [
                    "id" => $id
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
