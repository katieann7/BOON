<?php

namespace App\Models\Deliveries;

use App\Models\Entities\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderingCutoffScheduleConfiguration extends Model
{
    /*
    |----------------------------------------------
    |       PROTECTED PROPERTIES::START
    |----------------------------------------------
    */
    protected $table = "deliveries-ordering_cutoff_schedule_configuration_matrix";
    protected $fillable = [
        "store_id",
        "ordering_group_id",
        "upload_cutoff",
        "upload_cutoff_schedule_type",
        "ordering_schedule_type",
        "order_monday_week_1",
        "order_tuesday_week_1",
        "order_wednesday_week_1",
        "order_thursday_week_1",
        "order_friday_week_1",
        "order_saturday_week_1",
        "order_sunday_week_1",
        "order_monday_week_2",
        "order_tuesday_week_2",
        "order_wednesday_week_2",
        "order_thursday_week_2",
        "order_friday_week_2",
        "order_saturday_week_2",
        "order_sunday_week_2",
        "order_monday_week_3",
        "order_tuesday_week_3",
        "order_wednesday_week_3",
        "order_thursday_week_3",
        "order_friday_week_3",
        "order_saturday_week_3",
        "order_sunday_week_3",
        "order_monday_week_4",
        "order_tuesday_week_4",
        "order_wednesday_week_4",
        "order_thursday_week_4",
        "order_friday_week_4",
        "order_saturday_week_4",
        "order_sunday_week_4",
        "order_monday_week_5",
        "order_tuesday_week_5",
        "order_wednesday_week_5",
        "order_thursday_week_5",
        "order_friday_week_5",
        "order_saturday_week_5",
        "order_sunday_week_5",
        "order_monday_week_6",
        "order_tuesday_week_6",
        "order_wednesday_week_6",
        "order_thursday_week_6",
        "order_friday_week_6",
        "order_saturday_week_6",
        "order_sunday_week_6",
        "cutoff_monday_week_1",
        "cutoff_tuesday_week_1",
        "cutoff_wednesday_week_1",
        "cutoff_thursday_week_1",
        "cutoff_friday_week_1",
        "cutoff_saturday_week_1",
        "cutoff_sunday_week_1",
        "cutoff_monday_week_2",
        "cutoff_tuesday_week_2",
        "cutoff_wednesday_week_2",
        "cutoff_thursday_week_2",
        "cutoff_friday_week_2",
        "cutoff_saturday_week_2",
        "cutoff_sunday_week_2",
        "cutoff_monday_week_3",
        "cutoff_tuesday_week_3",
        "cutoff_wednesday_week_3",
        "cutoff_thursday_week_3",
        "cutoff_friday_week_3",
        "cutoff_saturday_week_3",
        "cutoff_sunday_week_3",
        "cutoff_monday_week_4",
        "cutoff_tuesday_week_4",
        "cutoff_wednesday_week_4",
        "cutoff_thursday_week_4",
        "cutoff_friday_week_4",
        "cutoff_saturday_week_4",
        "cutoff_sunday_week_4",
        "cutoff_monday_week_5",
        "cutoff_tuesday_week_5",
        "cutoff_wednesday_week_5",
        "cutoff_thursday_week_5",
        "cutoff_friday_week_5",
        "cutoff_saturday_week_5",
        "cutoff_sunday_week_5",
        "cutoff_monday_week_6",
        "cutoff_tuesday_week_6",
        "cutoff_wednesday_week_6",
        "cutoff_thursday_week_6",
        "cutoff_friday_week_6",
        "cutoff_saturday_week_6",
        "cutoff_sunday_week_6",
        "reference_date",
        "days_to_roll",
        "effective_to",
        "modified_by"
    ];
    // public $incrementing = true;

    // timstamps - migration
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

    public function setUploadCutoffScheduleTypeAttribute($value)
    {
        $this->attributes["upload_cutoff_schedule_type"] = strtoupper($value);
    }

    public function setOrderingScheduleTypeAttribute($value)
    {
        $this->attributes["ordering_schedule_type"] = strtoupper($value);
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
    public function Store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function OrderingGroup(): BelongsTo
    {
        return $this->belongsTo(OrderingGroup::class);
    }
    /*
    |----------------------------------------------
    |        RELATIONSHIP FUNCTIONS::END
    |----------------------------------------------
    */
}
