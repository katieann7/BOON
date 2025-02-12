<?php

namespace App\Models\Materials;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialGroup extends Model
{
    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::START
    |----------------------------------------------
    */
    protected $table = "materials-material_group_master";
    public $incrementing = true;

    protected $fillable = [
        'storeId',
        'orderingGroupId',
    ];

    const CREATED_AT = 'effective_from';
    const UPDATED_AT = 'modified_on';
    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::END
    |----------------------------------------------
    */

    public function materialGroupType(): BelongsTo
    {
        return $this->belongsTo(MaterialGroupType::class, 'material_group_type_id', 'id');
    }
}
