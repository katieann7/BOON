<?php

namespace App\Http\Controllers\Api\V1\Materials;

use ErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Materials\UnitRequest;
use App\Http\Requests\V1\Materials\UpdateUnitRequest;
use App\Http\Resources\V1\Materials\UnitResource;
use Yajra\DataTables\DataTables;
use App\Models\Materials\Unit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = Carbon::today();

        $unit = Unit::where('effective_from', '<=', $now)
        ->where('effective_to', '>=', $now)
        ->get();

        return DataTables::of($unit)->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnitRequest $request)
    {
        $unit = Unit::firstOrNew([
            "unit_code" => $request->code
        ]);
        if ($unit->exists && !Carbon::parse($unit->effective_to)->isPast()) {
            return $this->errorResponse(
                "Unit is still effective.",
                new UnitResource($unit),
                Response::HTTP_CONFLICT
            );
        }else if($unit->exists && Carbon::parse($unit->effective_to)->isPast()) {
            $unit->unit_description = $request->description;
            $unit->effective_to = $request->effectiveTo;
            $unit->modified_by = $request->modifiedBy;
            $unit->save();
            return $this->successResponse("Previous unit has been expired! Unit created.", new UnitResource($unit));
        }else if(!$unit->exists) {
            $unit->unit_description = $request->description;
            $unit->effective_to = $request->effectiveTo;
            $unit->modified_by = $request->modifiedBy;
            $unit->save();
            return $this->successResponse("Unit created successfully!", new UnitResource($unit));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $unitCode
     * @return \Illuminate\Http\Response
     */
    public function show($unitCode)
    {
        $validator = Validator::make(
            ['unitCode' => $unitCode],
            ['unitCode' => ['required', 'string', 'min:1', 'max:5']],
            [
                'unitCode.required' => 'The Unit Code parameter is required.',
                'unitCode.string' => 'The Unit Code parameter must only contain letters.',
                'unitCode.min' => 'The Unit Code parameter must be atleast 1 character.',
                'unitCode.max' => 'The Unit Code parameter must not exceed 5 characters.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse(
                [
                    "response" => "Invalid ID parameter!",
                    "errors" => $validator->errors()
                ],
                [
                    "id" => $unitCode
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $unit = Unit::where(["unit_code" => $unitCode])->first();
            return $this->successResponse("Unit(s) fetched successfully!", new UnitResource($unit));
        } catch (ErrorException $exception) {
            return $this->errorResponse(
                [
                    "response" => "Unit(s) not found!"
                ],
                [
                    "id" => $unitCode
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $unitCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $unitCode)
    {
        $validator = Validator::make(
            ['unitCode' => $unitCode],
            ['unitCode' => ['required', 'string', 'min:1', 'max:5']],
            [
                'unitCode.required' => 'The Unit Code parameter is required.',
                'unitCode.string' => 'The Unit Code parameter must only contain letters.',
                'unitCode.min' => 'The Unit Code parameter must be atleast 1 character.',
                'unitCode.max' => 'The Unit Code parameter must not exceed 5 characters.',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse(
                [
                    "response" => "Invalid ID parameter!",
                    "errors" => $validator->errors()
                ],
                [
                    "id" => $unitCode
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $validator = Validator::make($request->all(), (new UpdateUnitRequest())->rules($request));

        if ($validator->fails()) {
            return $this->errorResponse(
                [
                    "response" => "Invalid Unit body content!",
                    "errors" => $validator->errors()
                ],
                [
                    "id" => $unitCode
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $validatedData = $validator->validated();

        try {
            Unit::where('unit_code', '=', $unitCode)->firstOrFail();
            $unit = Unit::where('unit_code', '=', $unitCode)->first();
            if (isset($validatedData["code"])) {
                $unit->unit_code = $request->code;
            }
            if (isset($validatedData["description"])) {
                $unit->unit_description = $request->description;
            }
            if (isset($validatedData["effectiveTo"])) {
                $unit->effective_to = $request->effectiveTo;
            }
            if (isset($validatedData["modifiedBy"])) {
                $unit->modified_by = $request->modifiedBy;
            }
            $unit->save();

            return $this->successResponse("Unit(s) updated successfully!", new UnitResource($unit));
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse(
                [
                    "response" => "Unit(s) not found!", "exceptionMessage" => "No query results for Unit."
                ],
                [
                    "id" => $unitCode
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
