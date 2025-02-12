<?php

namespace App\Http\Resources\V1\Deliveries;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryOrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "orderHeader" => [
                "orderNumber" => $this->delivery_order_number,
                "storeCode" => $this->store_code,
                "deliveryDate" => $this->delivery_date,
                "orderingGroupCode" => $this->ordering_group_code,
                "submittedOn" => $this->submitted_on,
                "sapResponse" => $this->sap_response,
            ],
            "orderDetails" => [
                "materialCode" => $this->material_code,
                "plantCode" => $this->plant_code,
                "unitCode" => $this->unit_code,
                "quantityOrdered" => $this->quantity_ordered,
            ],
            "createdBy" => $this->created_by,
            "modifiedBy" => $this->when($this->modified_by != null, function () {
                return $this->modified_by;
            }),
        ];
    }
}
