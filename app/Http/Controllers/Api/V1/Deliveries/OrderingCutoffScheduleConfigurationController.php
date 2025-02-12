<?php

namespace App\Http\Controllers\Api\V1\Deliveries;

use Illuminate\Http\Response;
use App\Models\Entities\Store;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Deliveries\OrderingGroup;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Deliveries\OrderingCutoffScheduleConfiguration;
use App\Http\Requests\V1\Deliveries\OrderingCutoffScheduleConfigurationRequest;
use App\Http\Resources\V1\Deliveries\OrderingCutoffScheduleConfigurationResource;

class OrderingCutoffScheduleConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderingCutoffScheduleConfigurationRequest $request)
    {
        $request->validated($request->all());
        $user = Auth::user();

        $store = Store::where('id', '=', $request->storeId)->where('effective_from', '<=', now())
            ->where('effective_to', '>=', now())->first();
        $orderingGroup = OrderingGroup::where('id', '=', $request->orderingGroupId)->where('effective_from', '<=', now())
            ->where('effective_to', '>=', now())->first();

        if ($orderingGroup && $store) {
            $orderingGroupWithStore = DB::select('CALL sp_deliveries_get_ordering_materials_of_a_store(?)', array($store->store_code));
            $index = array_search($request->orderingGroupId, array_column($orderingGroupWithStore, 'id'));

            if ($index === false) {
                return $this->errorResponse("No matching ordering group found.", null, Response::HTTP_BAD_REQUEST);
            }

            $orderingSchedule = OrderingCutoffScheduleConfiguration::firstOrNew([
                "store_id" => $request->storeId,
                "ordering_group_id" => $request->orderingGroupId,
            ]);
            if ($orderingSchedule->exists && !Carbon::parse($orderingSchedule->effective_to)->isPast()) {
                return $this->errorResponse(
                    "Order Schedule Configuration is still effective.",
                    new OrderingCutoffScheduleConfigurationResource($orderingSchedule->load("OrderingGroup")),
                    Response::HTTP_CONFLICT
                );
            } else if ($orderingSchedule->exists && Carbon::parse($orderingSchedule->effective_to)->isPast()) {

                $orderingSchedule->upload_cutoff = $request->input('uploadCutoff');
                $orderingSchedule->upload_cutoff_schedule_type = $request->input('uploadCutoffScheduleType');
                $orderingSchedule->ordering_schedule_type = $request->input('orderingScheduleType');
                $orderingSchedule->order_monday_week_1 = $request->mondayWeek1;
                $orderingSchedule->order_tuesday_week_1 = $request->tuesdayWeek1;
                $orderingSchedule->order_wednesday_week_1 = $request->wednesdayWeek1;
                $orderingSchedule->order_thursday_week_1 = $request->thursdayWeek1;
                $orderingSchedule->order_friday_week_1 = $request->fridayWeek1;
                $orderingSchedule->order_saturday_week_1 = $request->saturdayWeek1;
                $orderingSchedule->order_sunday_week_1 = $request->sundayWeek1;
                $orderingSchedule->order_monday_week_2 = $request->mondayWeek2;
                $orderingSchedule->order_tuesday_week_2 = $request->tuesdayWeek2;
                $orderingSchedule->order_wednesday_week_2 = $request->wednesdayWeek2;
                $orderingSchedule->order_thursday_week_2 = $request->thursdayWeek2;
                $orderingSchedule->order_friday_week_2 = $request->fridayWeek2;
                $orderingSchedule->order_saturday_week_2 = $request->saturdayWeek2;
                $orderingSchedule->order_sunday_week_2 = $request->sundayWeek2;
                $orderingSchedule->order_monday_week_3 = $request->mondayWeek3;
                $orderingSchedule->order_tuesday_week_3 = $request->tuesdayWeek3;
                $orderingSchedule->order_wednesday_week_3 = $request->wednesdayWeek3;
                $orderingSchedule->order_thursday_week_3 = $request->thursdayWeek3;
                $orderingSchedule->order_friday_week_3 = $request->fridayWeek3;
                $orderingSchedule->order_saturday_week_3 = $request->saturdayWeek3;
                $orderingSchedule->order_sunday_week_3 = $request->sundayWeek3;
                $orderingSchedule->order_monday_week_4 = $request->mondayWeek4;
                $orderingSchedule->order_tuesday_week_4 = $request->tuesdayWeek4;
                $orderingSchedule->order_wednesday_week_4 = $request->wednesdayWeek4;
                $orderingSchedule->order_thursday_week_4 = $request->thursdayWeek4;
                $orderingSchedule->order_friday_week_4 = $request->fridayWeek4;
                $orderingSchedule->order_saturday_week_4 = $request->saturdayWeek4;
                $orderingSchedule->order_sunday_week_4 = $request->sundayWeek4;
                $orderingSchedule->order_monday_week_5 = $request->mondayWeek5;
                $orderingSchedule->order_tuesday_week_5 = $request->tuesdayWeek5;
                $orderingSchedule->order_wednesday_week_5 = $request->wednesdayWeek5;
                $orderingSchedule->order_thursday_week_5 = $request->thursdayWeek5;
                $orderingSchedule->order_friday_week_5 = $request->fridayWeek5;
                $orderingSchedule->order_saturday_week_5 = $request->saturdayWeek5;
                $orderingSchedule->order_sunday_week_5 = $request->sundayWeek5;
                $orderingSchedule->order_monday_week_6 = $request->mondayWeek6;
                $orderingSchedule->order_tuesday_week_6 = $request->tuesdayWeek6;
                $orderingSchedule->order_wednesday_week_6 = $request->wednesdayWeek6;
                $orderingSchedule->order_thursday_week_6 = $request->thursdayWeek6;
                $orderingSchedule->order_friday_week_6 = $request->fridayWeek6;
                $orderingSchedule->order_saturday_week_6 = $request->saturdayWeek6;
                $orderingSchedule->order_sunday_week_6 = $request->sundayWeek6;
                $orderingSchedule->cutoff_monday_week_1 = $request->cutOffMondayWeek1;
                $orderingSchedule->cutoff_tuesday_week_1 = $request->cutOffTuesdayWeek1;
                $orderingSchedule->cutoff_wednesday_week_1 = $request->cutOffWednesdayWeek1;
                $orderingSchedule->cutoff_thursday_week_1 = $request->cutOffThursdayWeek1;
                $orderingSchedule->cutoff_friday_week_1 = $request->cutOffFridayWeek1;
                $orderingSchedule->cutoff_saturday_week_1 = $request->cutOffSaturdayWeek1;
                $orderingSchedule->cutoff_sunday_week_1 = $request->cutOffSundayWeek1;
                $orderingSchedule->cutoff_monday_week_2 = $request->cutOffMondayWeek2;
                $orderingSchedule->cutoff_tuesday_week_2 = $request->cutOffTuesdayWeek2;
                $orderingSchedule->cutoff_wednesday_week_2 = $request->cutOffWednesdayWeek2;
                $orderingSchedule->cutoff_thursday_week_2 = $request->cutOffThursdayWeek2;
                $orderingSchedule->cutoff_friday_week_2 = $request->cutOffFridayWeek2;
                $orderingSchedule->cutoff_saturday_week_2 = $request->cutOffSaturdayWeek2;
                $orderingSchedule->cutoff_sunday_week_2 = $request->cutOffSundayWeek2;
                $orderingSchedule->cutoff_monday_week_3 = $request->cutOffMondayWeek3;
                $orderingSchedule->cutoff_tuesday_week_3 = $request->cutOffTuesdayWeek3;
                $orderingSchedule->cutoff_wednesday_week_3 = $request->cutOffWednesdayWeek3;
                $orderingSchedule->cutoff_thursday_week_3 = $request->cutOffThursdayWeek3;
                $orderingSchedule->cutoff_friday_week_3 = $request->cutOffFridayWeek3;
                $orderingSchedule->cutoff_saturday_week_3 = $request->cutOffSaturdayWeek3;
                $orderingSchedule->cutoff_sunday_week_3 = $request->cutOffSundayWeek3;
                $orderingSchedule->cutoff_monday_week_4 = $request->cutOffMondayWeek4;
                $orderingSchedule->cutoff_tuesday_week_4 = $request->cutOffTuesdayWeek4;
                $orderingSchedule->cutoff_wednesday_week_4 = $request->cutOffWednesdayWeek4;
                $orderingSchedule->cutoff_thursday_week_4 = $request->cutOffThursdayWeek4;
                $orderingSchedule->cutoff_friday_week_4 = $request->cutOffFridayWeek4;
                $orderingSchedule->cutoff_saturday_week_4 = $request->cutOffSaturdayWeek4;
                $orderingSchedule->cutoff_sunday_week_4 = $request->cutOffSundayWeek4;
                $orderingSchedule->cutoff_monday_week_5 = $request->cutOffMondayWeek5;
                $orderingSchedule->cutoff_tuesday_week_5 = $request->cutOffTuesdayWeek5;
                $orderingSchedule->cutoff_wednesday_week_5 = $request->cutOffWednesdayWeek5;
                $orderingSchedule->cutoff_thursday_week_5 = $request->cutOffThursdayWeek5;
                $orderingSchedule->cutoff_friday_week_5 = $request->cutOffFridayWeek5;
                $orderingSchedule->cutoff_saturday_week_5 = $request->cutOffSaturdayWeek5;
                $orderingSchedule->cutoff_sunday_week_5 = $request->cutOffSundayWeek5;
                $orderingSchedule->cutoff_monday_week_6 = $request->cutOffMondayWeek6;
                $orderingSchedule->cutoff_tuesday_week_6 = $request->cutOffTuesdayWeek6;
                $orderingSchedule->cutoff_wednesday_week_6 = $request->cutOffWednesdayWeek6;
                $orderingSchedule->cutoff_thursday_week_6 = $request->cutOffThursdayWeek6;
                $orderingSchedule->cutoff_friday_week_6 = $request->cutOffFridayWeek6;
                $orderingSchedule->cutoff_saturday_week_6 = $request->cutOffSaturdayWeek6;
                $orderingSchedule->cutoff_sunday_week_6 = $request->cutOffSundayWeek6;
                $orderingSchedule->reference_date = $request->referenceDate;
                $orderingSchedule->days_to_roll = $request->daysToRoll;
                $orderingSchedule->effective_to = $request->effectiveTo;
                $orderingSchedule->modified_by = $user->username;
                $orderingSchedule->save();

                return $this->successResponse("Previous configuration has been expired! Order Schedule Configuration created. ", new OrderingCutoffScheduleConfigurationResource($orderingSchedule->load("OrderingGroup")), Response::HTTP_CREATED);
            } else if (!$orderingSchedule->exists) {
                $orderingSchedule->upload_cutoff = $request->input('uploadCutoff');
                $orderingSchedule->upload_cutoff_schedule_type = $request->input('uploadCutoffScheduleType');
                $orderingSchedule->ordering_schedule_type = $request->input('orderingScheduleType');
                $orderingSchedule->order_monday_week_1 = $request->mondayWeek1;
                $orderingSchedule->order_tuesday_week_1 = $request->tuesdayWeek1;
                $orderingSchedule->order_wednesday_week_1 = $request->wednesdayWeek1;
                $orderingSchedule->order_thursday_week_1 = $request->thursdayWeek1;
                $orderingSchedule->order_friday_week_1 = $request->fridayWeek1;
                $orderingSchedule->order_saturday_week_1 = $request->saturdayWeek1;
                $orderingSchedule->order_sunday_week_1 = $request->sundayWeek1;
                $orderingSchedule->order_monday_week_2 = $request->mondayWeek2;
                $orderingSchedule->order_tuesday_week_2 = $request->tuesdayWeek2;
                $orderingSchedule->order_wednesday_week_2 = $request->wednesdayWeek2;
                $orderingSchedule->order_thursday_week_2 = $request->thursdayWeek2;
                $orderingSchedule->order_friday_week_2 = $request->fridayWeek2;
                $orderingSchedule->order_saturday_week_2 = $request->saturdayWeek2;
                $orderingSchedule->order_sunday_week_2 = $request->sundayWeek2;
                $orderingSchedule->order_monday_week_3 = $request->mondayWeek3;
                $orderingSchedule->order_tuesday_week_3 = $request->tuesdayWeek3;
                $orderingSchedule->order_wednesday_week_3 = $request->wednesdayWeek3;
                $orderingSchedule->order_thursday_week_3 = $request->thursdayWeek3;
                $orderingSchedule->order_friday_week_3 = $request->fridayWeek3;
                $orderingSchedule->order_saturday_week_3 = $request->saturdayWeek3;
                $orderingSchedule->order_sunday_week_3 = $request->sundayWeek3;
                $orderingSchedule->order_monday_week_4 = $request->mondayWeek4;
                $orderingSchedule->order_tuesday_week_4 = $request->tuesdayWeek4;
                $orderingSchedule->order_wednesday_week_4 = $request->wednesdayWeek4;
                $orderingSchedule->order_thursday_week_4 = $request->thursdayWeek4;
                $orderingSchedule->order_friday_week_4 = $request->fridayWeek4;
                $orderingSchedule->order_saturday_week_4 = $request->saturdayWeek4;
                $orderingSchedule->order_sunday_week_4 = $request->sundayWeek4;
                $orderingSchedule->order_monday_week_5 = $request->mondayWeek5;
                $orderingSchedule->order_tuesday_week_5 = $request->tuesdayWeek5;
                $orderingSchedule->order_wednesday_week_5 = $request->wednesdayWeek5;
                $orderingSchedule->order_thursday_week_5 = $request->thursdayWeek5;
                $orderingSchedule->order_friday_week_5 = $request->fridayWeek5;
                $orderingSchedule->order_saturday_week_5 = $request->saturdayWeek5;
                $orderingSchedule->order_sunday_week_5 = $request->sundayWeek5;
                $orderingSchedule->order_monday_week_6 = $request->mondayWeek6;
                $orderingSchedule->order_tuesday_week_6 = $request->tuesdayWeek6;
                $orderingSchedule->order_wednesday_week_6 = $request->wednesdayWeek6;
                $orderingSchedule->order_thursday_week_6 = $request->thursdayWeek6;
                $orderingSchedule->order_friday_week_6 = $request->fridayWeek6;
                $orderingSchedule->order_saturday_week_6 = $request->saturdayWeek6;
                $orderingSchedule->order_sunday_week_6 = $request->sundayWeek6;
                $orderingSchedule->cutoff_monday_week_1 = $request->cutOffMondayWeek1;
                $orderingSchedule->cutoff_tuesday_week_1 = $request->cutOffTuesdayWeek1;
                $orderingSchedule->cutoff_wednesday_week_1 = $request->cutOffWednesdayWeek1;
                $orderingSchedule->cutoff_thursday_week_1 = $request->cutOffThursdayWeek1;
                $orderingSchedule->cutoff_friday_week_1 = $request->cutOffFridayWeek1;
                $orderingSchedule->cutoff_saturday_week_1 = $request->cutOffSaturdayWeek1;
                $orderingSchedule->cutoff_sunday_week_1 = $request->cutOffSundayWeek1;
                $orderingSchedule->cutoff_monday_week_2 = $request->cutOffMondayWeek2;
                $orderingSchedule->cutoff_tuesday_week_2 = $request->cutOffTuesdayWeek2;
                $orderingSchedule->cutoff_wednesday_week_2 = $request->cutOffWednesdayWeek2;
                $orderingSchedule->cutoff_thursday_week_2 = $request->cutOffThursdayWeek2;
                $orderingSchedule->cutoff_friday_week_2 = $request->cutOffFridayWeek2;
                $orderingSchedule->cutoff_saturday_week_2 = $request->cutOffSaturdayWeek2;
                $orderingSchedule->cutoff_sunday_week_2 = $request->cutOffSundayWeek2;
                $orderingSchedule->cutoff_monday_week_3 = $request->cutOffMondayWeek3;
                $orderingSchedule->cutoff_tuesday_week_3 = $request->cutOffTuesdayWeek3;
                $orderingSchedule->cutoff_wednesday_week_3 = $request->cutOffWednesdayWeek3;
                $orderingSchedule->cutoff_thursday_week_3 = $request->cutOffThursdayWeek3;
                $orderingSchedule->cutoff_friday_week_3 = $request->cutOffFridayWeek3;
                $orderingSchedule->cutoff_saturday_week_3 = $request->cutOffSaturdayWeek3;
                $orderingSchedule->cutoff_sunday_week_3 = $request->cutOffSundayWeek3;
                $orderingSchedule->cutoff_monday_week_4 = $request->cutOffMondayWeek4;
                $orderingSchedule->cutoff_tuesday_week_4 = $request->cutOffTuesdayWeek4;
                $orderingSchedule->cutoff_wednesday_week_4 = $request->cutOffWednesdayWeek4;
                $orderingSchedule->cutoff_thursday_week_4 = $request->cutOffThursdayWeek4;
                $orderingSchedule->cutoff_friday_week_4 = $request->cutOffFridayWeek4;
                $orderingSchedule->cutoff_saturday_week_4 = $request->cutOffSaturdayWeek4;
                $orderingSchedule->cutoff_sunday_week_4 = $request->cutOffSundayWeek4;
                $orderingSchedule->cutoff_monday_week_5 = $request->cutOffMondayWeek5;
                $orderingSchedule->cutoff_tuesday_week_5 = $request->cutOffTuesdayWeek5;
                $orderingSchedule->cutoff_wednesday_week_5 = $request->cutOffWednesdayWeek5;
                $orderingSchedule->cutoff_thursday_week_5 = $request->cutOffThursdayWeek5;
                $orderingSchedule->cutoff_friday_week_5 = $request->cutOffFridayWeek5;
                $orderingSchedule->cutoff_saturday_week_5 = $request->cutOffSaturdayWeek5;
                $orderingSchedule->cutoff_sunday_week_5 = $request->cutOffSundayWeek5;
                $orderingSchedule->cutoff_monday_week_6 = $request->cutOffMondayWeek6;
                $orderingSchedule->cutoff_tuesday_week_6 = $request->cutOffTuesdayWeek6;
                $orderingSchedule->cutoff_wednesday_week_6 = $request->cutOffWednesdayWeek6;
                $orderingSchedule->cutoff_thursday_week_6 = $request->cutOffThursdayWeek6;
                $orderingSchedule->cutoff_friday_week_6 = $request->cutOffFridayWeek6;
                $orderingSchedule->cutoff_saturday_week_6 = $request->cutOffSaturdayWeek6;
                $orderingSchedule->cutoff_sunday_week_6 = $request->cutOffSundayWeek6;
                $orderingSchedule->reference_date = $request->referenceDate;
                $orderingSchedule->days_to_roll = $request->daysToRoll;
                $orderingSchedule->effective_to = $request->effectiveTo;
                $orderingSchedule->modified_by = $user->username;
                $orderingSchedule->save();
                return $this->successResponse("Order Schedule Configuration created successfully!", new OrderingCutoffScheduleConfigurationResource($orderingSchedule->load("OrderingGroup")), Response::HTTP_CREATED);
            } else {
                return $this->errorResponse(
                    "There might be some errors.",
                    null,
                    Response::HTTP_BAD_REQUEST
                );
            }
        } else if (!$store) {
            return $this->errorResponse(
                "Store not found! Enter a valid store.",
                null,
                Response::HTTP_BAD_REQUEST
            );
        } else if (!$orderingGroup) {
            return $this->errorResponse(
                "Ordering group not found! Enter a valid ordering group.",
                null,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($storeCode, $orderingGroupCode)
    {
        try {
            $validator = Validator::make(
                [
                    "storeCode" => $this->parseParameter($storeCode),
                    "orderingGroupCode" => $this->parseParameter($orderingGroupCode)
                ],
                [
                    "storeCode" => ['required', 'alpha_num', 'min:6', 'max:6'],
                    "orderingGroupCode" => ['required', 'alpha_num', 'min:7', 'max:7'],
                ],
            );
            $validator->validate();
            $store = Store::where(["store_code" => $storeCode])->firstOrFail();
            $orderingGroup = OrderingGroup::where(["ordering_group_code" => $orderingGroupCode])->firstOrFail();
            $schedule = OrderingCutoffScheduleConfiguration::where(["store_id" => $store->id, "ordering_group_id" => $orderingGroup->id])->firstOrFail();

            return $this->successResponse("Ordering Cutoff Schedule Configuration fetched successfully!", new OrderingCutoffScheduleConfigurationResource($schedule));
        } catch (ModelNotFoundException $exception) {
            return $this->modelExceptionResponse($exception, [
                "storeCode" => $storeCode,
                "orderingGroupCode" => $orderingGroupCode
            ], Response::HTTP_OK);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
