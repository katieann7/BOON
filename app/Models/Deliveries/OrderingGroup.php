<?php

namespace App\Models\Deliveries;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderingGroup extends Model
{
    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::START
    |----------------------------------------------
    */
    protected $table = "deliveries-ordering_group_master";

    protected $fillable = [
        "ordering_group_code",
        "ordering_group_description",
        "effective_to",
        "modified_by",
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

    public function setOrderingGroupDescriptionAttribute($value)
    {
        $this->attributes["ordering_group_description"] = strtoupper($value);
    }

    /*
    |----------------------------------------------
    |       SETTER FUNCTIONS::END
    |----------------------------------------------
    */

    ///////////////////////////////////////////////
    /*
    |----------------------------------------------
    |        RELATIONSHIP FUNCTIONS::START
    |----------------------------------------------
    */

    public function OrderScheduleConfiguration(): HasMany
    {
        return $this->hasMany(OrderScheduleConfiguration::class);
    }

    public function materialGroups()
    {
        return $this->belongsToMany(MaterialGroup::class, 'deliveries-ordering_material_loading_matrix', 'ordering_group_id', 'material_group_id', 'id', 'id');
    }

    /*
    |----------------------------------------------
    |        RELATIONSHIP FUNCTIONS::END
    |----------------------------------------------
    */
}
