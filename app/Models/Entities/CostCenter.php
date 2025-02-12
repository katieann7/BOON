<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CostCenter extends Model
{
    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::START
    |----------------------------------------------
    */
    protected $table = "entities-cost_center_master";
    public $incrementing = true;

    const CREATED_AT = 'effective_from';
    const UPDATED_AT = 'modified_on';
    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::END
    |----------------------------------------------
    */

    /*
    |----------------------------------------------
    |        RELATIONSHIP FUNCTIONS::START
    |----------------------------------------------
    */
    public function Store(): HasMany
    {
        return $this->hasMany(Store::class);
    }
    /*
    |----------------------------------------------
    |        RELATIONSHIP FUNCTIONS::END
    |----------------------------------------------
    */
}
