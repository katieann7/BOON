<?php

namespace App\Http\Controllers\Api\V1\Deliveries;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Deliveries\DeliveryOrderDetailResource;
use App\Models\Deliveries\DeliveryOrderDetail;
use App\Models\Deliveries\OrderingGroup;
use App\Models\Entities\Store;
use App\Models\Materials\Material;
use App\Models\Materials\Plant;
use App\Models\Materials\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DeliveryOrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($storeCode, $orderNumber, $deliveryDate, $orderingGroupCode, $plantCode, $materialCode, $unitCode, $quantityOrdered, $createdBy, $createdOn, $modifiedBy)
    {
        $plant = Plant::where('plant_code', '=', $plantCode)->where('effective_from', '<=', now())
        ->where('effective_to', '>=', now())->first();

        $material = Material::where('material_code', '=', $materialCode)->where('effective_from', '<=', now())
        ->where('effective_to', '>=', now())->first();

        $unit = Unit::where('unit_code', '=', $unitCode)->where('effective_from', '<=', now())
        ->where('effective_to', '>=', now())->first();

        if ($plant && $material && $unit) {
            $deliveryOrder = DeliveryOrderDetail::firstOrNew([
                "store_code" => $storeCode,
                "delivery_date" => $deliveryDate,
                "ordering_group_code" => $orderingGroupCode,
                "plant_code" => $plantCode,
                "material_code" => $materialCode,
                "unit_code" => $unitCode
            ]);

            if (!$deliveryOrder->exists) {
                $createdOn = Carbon::now();
                $deliveryOrder->delivery_order_number = $orderNumber;
                $deliveryOrder->quantity_ordered = $quantityOrdered;
                $deliveryOrder->sap_response = "A9XC01201FG - ON PROCESS";
                $deliveryOrder->submitted_on = $createdOn;
                $deliveryOrder->created_by = $createdBy;
                $deliveryOrder->created_on = $createdOn;
                $deliveryOrder->modified_by = $createdBy;
                $deliveryOrder->save();

                return $this->successResponse("Delivery Order created successfully!", new DeliveryOrderDetailResource($deliveryOrder));
            } elseif ($deliveryOrder->exists && !Carbon::parse($deliveryOrder->delivery_date)->isPast()) {
                if ($quantityOrdered == 0) {
                    $deliveryOrder->delete();

                    return $this->successResponse("Delivery Order deleted successfully!", null);
                } else if ($deliveryOrder->quantity_ordered != $quantityOrdered) {
                    $deliveryOrder->quantity_ordered = $quantityOrdered;
                    $deliveryOrder->modified_by = $modifiedBy;
                    $deliveryOrder->save();

                    return $this->successResponse("Delivery Order updated successfully!", new DeliveryOrderDetailResource($deliveryOrder));
                }
            }
        } else if (!$plant) {
            return $this->errorResponse(
                "Plant not found! Enter a valid plant.",
                null,
                Response::HTTP_BAD_REQUEST
            );
        } else if (!$material) {
            return $this->errorResponse(
                "Material not found! Enter a valid material.",
                null,
                Response::HTTP_BAD_REQUEST
            );
        } else if (!$unit) {
            return $this->errorResponse(
                "Unit not found! Enter a valid unit",
                null,
                Response::HTTP_BAD_REQUEST
            );
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
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
