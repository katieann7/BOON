<?php

namespace App\Models\Entities;

use App\Models\Deliveries\OrderingGroup;
use App\Models\Deliveries\OrderScheduleConfiguration;
use App\Models\Materials\Plant;
use App\Models\Materials\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::START
    |----------------------------------------------
    */
    protected $table = "entities-store_master";
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

    public function CostCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function OrderingScheduleConfig(): HasMany
    {
        return $this->hasMany(OrderScheduleConfiguration::class);
    }

    public function plants(): BelongsToMany
    {
        return $this->belongsToMany(Plant::class, 'materials-store_plant_matrix', 'store_id', 'plant_id', 'id', 'id');
    }

    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class, 'materials-store_plant_matrix', 'store_id', 'vendor_id ', 'id', 'id');
    }

    public function orderingGroups()
    {
        return $this->belongsToMany(OrderingGroup::class, 'deliveries-order_schedule_configuration_matrix', 'store_code', 'ordering_group_id');
    }
    /*
    |----------------------------------------------
    |        RELATIONSHIP FUNCTIONS::END
    |----------------------------------------------
    */
}
