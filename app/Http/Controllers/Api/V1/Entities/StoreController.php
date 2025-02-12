<?php

namespace App\Http\Controllers\Api\V1\Entities;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Entities\Store;
use App\Models\Entities\CostCenter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\V1\Entities\StoreRequest;
use App\Http\Resources\V1\Entities\StoreCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
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
    public function show($storeCode)
    {
        //
    }

    /**
     * Display the list of stores from the cost center code provided.
     *
     * @param  costCenterCode  $costCenterCode
     * @return \Illuminate\Http\Response
     */
    public function showByCostCenterCode($costCenterCode)
    {
        try {
            $validator = Validator::make(
                ["costCenterCode" => $this->parseParameter($costCenterCode)],
                ["costCenterCode" => ['required', 'alpha_num', 'min:8', 'max:12']],
            );
            $validator->validate();

            $now = Carbon::now();
            CostCenter::where(['cost_center_code' => $costCenterCode])->firstOrFail();
            $store = Store::select('entities-store_master.*')
                ->join('entities-cost_center_master', 'entities-store_master.cost_center_id', '=', 'entities-cost_center_master.id')
                ->where('entities-cost_center_master.effective_from', '<=', $now)
                ->where('entities-cost_center_master.effective_to', '>=', $now)
                ->where('entities-cost_center_master.cost_center_code', $costCenterCode)
                ->where('entities-store_master.effective_from', '<=', $now)
                ->where('entities-store_master.effective_to', '>=', $now)
                ->get();

            if (sizeof($store) == 0) {
                throw new ModelNotFoundException("Store");
            }

            return $this->successResponse("Store fetched successfully", new StoreCollection($store->load('CostCenter')));
        } catch (ValidationException $exception) {
            return $this->validationExceptionReponse($exception, ["costCenterCode" => $costCenterCode]);
        } catch (ModelNotFoundException $exception) {
            return $this->modelExceptionResponse($exception, ["costCenterCode" => $costCenterCode]);
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
        //
    }
}
