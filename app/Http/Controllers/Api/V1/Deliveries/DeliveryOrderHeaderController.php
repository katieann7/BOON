<?php

namespace App\Http\Controllers\Api\V1\Deliveries;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Deliveries\DeliveryOrderHeaderRequest;
use App\Models\Deliveries\DeliveryOrderHeader;
use App\Models\Deliveries\OrderingGroup;
use App\Models\Entities\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DeliveryOrderHeaderController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeliveryOrderHeaderRequest $request)
    {
        $user = Auth::user();
        $orderDetails = app(DeliveryOrderDetailController::class);

        $store = Store::where('store_code', '=', $request->storeCode)->where('effective_from', '<=', now())
        ->where('effective_to', '>=', now())->first();

        $orderingGroup = OrderingGroup::where('ordering_group_code', '=', $request->orderingGroupCode)->where('effective_from', '<=', now())
        ->where('effective_to', '>=', now())->first();

        if ($store && $orderingGroup) {
            $createdOn = Carbon::now();
            $codeExtension = md5($request->deliveryDate);

            $deliveryOrderHeader = DeliveryOrderHeader::firstOrNew([
                "store_code" => $request->storeCode,
                "delivery_date" => $request->deliveryDate,
                "ordering_group_code" => $request->orderingGroupCode,
            ]);

            if (!$deliveryOrderHeader->exists) {
                $orderNumber = $request->storeCode . "-" . substr($codeExtension, 0, 6);
                $deliveryOrderHeader->delivery_order_number = $orderNumber;
                $deliveryOrderHeader->status_code = $request->statusCode;
                $deliveryOrderHeader->submitted_on = $createdOn;
                $deliveryOrderHeader->sap_response = "A9XC01201FG - ON PROCESS";
                $deliveryOrderHeader->created_by = $user->username;
                $deliveryOrderHeader->created_on = $createdOn;
                $deliveryOrderHeader->modified_by = $user->username;

                if ($request->quantityOrdered != null) {
                    $deliveryOrderHeader->save();

                    $details = $orderDetails->store($request->storeCode, $orderNumber, $request->deliveryDate, $request->orderingGroupCode, 
                        $request->plantCode, $request->materialCode, $request->unitCode, $request->quantityOrdered, $user->username, $createdOn, $user->username);

                    return $details;
                }

            } elseif ($deliveryOrderHeader->exists && !Carbon::parse($deliveryOrderHeader->delivery_date)->isPast()) {
                if ($request->quantityOrdered != null) {
                    $details = $orderDetails->store($request->storeCode, $deliveryOrderHeader->delivery_order_number, $request->deliveryDate, $request->orderingGroupCode, 
                        $request->plantCode, $request->materialCode, $request->unitCode, $request->quantityOrdered, $user->username, $createdOn, $user->username);
                    
                    return $details;
                }else {
                    return $this->errorResponse(
                        "Quantity Order is Null.",
                        null,
                        Response::HTTP_BAD_REQUEST
                    );
                }
            }

        } else if (!$store) {
            return $this->errorResponse(
                "Store not found! Enter a valid store.",
                null,
                Response::HTTP_BAD_REQUEST
            );
        } else if (!$orderingGroup) {
            return $this->errorResponse(
                "Ordering Group not found! Enter a valid ordering group.",
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
