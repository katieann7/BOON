<?php

namespace App\Http\Controllers\Api\V1\Materials;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Entities\Store;
use App\Models\Materials\CostType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Deliveries\OrderingGroup;
use Illuminate\Support\Facades\Validator;
use App\Models\Materials\MaterialGroupType;
use App\Http\Requests\V1\Materials\MaterialGroupRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\V1\Materials\MaterialGroupCollection;
use App\Http\Resources\V1\Materials\MaterialGroupDropDownCollection;
use App\Http\Resources\V1\Materials\MaterialGroupResource;
use Illuminate\Validation\ValidationException;

class MaterialGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MaterialGroupRequest $request)
    {
        try {
            Store::findOrFail($request->storeId);
            try {
                OrderingGroup::findOrFail($request->orderingGroupId);
                $request->validated($request->all());
                $materialGroups = DB::select('CALL sp_materials_get_material_group(?,?)', array($request->storeId, $request->orderingGroupId));

                if (!count($materialGroups) == 0) {
                    return $this->successResponse("Material Group(s) fetched successfully!", new MaterialGroupDropDownCollection($materialGroups));
                } else {
                    return $this->errorResponse("No Material Group(s) found!", [], Response::HTTP_NOT_FOUND);
                }
            } catch (ModelNotFoundException $exception) {
                return $this->errorResponse(
                    "Ordering Group does not exists!",
                    ["orderingGroupId" => $request->orderingGroupId],
                    Response::HTTP_NOT_FOUND
                );
            }
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse(
                "Store does not exists!",
                ["storeId" => $request->storeId],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialGroupRequest $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showByStoreCodeOrderingGroupCode($storeCode, $orderingGroupCode)
    {

        $validator = Validator::make(
            [
                "storeCode" => $this->parseParameter($storeCode),
                "orderingGroupCode" => $this->parseParameter($orderingGroupCode)
            ],
            [
                "storeCode" => ['required', 'alpha_num', 'min:6', 'max:6'],
                "orderingGroupCode" => ['required', 'alpha_num', 'min:7', 'max:7']
            ],
        );
        $validator->validate();

        $materialGroups = DB::select('CALL sp_materials_get_material_group_of_a_store_ordering_group(?,?)', array($storeCode, $orderingGroupCode));

        if (sizeof($materialGroups) == 0) {
            return $this->errorResponse(
                [
                    "response" => "Ordering Group(s) not found!",
                    "exception" => "No query results for Ordering Group"
                ],
                [
                    "storeCode" => $storeCode,
                    "orderingGroupCode" => $orderingGroupCode
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        return $this->successResponse("Material Group(s) fetched successfully!", new MaterialGroupCollection($materialGroups));
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
        //
    }
}
