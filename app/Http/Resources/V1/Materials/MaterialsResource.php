<?php

namespace App\Http\Resources\V1\Materials;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialsResource extends JsonResource
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
            'code' => $this->material_code,
            'description' => $this->material_description,
            'conversionQuantity' => $this->base_quantity,
            'conversionLeadTime' => $this->lead_time,
            'unit' =>  [
                'code' => $this->unit_code,
                'description' => $this->unit_description,
            ],
            'materialGroup' => [
                'code' => $this->material_group_code,
                'description' => $this->material_group_description,
            ],
            'orderingGroup' => [
                'code' => $this->ordering_group_code,
                'description' => $this->ordering_group_description,
            ],
            'plant' =>  $this->when($this->plant_code != null, function () {
                return [
                    'code' => $this->when($this->plant_code != null, function () {
                        return $this->plant_code;
                    }),
                    'description' => $this->when($this->plant_description != null, function () {
                        return $this->plant_description;
                    }),
                ];
            }),
            'vendor' => $this->when($this->vendor_code != null, function () {
                return [
                    'code' => $this->when($this->vendor_code != null, function () {
                        return $this->vendor_code;
                    }),
                    'description' => $this->when($this->vendor_description != null, function () {
                        return $this->vendor_description;
                    }),
                ];
            }),
            'schedule' => $this->schedule
        ];
    }
}
