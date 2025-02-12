<?php

namespace App\Models\Materials;

use Illuminate\Database\Eloquent\Model;

class UnitType extends Model
{
    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::START
    |----------------------------------------------
    */
    protected $table = "materials-unit_type_master";
    protected $fillable = [
        "unit_type_code",
        "unit_type_description",
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
