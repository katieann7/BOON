<?php

namespace App\Http\Controllers\Api\V1\Materials;

use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Materials\MaterialGroupTypeRequest;
use App\Http\Requests\V1\Materials\UpdateMaterialGroupTypeRequest;
use App\Http\Resources\V1\Materials\MaterialGroupTypeResource;
use App\Models\Materials\MaterialGroupType;

class MaterialGroupTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = Carbon::today();

        $materialGroupType = MaterialGroupType::where('effective_from', '<=', $now)
        ->where('effective_to', '>=', $now)
        ->get();

        return DataTables::of($materialGroupType)->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaterialGroupTypeRequest $request)
    {
        $materialGroupType = MaterialGroupType::firstOrNew([
            "material_group_type_description" => $request->description,
        ]);
        if ($materialGroupType->exists && !Carbon::parse($materialGroupType->effective_to)->isPast()) {
            return $this->errorResponse(
                "Material Group Type is still effective.",
                new MaterialGroupTypeResource($materialGroupType),
                Response::HTTP_CONFLICT
            );
        }else if($materialGroupType->exists && Carbon::parse($materialGroupType->effective_to)->isPast()) {
            $materialGroupType->effective_to = $request->effectiveTo;
            $materialGroupType->modified_by = $request->modifiedBy;
            $materialGroupType->save();
            return $this->successResponse("Previous material group type has been expired! Material Group Type created. ", new MaterialGroupTypeResource($materialGroupType));
        }else if(!$materialGroupType->exists) {
            $materialGroupType->effective_to = $request->effectiveTo;
            $materialGroupType->modified_by = $request->modifiedBy;
            $materialGroupType->save();
            return $this->successResponse("Material Group Type created successfully!", new MaterialGroupTypeResource($materialGroupType));
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

        try {
            $materialGroupType = MaterialGroupType::where(["id" => $id])->first();
            return $this->successResponse("Material Group Type(s) fetched successfully!", new MaterialGroupTypeResource($materialGroupType));
        } catch (ErrorException $exception) {
            return $this->errorResponse(
                [
                    "response" => "Material Group Type(s) not found!"
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

        $validator = Validator::make($request->all(), (new UpdateMaterialGroupTypeRequest())->rules($request));

        if ($validator->fails()) {
            return $this->errorResponse(
                [
                    "response" => "Invalid Material Group Type body content!",
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
            MaterialGroupType::findOrFail($id);
            $materialGroupType = MaterialGroupType::find($id);
            if (isset($validatedData["description"])) {
                $materialGroupType->material_group_type_description = $request->description;
            }
            if (isset($validatedData["effectiveTo"])) {
                $materialGroupType->effective_to = $request->effectiveTo;
            }
            if (isset($validatedData["modifiedBy"])) {
                $materialGroupType->modified_by = $request->modifiedBy;
            }
            $materialGroupType->save();

            return $this->successResponse("Material Group Type(s) updated successfully!", new MaterialGroupTypeResource($materialGroupType));
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse(
                [
                    "response" => "Material Group Type(s) not found!", "exceptionMessage" => "No query results for Material Group Type."
                ],
                [
                    "id" => $id
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
