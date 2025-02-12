<?php

namespace App\Models\Materials;

use Illuminate\Database\Eloquent\Model;

class CostType extends Model
{
    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::START
    |----------------------------------------------
    */
    protected $table = "materials-cost_type_master";
    protected $fillable = [
        "cost_type_code",
        "cost_type_description",
        "effective_to",
        "modified_by"
    ];
    public $incrementing = true;

    const CREATED_AT = 'effective_from';
    const UPDATED_AT = 'modified_on';
    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::END
    |----------------------------------------------
    */
}
