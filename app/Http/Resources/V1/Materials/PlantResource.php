<?php

namespace App\Http\Resources\V1\Materials;

use App\Http\Resources\V1\Entities\StoreResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PlantResource extends JsonResource
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
            'id' => $this->id,
            'code' => $this->plant_code,
            'description' => $this->plant_description,
            'salesOrg' => $this->sales_org,
            'stores' => StoreResource::collection($this->whenLoaded('stores')),
            'materials' => $this->whenLoaded('materialsByConversion'),
            'effectiveTo' => $this->effective_to,
        ];
    }
}
