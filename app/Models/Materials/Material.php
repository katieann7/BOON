<?php

namespace App\Models\Materials;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::START
    |----------------------------------------------
    */
    protected $table = "materials-material_master";
    public $incrementing = true;

    const CREATED_AT = 'effective_from';
    const UPDATED_AT = 'modified_on';
    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::END
    |----------------------------------------------
    */
}
