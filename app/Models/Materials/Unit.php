<?php

namespace App\Models\Materials;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::START
    |----------------------------------------------
    */
    protected $table = "materials-unit_master";
    protected $fillable = [
        "unit_code",
        "unit_description",
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

    ///////////////////////////////////////////////

    /*
    |----------------------------------------------
    |       SETTER FUNCTIONS::START
    |----------------------------------------------
    */

    public function setUnitCodeAttribute($value)
    {
        $this->attributes["unit_code"] = strtoupper($value);
    }
    
    public function setUnitDescriptionAttribute($value)
    {
        $this->attributes["unit_description"] = strtoupper($value);
    }

    /*
    |----------------------------------------------
    |       SETTER FUNCTIONS::END
    |----------------------------------------------
    */
}
