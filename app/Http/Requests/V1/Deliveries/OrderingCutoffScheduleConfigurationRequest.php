<?php

namespace App\Http\Requests\V1\Deliveries;

use Illuminate\Foundation\Http\FormRequest;

class OrderingCutoffScheduleConfigurationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $dayWeekValidation = [
            'required_if:orderingScheduleType,DAYWEEK',
            'integer',
            function ($attribute, $value, $fail) {
                $this->dayWeekValidation($attribute, $value, $fail);
            },
        ];

        $cutoffValidation = [
            'required_if:uploadCutoffScheduleType,BY SCHEDULE',
            'integer',
            function ($attribute, $value, $fail) {
                $this->cutOffValidation($attribute, $value, $fail);
            }
        ];

        return [
            "storeId" => 'required|numeric|gt:0',
            "orderingGroupId" => 'required|numeric|gt:0',
            "uploadCutoff" => 'required|string',
            "uploadCutoffScheduleType" => 'required|string|in:LEAD TIME BY MATERIAL,BY SCHEDULE',
            "orderingScheduleType" => [
                'required',
                'string',
                'in:ROLLING,DAYWEEK',
                function ($attribute, $value, $fail) {
                    if ($this->input('uploadCutoffScheduleType') === 'BY SCHEDULE' && $this->input('orderingScheduleType') === 'ROLLING') {
                        $fail("The $attribute field must used DAYWEEK if upload cutoff schedule type is BY SCHEDULE.");
                    }
                },
            ],
            "mondayWeek1" => $dayWeekValidation,
            "tuesdayWeek1" => $dayWeekValidation,
            "wednesdayWeek1" => $dayWeekValidation,
            "thursdayWeek1" => $dayWeekValidation,
            "fridayWeek1" => $dayWeekValidation,
            "saturdayWeek1" => $dayWeekValidation,
            "sundayWeek1" => $dayWeekValidation,
            "mondayWeek2" => $dayWeekValidation,
            "tuesdayWeek2" => $dayWeekValidation,
            "wednesdayWeek2" => $dayWeekValidation,
            "thursdayWeek2" => $dayWeekValidation,
            "fridayWeek2" => $dayWeekValidation,
            "saturdayWeek2" => $dayWeekValidation,
            "sundayWeek2" => $dayWeekValidation,
            "mondayWeek3" => $dayWeekValidation,
            "tuesdayWeek3" => $dayWeekValidation,
            "wednesdayWeek3" => $dayWeekValidation,
            "thursdayWeek3" => $dayWeekValidation,
            "fridayWeek3" => $dayWeekValidation,
            "saturdayWeek3" => $dayWeekValidation,
            "sundayWeek3" => $dayWeekValidation,
            "mondayWeek4" => $dayWeekValidation,
            "tuesdayWeek4" => $dayWeekValidation,
            "wednesdayWeek4" => $dayWeekValidation,
            "thursdayWeek4" => $dayWeekValidation,
            "fridayWeek4" => $dayWeekValidation,
            "saturdayWeek4" => $dayWeekValidation,
            "sundayWeek4" => $dayWeekValidation,
            "mondayWeek5" => $dayWeekValidation,
            "tuesdayWeek5" => $dayWeekValidation,
            "wednesdayWeek5" => $dayWeekValidation,
            "thursdayWeek5" => $dayWeekValidation,
            "fridayWeek5" => $dayWeekValidation,
            "saturdayWeek5" => $dayWeekValidation,
            "sundayWeek5" => $dayWeekValidation,
            "mondayWeek6" => $dayWeekValidation,
            "tuesdayWeek6" => $dayWeekValidation,
            "wednesdayWeek6" => $dayWeekValidation,
            "thursdayWeek6" => $dayWeekValidation,
            "fridayWeek6" => $dayWeekValidation,
            "saturdayWeek6" => $dayWeekValidation,
            "sundayWeek6" => $dayWeekValidation,
            "cutOffMondayWeek1" => $cutoffValidation,
            "cutOffTuesdayWeek1" => $cutoffValidation,
            "cutOffWednesdayWeek1" => $cutoffValidation,
            "cutOffThursdayWeek1" => $cutoffValidation,
            "cutOffFridayWeek1" => $cutoffValidation,
            "cutOffSaturdayWeek1" => $cutoffValidation,
            "cutOffSundayWeek1" => $cutoffValidation,
            "cutOffMondayWeek2" => $cutoffValidation,
            "cutOffTuesdayWeek2" => $cutoffValidation,
            "cutOffWednesdayWeek2" => $cutoffValidation,
            "cutOffThursdayWeek2" => $cutoffValidation,
            "cutOffFridayWeek2" => $cutoffValidation,
            "cutOffSaturdayWeek2" => $cutoffValidation,
            "cutOffSundayWeek2" => $cutoffValidation,
            "cutOffMondayWeek3" => $cutoffValidation,
            "cutOffTuesdayWeek3" => $cutoffValidation,
            "cutOffWednesdayWeek3" => $cutoffValidation,
            "cutOffThursdayWeek3" => $cutoffValidation,
            "cutOffFridayWeek3" => $cutoffValidation,
            "cutOffSaturdayWeek3" => $cutoffValidation,
            "cutOffSundayWeek3" => $cutoffValidation,
            "cutOffMondayWeek4" => $cutoffValidation,
            "cutOffTuesdayWeek4" => $cutoffValidation,
            "cutOffWednesdayWeek4" => $cutoffValidation,
            "cutOffThursdayWeek4" => $cutoffValidation,
            "cutOffFridayWeek4" => $cutoffValidation,
            "cutOffSaturdayWeek4" => $cutoffValidation,
            "cutOffSundayWeek4" => $cutoffValidation,
            "cutOffMondayWeek5" => $cutoffValidation,
            "cutOffTuesdayWeek5" => $cutoffValidation,
            "cutOffWednesdayWeek5" => $cutoffValidation,
            "cutOffThursdayWeek5" => $cutoffValidation,
            "cutOffFridayWeek5" => $cutoffValidation,
            "cutOffSaturdayWeek5" => $cutoffValidation,
            "cutOffSundayWeek5" => $cutoffValidation,
            "cutOffMondayWeek6" => $cutoffValidation,
            "cutOffTuesdayWeek6" => $cutoffValidation,
            "cutOffWednesdayWeek6" => $cutoffValidation,
            "cutOffThursdayWeek6" => $cutoffValidation,
            "cutOffFridayWeek6" => $cutoffValidation,
            "cutOffSaturdayWeek6" => $cutoffValidation,
            "cutOffSundayWeek6" => $cutoffValidation,
            "referenceDate" => [
                'required_if:orderingScheduleType,ROLLING',
                'date_format:Y-m-d',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    if ($this->input('orderingScheduleType') === 'DAYWEEK') {
                        $fail("The $attribute field cannot be used with DAYWEEK ordering schedule type.");
                    }
                },
            ],
            "daysToRoll" => [
                'required_if:orderingScheduleType,ROLLING',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($this->input('orderingScheduleType') === 'DAYWEEK') {
                        $fail("The $attribute field cannot be used with DAYWEEK ordering schedule type.");
                    }
                },
            ],
            "effectiveTo" => 'required|date_format:Y-m-d|after:today',
        ];
    }

    public function dayWeekValidation($attribute, $value, $fail)
    {
        if ($this->input('orderingScheduleType') === 'ROLLING') {
            $fail("The $attribute field cannot be used with ROLLING ordering schedule type.");
        } elseif (!$this->hasWeekdayValue()) {
            $fail("At least one day of the week must have a value.");
        }
    }

    public function cutOffValidation($attribute, $value, $fail)
    {
        if ($this->input('uploadCutoffScheduleType') === 'LEAD TIME BY MATERIAL') {
            $fail("The $attribute field cannot be used with LEAD TIME BY MATERIAL upload cutoff schedule type.");
        } elseif (!$this->hasCutOffWeekdayValue()) {
            $fail("At least one day of the week must have a value.");
        } elseif (!$this->cutOffWeekdayValueLimit()) {
            $fail("The cutoff day should be only one day.");
        }
    }

    private function hasWeekdayValue()
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($days as $day) {
            for ($i = 1; $i <= 6; $i++) {
                $field = $day . 'Week' . $i;

                if ($this->input($field) !== 0) {
                    return true;
                }
            }
        }
        return false;
    }

    private function hasCutOffWeekdayValue()
    {
        $days = ['cutOffMonday', 'cutOffTuesday', 'cutOffWednesday', 'cutOffThursday', 'cutOffFriday', 'cutOffSaturday', 'cutOffSunday'];
        foreach ($days as $day) {
            for ($i = 1; $i <= 6; $i++) {
                $field = $day . 'Week' . $i;

                if ($this->input($field) !== 0) {
                    return true;
                }
            }
        }
        return false;
    }

    private function cutOffWeekdayValueLimit()
    {
        $counter = 0;
        $days = ['cutOffMonday', 'cutOffTuesday', 'cutOffWednesday', 'cutOffThursday', 'cutOffFriday', 'cutOffSaturday', 'cutOffSunday'];
        foreach ($days as $day) {
            for ($i = 1; $i <= 6; $i++) {
                $field = $day . 'Week' . $i;

                if ($this->input($field) !== 0) {
                    $counter++;
                }
            }
        }
        return ($counter === 1);
    }
}
