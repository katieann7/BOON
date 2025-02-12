<?php

namespace App\Http\Resources\V1\Deliveries;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderingCutoffScheduleConfigurationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "storeId" => $this->store_id,
            "orderingGroup" => new OrderingGroupResource($this->whenLoaded("OrderingGroup")),
            "uploadCutOff" => $this->upload_cutoff,
            "uploadScheduleType" => $this->upload_cutoff_schedule_type,
            "orderingScheduleType" => $this->ordering_schedule_type,
            "dayWeeks" => $this->when($this->ordering_schedule_type === "DAYWEEK", function () {
                return [
                    "Week1" => [
                        "Monday" => (bool) $this->order_monday_week_1,
                        "Tuesday" => (bool)$this->order_tuesday_week_1,
                        "Wednesday" => (bool)$this->order_wednesday_week_1,
                        "Thursday" => (bool)$this->order_thursday_week_1,
                        "Friday" => (bool) $this->order_friday_week_1,
                        "Saturday" => (bool)$this->order_saturday_week_1,
                        "Sunday" => (bool) $this->order_sunday_week_1
                    ],
                    "Week2" => [
                        "Monday" => (bool) $this->order_monday_week_2,
                        "Tuesday" => (bool)$this->order_tuesday_week_2,
                        "Wednesday" => (bool)$this->order_wednesday_week_2,
                        "Thursday" => (bool)$this->order_thursday_week_2,
                        "Friday" => (bool) $this->order_friday_week_2,
                        "Saturday" => (bool)$this->order_saturday_week_2,
                        "Sunday" => (bool) $this->order_sunday_week_2
                    ],
                    "Week3" => [
                        "Monday" => (bool) $this->order_monday_week_3,
                        "Tuesday" => (bool)$this->order_tuesday_week_3,
                        "Wednesday" => (bool)$this->order_wednesday_week_3,
                        "Thursday" => (bool)$this->order_thursday_week_3,
                        "Friday" => (bool) $this->order_friday_week_3,
                        "Saturday" => (bool)$this->order_saturday_week_3,
                        "Sunday" => (bool) $this->order_sunday_week_3
                    ],
                    "Week4" => [
                        "Monday" => (bool) $this->order_monday_week_4,
                        "Tuesday" => (bool)$this->order_tuesday_week_4,
                        "Wednesday" => (bool)$this->order_wednesday_week_4,
                        "Thursday" => (bool)$this->order_thursday_week_4,
                        "Friday" => (bool) $this->order_friday_week_4,
                        "Saturday" => (bool)$this->order_saturday_week_4,
                        "Sunday" => (bool) $this->order_sunday_week_4
                    ],
                    "Week5" => [
                        "Monday" => (bool) $this->order_monday_week_5,
                        "Tuesday" => (bool)$this->order_tuesday_week_5,
                        "Wednesday" => (bool)$this->order_wednesday_week_5,
                        "Thursday" => (bool)$this->order_thursday_week_5,
                        "Friday" => (bool) $this->order_friday_week_5,
                        "Saturday" => (bool)$this->order_saturday_week_5,
                        "Sunday" => (bool) $this->order_cutoff_sunday_week_5
                    ],
                    "Week6" => [
                        "Monday" => (bool) $this->order_monday_week_6,
                        "Tuesday" => (bool)$this->order_tuesday_week_6,
                        "Wednesday" => (bool)$this->order_wednesday_week_6,
                        "Thursday" => (bool)$this->order_thursday_week_6,
                        "Friday" => (bool) $this->order_friday_week_6,
                        "Saturday" => (bool)$this->order_saturday_week_6,
                        "Sunday" => (bool) $this->order_cutoff_sunday_week_6
                    ],
                ];
            }),
            "cutOffDayWeeks" => $this->when($this->upload_cutoff_schedule_type === "BY SCHEDULE", function () {
                return [
                    "Week1" => [
                        "Monday" => (bool) $this->cutoff_monday_week_1,
                        "Tuesday" => (bool)$this->cutoff_tuesday_week_1,
                        "Wednesday" => (bool)$this->cutoff_wednesday_week_1,
                        "Thursday" => (bool)$this->cutoff_thursday_week_1,
                        "Friday" => (bool) $this->cutoff_friday_week_1,
                        "Saturday" => (bool)$this->cutoff_saturday_week_1,
                        "Sunday" => (bool) $this->cutoff_sunday_week_1
                    ],
                    "Week2" => [
                        "Monday" => (bool) $this->cutoff_monday_week_2,
                        "Tuesday" => (bool)$this->cutoff_tuesday_week_2,
                        "Wednesday" => (bool)$this->cutoff_wednesday_week_2,
                        "Thursday" => (bool)$this->cutoff_thursday_week_2,
                        "Friday" => (bool) $this->cutoff_friday_week_2,
                        "Saturday" => (bool)$this->cutoff_saturday_week_2,
                        "Sunday" => (bool) $this->cutoff_sunday_week_2
                    ],
                    "Week3" => [
                        "Monday" => (bool) $this->cutoff_monday_week_3,
                        "Tuesday" => (bool)$this->cutoff_tuesday_week_3,
                        "Wednesday" => (bool)$this->cutoff_wednesday_week_3,
                        "Thursday" => (bool)$this->cutoff_thursday_week_3,
                        "Friday" => (bool) $this->cutoff_friday_week_3,
                        "Saturday" => (bool)$this->cutoff_saturday_week_3,
                        "Sunday" => (bool) $this->cutoff_sunday_week_3
                    ],
                    "Week4" => [
                        "Monday" => (bool) $this->cutoff_monday_week_4,
                        "Tuesday" => (bool)$this->cutoff_tuesday_week_4,
                        "Wednesday" => (bool)$this->cutoff_wednesday_week_4,
                        "Thursday" => (bool)$this->cutoff_thursday_week_4,
                        "Friday" => (bool) $this->cutoff_friday_week_4,
                        "Saturday" => (bool)$this->cutoff_saturday_week_4,
                        "Sunday" => (bool) $this->cutoff_sunday_week_4
                    ],
                    "Week5" => [
                        "Monday" => (bool) $this->cutoff_monday_week_5,
                        "Tuesday" => (bool)$this->cutoff_tuesday_week_5,
                        "Wednesday" => (bool)$this->cutoff_wednesday_week_5,
                        "Thursday" => (bool)$this->cutoff_thursday_week_5,
                        "Friday" => (bool) $this->cutoff_friday_week_5,
                        "Saturday" => (bool)$this->cutoff_saturday_week_5,
                        "Sunday" => (bool) $this->cutoff_sunday_week_5
                    ],
                    "Week6" => [
                        "Monday" => (bool) $this->cutoff_monday_week_6,
                        "Tuesday" => (bool)$this->cutoff_tuesday_week_6,
                        "Wednesday" => (bool)$this->cutoff_wednesday_week_6,
                        "Thursday" => (bool)$this->cutoff_thursday_week_6,
                        "Friday" => (bool) $this->cutoff_friday_week_6,
                        "Saturday" => (bool)$this->cutoff_saturday_week_6,
                        "Sunday" => (bool) $this->cutoff_sunday_week_6
                    ],
                ];
            }),
            "referenceDate" => $this->reference_date,
            "daysToRoll" => $this->days_to_roll,
            "effectiveFrom" => $this->effective_from,
            "effectiveTo" => $this->effective_to,
            "modifiedBy" => $this->modified_by
        ];
    }
}
