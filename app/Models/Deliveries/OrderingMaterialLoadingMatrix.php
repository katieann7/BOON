<?php

namespace App\Models\Deliveries;

use App\Models\Materials\MaterialGroup;
use Illuminate\Database\Eloquent\Model;

class OrderingMaterialLoadingMatrix extends Model
{
    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::START
    |----------------------------------------------
    */
    protected $table = "deliveries-ordering_material_loading_matrix";
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
    |        RELATIONSHIP FUNCTIONS::START
    |----------------------------------------------
    */

    public function materialGroup()
    {
        return $this->belongsTo(MaterialGroup::class, 'material_group_id', 'id');
    }

    /*
    |----------------------------------------------
    |        RELATIONSHIP FUNCTIONS::END
    |----------------------------------------------
    */
}
