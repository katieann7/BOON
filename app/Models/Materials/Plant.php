<?php

namespace App\Models\Materials;

use App\Models\Deliveries\LoadingGroup;
use App\Models\Deliveries\OrderingGroup;
use App\Models\Entities\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Plant extends Model
{
    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::START
    |----------------------------------------------
    */
    protected $table = "materials-plant_master";
    protected $fillable = [
        'plant_code',
        'plant_description',
        'sales_org',
        'effective_to',
        'modified_by',
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

    public function setPlantDescriptionAttribute($value)
    {
        $this->attributes["plant_description"] = strtoupper($value);
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

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'materials-store_plant_matrix', 'plant_id', 'store_id', 'id', 'id');
    }

    public function orderingGroups()
    {
        return $this->belongsToMany(OrderingGroup::class, 'deliveries-ordering_material_loading_matrix', 'plant_id', 'ordering_group_id', 'id', 'id');
    }

    public function loadingGroups()
    {
        return $this->belongsToMany(LoadingGroup::class, 'deliveries-ordering_material_loading_matrix', 'plant_id', 'loading_group_id', 'id', 'id');
    }

    public function materialGroups()
    {
        return $this->belongsToMany(MaterialGroup::class, 'deliveries-ordering_material_loading_matrix', 'plant_id', 'material_group_id', 'id', 'id');
    }

    public function materialsByConversion()
    {
        return $this->belongsToMany(Material::class, 'materials-material_conversion_matrix', 'plant_id', 'material_id', 'id', 'id');
    }

    public function materialsByPricing()
    {
        return $this->belongsToMany(Material::class, 'materials-material_pricing_matrix', 'plant_id', 'material_id', 'id', 'id');
    }

    public function units()
    {
        return $this->belongsToMany(Unit::class, 'materials-material_conversion_matrix', 'plant_id', 'unit_id', 'id', 'id');
    }

    public function unitTypes()
    {
        return $this->belongsToMany(UnitType::class, 'materials-material_conversion_matrix', 'plant_id', 'unit_type_id', 'id', 'id');
    }
    /*
    |----------------------------------------------
    |        RELATIONSHIP FUNCTIONS::END
    |----------------------------------------------
    */
}
