<?php

namespace App\Models\Deliveries;

use Illuminate\Database\Eloquent\Model;

class DeliveryConfirmation extends Model
{

    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::START
    |----------------------------------------------
    */

    protected $table = "deliveries-delivery_confirmation";
    public $incrementing = true;

    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'modified_on';

    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::END
    |----------------------------------------------
    */

    ///////////////////////////////////////////////

    /*
    |----------------------------------------------
    |        RELATIONSHIP FUNCTIONS::START
    |----------------------------------------------
    */

    /*
    |----------------------------------------------
    |        RELATIONSHIP FUNCTIONS::END
    |----------------------------------------------
    */
}
