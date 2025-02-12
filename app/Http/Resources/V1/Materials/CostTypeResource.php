<?php

namespace App\Http\Resources\V1\Materials;

use Illuminate\Http\Resources\Json\JsonResource;

class CostTypeResource extends JsonResource
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
            'code' => $this->cost_type_code,
            'description' => $this->cost_type_description,
        ];
    }
}
