<?php

namespace App\Models\Deliveries;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrderDetail extends Model
{
    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::START
    |----------------------------------------------
    */

    protected $table = "deliveries-delivery_order_details";
    protected $fillable = [
        "store_code",
        "delivery_order_number",
        "delivery_date",
        "ordering_group_code",
        "plant_code",
        "material_code",
        "unit_code",
        "quantity_ordered",
        "submitted_on",
        "sap_response",
        "created_by",
        "modified_by",
        "created_on",
    ];

    public $incrementing = true;
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'modified_on';

    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::END
    |----------------------------------------------
    */

    /*
    |----------------------------------------------
    |       SETTER FUNCTIONS::START
    |----------------------------------------------
    */

    public function setDeliveryOrderNumberAttribute($value)
    {
        $this->attributes["delivery_order_number"] = strtoupper($value);
    }

    /*
    |----------------------------------------------
    |       SETTER FUNCTIONS::END
    |----------------------------------------------
    */
}