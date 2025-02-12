<?php

namespace App\Http\Controllers\Api\V1\Materials;

use Carbon\Carbon;
use App\Models\Entities\Store;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Materials\MaterialGroup;
use App\Models\Deliveries\OrderingGroup;
use Illuminate\Support\Facades\Validator;
use App\Models\Deliveries\DeliveryOrderDetail;
use App\Http\Requests\V1\Materials\MaterialRequest;
use App\Http\Resources\V1\Materials\MaterialsCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Deliveries\OrderingCutoffScheduleConfiguration;
use App\Http\Resources\V1\Deliveries\OrderingCutoffScheduleConfigurationResource;

class MaterialController extends Controller
{
    public function showMaterialsWithScheduleOrders($storeCode, $orderingGroupCode, $materialGroupCode, MaterialRequest $request)
    {
        try {
            /*
                Start::Validate paramters and request
            */
            $validator = Validator::make(
                [
                    "storeCode" => $this->parseParameter($storeCode),
                    "orderingGroupCode" => $this->parseParameter($orderingGroupCode),
                    "materialGroupCode" => $this->parseParameter($materialGroupCode)
                ],
                [
                    "storeCode" => ['required', 'alpha_num', 'min:6', 'max:6'],
                    "orderingGroupCode" => ['required', 'alpha_num', 'min:7', 'max:7'],
                    "materialGroupCode" => ['required', 'alpha_num', 'min:2', 'max:7']
                ],
            );
            $validator->validate();
            /*
                End::Validate paramters and request
            */


            /*
                Start::Query configuration variables
            */
            $effectiveNow = Carbon::today();
            /*
                End::Query configuration variables
            */


            /*
                Start::Query table for validation of effectivity and data extraction, and global variables
            */
            $store = Store::where(["store_code" => $storeCode])->where('effective_from', '<=', $effectiveNow)->where('effective_to', '>=', $effectiveNow)->firstOrFail();
            $orderingGroup = OrderingGroup::where(["ordering_group_code" => $orderingGroupCode])->where('effective_from', '<=', $effectiveNow)->where('effective_to', '>=', $effectiveNow)->firstOrFail();
            $materialGroup = MaterialGroup::where(["material_group_code" => $materialGroupCode])->where('effective_from', '<=', $effectiveNow)->where('effective_to', '>=', $effectiveNow)->firstOrFail();
            $orderingCutoffScheduleConfiguration = (object) (new OrderingCutoffScheduleConfigurationResource(OrderingCutoffScheduleConfiguration::where(["store_id" => $store->id, "ordering_group_id" => $orderingGroup->id])->where('effective_from', '<=', $effectiveNow)->where('effective_to', '>=', $effectiveNow)->firstOrFail()))->jsonSerialize();
            $now = Carbon::now()->setTimeFromTimeString($orderingCutoffScheduleConfiguration->uploadCutOff);
            /*
                End::Query table for validation of effectivity and data extraction, and global variables
            */



            if ($orderingCutoffScheduleConfiguration->uploadScheduleType === "LEAD TIME BY MATERIAL") {
                // LEAD TIME BY MATERIALS
                if ($orderingCutoffScheduleConfiguration->orderingScheduleType === "DAYWEEK") {
                    $dateStart = is_null($request->deliveryDateRangeStart) ? $now->copy()->subDays(3) : Carbon::parse($request->deliveryDateRangeStart)->setTimeFromTimeString($orderingCutoffScheduleConfiguration->uploadCutOff);
                    $numberOfDays = is_null($request->numberOfDays) ? 14 : $request->numberOfDays;
                    $dayWeeks = $orderingCutoffScheduleConfiguration->dayWeeks;
                    $schedules = [];

                    /*
                        Lead time by Material and Dayweek:
                        1. Fetch first the materials based on the parameters (Store Code, Ordering Group Code, Material Group Code).
                        2. Each materials should contain lead time, unit, description, code.
                        3. Each materials have their respective schedule: 2 days before the current date and 3 days less of selected days after the current date.
                        4. Each schedule of the materials contains the ff: calculated cut-off date (current date + lead time), day number (generated), week number (calendar)
                        5. For every generated schedules, store it on an array to be re-used by other materials

                        Criteria:
                        1. Materials must be Selling (Conversion Matrix's Unit Type ID = 1)
                        2. All of the tables traversed must be effective
                        3. Must traversed the master tables of: Unit, Unit Type, Store, Ordering Group, Material Group to get columns needed such as their code and description
                    */

                    $materials = DB::select('CALL sp_materials_get_materials_of_a_store_ordering_material_group(?,?,?)', array($storeCode, $orderingGroupCode, $materialGroupCode));

                    foreach ($materials as $material) { // GENERATION OF SCHEDULE PER MATERIAL
                        $data = [];
                        $day = 1;

                        if (is_null($request->deliveryDateRangeStart)) {
                            while ($day <= $numberOfDays) { // GENERATE SCHEDULE BASED ON NYUMBER OF DAYS ENTERED & IF LEAD TIME
                                $weekNumber = Carbon::parse($dateStart)->weekNumberInMonth;
                                $dayName = $dateStart->isoFormat('dddd');
                                $weekName = "Week" . $weekNumber;

                                if ($dayWeeks[$weekName][$dayName]) { // DAYWEEK VALIDATION, IF TRUE: MEANS, THE COMBINATION OF WEEKNAME AND DAYNAME IS TRUE TO THE SCHEDULE CONFIG
                                    $deliveryDate = Carbon::parse($dateStart)->addDays($material->lead_time)->toDateString();
                                    $cutOffDate = Carbon::parse($dateStart)->toDateString();

                                    //  query quantity from Details of the Material
                                    $qtyDetails = DeliveryOrderDetail::where([
                                        "store_code" => $storeCode,
                                        "delivery_date" => $deliveryDate,
                                        "ordering_group_code" => $orderingGroupCode,
                                        "plant_code" => $material->plant_code,
                                        "material_code" => $material->material_code,
                                        "unit_code" => $material->unit_code
                                    ])->first();

                                    // $qty = (object) ($qtyDetails[0]->quantity_ordered);

                                    array_push($data, [
                                        "day_number" => $day,
                                        "day_name" => $dayName,
                                        "week_number" => $weekNumber,
                                        "deadline" => $cutOffDate,
                                        "date" => $deliveryDate,
                                        'quantity' => $qtyDetails['quantity_ordered']
                                    ]);
                                    $day++; // ADD DAY TO DAY (UNTIL IT REACH THE NUMBEROFDAYS REQUEST)
                                }
                                $dateStart->addDay(); // ADD DAY TO DATESTART (ITERATION OF DATE START)
                            }
                        } else {
                            while ($dateStart <= Carbon::parse($request->deliveryDateRangeEnd)->setTimeFromTimeString($orderingCutoffScheduleConfiguration->uploadCutOff)) { // GENERATE SCHEDULE BASED ON NYUMBER OF DAYS ENTERED & IF LEAD TIME
                                $weekNumber = Carbon::parse($dateStart)->weekNumberInMonth;
                                $dayName = $dateStart->isoFormat('dddd');
                                $weekName = "Week" . $weekNumber;

                                if ($dayWeeks[$weekName][$dayName]) { // DAYWEEK VALIDATION, IF TRUE: MEANS, THE COMBINATION OF WEEKNAME AND DAYNAME IS TRUE TO THE SCHEDULE CONFIG
                                    $deliveryDate = Carbon::parse($dateStart)->addDays($material->lead_time)->toDateString();
                                    $cutOffDate = Carbon::parse($dateStart)->toDateString();

                                    //  query quantity from Details of the Material
                                    $qtyDetails = DeliveryOrderDetail::where([
                                        "store_code" => $storeCode,
                                        "delivery_date" => $deliveryDate,
                                        "ordering_group_code" => $orderingGroupCode,
                                        "plant_code" => $material->plant_code,
                                        "material_code" => $material->material_code,
                                        "unit_code" => $material->unit_code
                                    ])->first();

                                    // $qty = (object) ($qtyDetails[0]->quantity_ordered);

                                    array_push($data, [
                                        "day_number" => $day,
                                        "day_name" => $dayName,
                                        "week_number" => $weekNumber,
                                        "deadline" => $cutOffDate,
                                        "date" => $deliveryDate,
                                        'quantity' => $qtyDetails['quantity_ordered']
                                    ]);

                                    $day++; // ADD DAY TO DAY (UNTIL IT REACH THE NUMBEROFDAYS REQUEST)
                                }
                                $dateStart->addDay(); // ADD DAY TO DATESTART (ITERATION OF DATE START)
                            }
                        }
                        // return $qtyDetails;

                        array_push($schedules, [$material->lead_time => $data]); // ARRAY PUSH THE GENERATED SCHEDULE FOR REUSE
                        $material->schedule = $data;

                        $dateStart = is_null($request->deliveryDateRangeStart) ? $now->copy()->subDays(3) : Carbon::parse($request->deliveryDateRangeStart)->setTimeFromTimeString($orderingCutoffScheduleConfiguration->uploadCutOff);
                        $numberOfDays = is_null($request->numberOfDays) ? 14 : $request->numberOfDays;
                        $dayWeeks = $orderingCutoffScheduleConfiguration->dayWeeks;
                        $schedules = [];
                    }

                    return $this->successResponse("Material(s) fetched successfully!", new MaterialsCollection($materials));
                    // END OF LEAD TIME AND DAYWEEK
                } else if ($orderingCutoffScheduleConfiguration->orderingScheduleType === "ROLLING") {
                    $dateStart =  Carbon::parse($orderingCutoffScheduleConfiguration->referenceDate)->setTimeFromTimeString($orderingCutoffScheduleConfiguration->uploadCutOff);
                    $daysToRoll = $orderingCutoffScheduleConfiguration->daysToRoll;
                    if ($dateStart >= $now) {
                        $dateStart =  Carbon::parse($orderingCutoffScheduleConfiguration->referenceDate)->setTimeFromTimeString($orderingCutoffScheduleConfiguration->uploadCutOff);
                    } else {
                        while ($dateStart <= $now) {
                            $dateStart->addDays($daysToRoll);
                        }
                        $dateStart->subDays($daysToRoll);
                    }
                    $numberOfDays = is_null($request->numberOfDays) ? 14 : $request->numberOfDays;
                    $schedules = [];

                    /*
                        Lead time by Material and Rolling:
                        1. Fetch first the materials based on the parameters (Store Code, Ordering Group Code, Material Group Code).
                        2. Each materials should contain lead time, unit, description, code.
                        3. Each materials have their respective schedule: starting from reference date then add days from days to roll, while their deadline is date + lead time of material.
                        4. Each schedule of the materials contains the ff: calculated cut-off date (current date + lead time), day number (generated), week number (calendar)
                        5. For every generated schedules, store it on an array to be re-used by other materials

                        Criteria:
                        1. Materials must be Selling (Conversion Matrix's Unit Type ID = 1)
                        2. All of the tables traversed must be effective
                        3. Must traversed the master tables of: Unit, Unit Type, Store, Ordering Group, Material Group to get columns needed such as their code and description
                    */

                    $materials = DB::select('CALL sp_materials_get_materials_of_a_store_ordering_material_group(?,?,?)', array($storeCode, $orderingGroupCode, $materialGroupCode));

                    foreach ($materials as $material) {
                        $data = [];
                        $day = 1;

                        if (is_null($request->deliveryDateRangeEnd)) {
                            while ($day <= $numberOfDays) { // GENERATE SCHEDULE BASED ON NYUMBER OF DAYS ENTERED & IF LEAD TIME
                                $weekNumber = Carbon::parse($dateStart)->weekNumberInMonth;
                                $dayName = $dateStart->isoFormat('dddd');

                                $deliveryDate = Carbon::parse($dateStart)->addDays($material->lead_time)->toDateString();
                                $cutOffDate = Carbon::parse($dateStart)->toDateString();

                                //  query quantity from Details of the Material
                                $qtyDetails = DeliveryOrderDetail::where([
                                    "store_code" => $storeCode,
                                    "delivery_date" => $deliveryDate,
                                    "ordering_group_code" => $orderingGroupCode,
                                    "plant_code" => $material->plant_code,
                                    "material_code" => $material->material_code,
                                    "unit_code" => $material->unit_code
                                ])->first();

                                // $qty = (object) ($qtyDetails[0]->quantity_ordered);

                                array_push($data, [
                                    "day_number" => $day,
                                    "day_name" => $dayName,
                                    "week_number" => $weekNumber,
                                    "deadline" => $cutOffDate,
                                    "date" => $deliveryDate,
                                    'quantity' => $qtyDetails['quantity_ordered']
                                ]);

                                $day++; // ADD DAY TO DAY (UNTIL IT REACH THE NUMBEROFDAYS REQUEST)
                                $dateStart->addDays($daysToRoll);
                            }
                        } else {
                            while ($dateStart <= Carbon::parse($request->deliveryDateRangeEnd)->setTimeFromTimeString($orderingCutoffScheduleConfiguration->uploadCutOff)) { // GENERATE SCHEDULE BASED ON NYUMBER OF DAYS ENTERED & IF LEAD TIME
                                $weekNumber = Carbon::parse($dateStart)->weekNumberInMonth;
                                $dayName = $dateStart->isoFormat('dddd');

                                $deliveryDate = Carbon::parse($dateStart)->addDays($material->lead_time)->toDateString();
                                $cutOffDate = Carbon::parse($dateStart)->toDateString();

                                //  query quantity from Details of the Material
                                $qtyDetails = DeliveryOrderDetail::where([
                                    "store_code" => $storeCode,
                                    "delivery_date" => $deliveryDate,
                                    "ordering_group_code" => $orderingGroupCode,
                                    "plant_code" => $material->plant_code,
                                    "material_code" => $material->material_code,
                                    "unit_code" => $material->unit_code
                                ])->first();

                                // $qty = (object) ($qtyDetails[0]->quantity_ordered);

                                array_push($data, [
                                    "day_number" => $day,
                                    "day_name" => $dayName,
                                    "week_number" => $weekNumber,
                                    "deadline" => $cutOffDate,
                                    "date" => $deliveryDate,
                                    'quantity' => $qtyDetails['quantity_ordered']
                                ]);
                                $day++; // ADD DAY TO DAY (UNTIL IT REACH THE NUMBEROFDAYS REQUEST)
                                $dateStart->addDays($daysToRoll);
                            }
                        }

                        // array_push($schedules, [$material->lead_time => $data]); // ARRAY PUSH THE GENERATED SCHEDULE FOR REUSE
                        $material->schedule = $data;
                        // }
                        // else { // EQUATE THE ALREADY EXISTING SCHEULE TO THE MATERIAL
                        //     $material->schedule = $schedules[array_search($material->lead_time, $leadTimes)][$material->lead_time];
                        // }

                        $dateStart =  Carbon::parse($orderingCutoffScheduleConfiguration->referenceDate)->setTimeFromTimeString($orderingCutoffScheduleConfiguration->uploadCutOff);
                        $daysToRoll = $orderingCutoffScheduleConfiguration->daysToRoll;
                        if ($dateStart >= $now) {
                            $dateStart =  Carbon::parse($orderingCutoffScheduleConfiguration->referenceDate)->setTimeFromTimeString($orderingCutoffScheduleConfiguration->uploadCutOff);
                        } else {
                            while ($dateStart <= $now) {
                                $dateStart->addDays($daysToRoll);
                            }
                            $dateStart->subDays($daysToRoll);
                        }
                    }

                    return $this->successResponse("Material(s) fetched successfully!", new MaterialsCollection($materials));
                    // END OF LEAD TIME AND ROLLING
                }
            } else if ($orderingCutoffScheduleConfiguration->uploadScheduleType === "BY SCHEDULE") {
                $dateStart = is_null($request->deliveryDateRangeStart) ? $now->copy()->subDays(3) : Carbon::parse($request->deliveryDateRangeStart)->setTimeFromTimeString($orderingCutoffScheduleConfiguration->uploadCutOff);
                $numberOfDays = is_null($request->numberOfDays) ? 14 : $request->numberOfDays;
                $dayWeeks = $orderingCutoffScheduleConfiguration->dayWeeks;
                $cutOffDayWeeks = $orderingCutoffScheduleConfiguration->cutOffDayWeeks;
                $schedules = [];
                $data = [];
                $day = 1;

                // 2nd step: Assign schedule to each materials
                $materials = DB::select('CALL sp_materials_get_materials_of_a_store_ordering_material_group(?,?,?)', array($storeCode, $orderingGroupCode, $materialGroupCode));

                foreach ($materials as $material) {
                    // 1st step: Generate Schedule first
                    if (is_null($request->deliveryDateRangeEnd)) {
                        while ($day <= $numberOfDays) { // LOOP AROUND
                            $weekNumber = Carbon::parse($dateStart)->weekNumberInMonth;
                            $dayName = $dateStart->isoFormat('dddd');
                            $weekName = "Week" . $weekNumber;
                            $cutOff = "";

                            if ($dayWeeks[$weekName][$dayName]) { // DAYWEEK VALIDATION, IF TRUE: MEANS, THE COMBINATION OF WEEKNAME AND DAYNAME IS TRUE TO THE SCHEDULE CONFIG
                                $des = 0;
                                $cutOffDate = Carbon::parse($dateStart->copy())->addDay();

                                while ($des === 0) {
                                    $cutOffDayName = $cutOffDate->isoFormat('dddd');
                                    $cutOffWeekName = "Week" . $cutOffDate->weekNumberInMonth;

                                    if ($cutOffDayWeeks[$cutOffWeekName][$cutOffDayName]) {
                                        $cutOff = Carbon::parse($cutOffDate)->toDateString();
                                        break;
                                    }
                                    $cutOffDate->addDay();
                                }

                                //  query quantity from Details of the Material
                                $qtyDetails = DeliveryOrderDetail::where([
                                    "store_code" => $storeCode,
                                    "delivery_date" => $cutOff,
                                    "ordering_group_code" => $orderingGroupCode,
                                    "plant_code" => $material->plant_code,
                                    "material_code" => $material->material_code,
                                    "unit_code" => $material->unit_code
                                ])->first();

                                array_push($data, [
                                    "day_number" => $day,
                                    "day_name" => $dayName,
                                    "week_number" => $weekNumber,
                                    "deadline" => Carbon::parse($dateStart)->toDateString(),
                                    "date" => $cutOff,
                                    "quantity" => $qtyDetails['quantity_ordered']
                                ]);

                                $day++; // ADD DAY TO DAY (UNTIL IT REACH THE NUMBEROFDAYS REQUEST)
                            }
                            $dateStart->addDay(); // ADD DAY TO DATESTART (ITERATION OF DATE START)
                        }
                    } else {
                        while ($dateStart <= $request->deliveryDateRangeEnd) { // LOOP AROUND
                            $weekNumber = Carbon::parse($dateStart)->weekNumberInMonth;
                            $dayName = $dateStart->isoFormat('dddd');
                            $weekName = "Week" . $weekNumber;
                            $cutOff = "";

                            if ($dayWeeks[$weekName][$dayName]) { // DAYWEEK VALIDATION, IF TRUE: MEANS, THE COMBINATION OF WEEKNAME AND DAYNAME IS TRUE TO THE SCHEDULE CONFIG
                                $des = 0;
                                $cutOffDate = Carbon::parse($dateStart->copy())->addDay();

                                while ($des === 0) {
                                    $cutOffDayName = $cutOffDate->isoFormat('dddd');
                                    $cutOffWeekName = "Week" . $cutOffDate->weekNumberInMonth;

                                    if ($cutOffDayWeeks[$cutOffWeekName][$cutOffDayName]) {
                                        $cutOff = Carbon::parse($cutOffDate)->toDateString();
                                        break;
                                    }
                                    $cutOffDate->addDay();
                                }

                                //  query quantity from Details of the Material
                                $qtyDetails = DeliveryOrderDetail::where([
                                    "store_code" => $storeCode,
                                    "delivery_date" => $cutOff,
                                    "ordering_group_code" => $orderingGroupCode,
                                    "plant_code" => $material->plant_code,
                                    "material_code" => $material->material_code,
                                    "unit_code" => $material->unit_code
                                ])->first();

                                array_push($data, [
                                    "day_number" => $day,
                                    "day_name" => $dayName,
                                    "week_number" => $weekNumber,
                                    "deadline" => Carbon::parse($dateStart)->toDateString(),
                                    "date" => $cutOff,
                                    "quantity" => $qtyDetails['quantity_ordered']
                                ]);

                                $day++; // ADD DAY TO DAY (UNTIL IT REACH THE NUMBEROFDAYS REQUEST)
                            }
                            $dateStart->addDay(); // ADD DAY TO DATESTART (ITERATION OF DATE START)
                        }
                    }

                    $material->schedule = $data;

                    // Refresh variables
                    $dateStart = is_null($request->deliveryDateRangeStart) ? $now->copy()->subDays(3) : Carbon::parse($request->deliveryDateRangeStart)->setTimeFromTimeString($orderingCutoffScheduleConfiguration->uploadCutOff);
                    $numberOfDays = is_null($request->numberOfDays) ? 14 : $request->numberOfDays;
                    $dayWeeks = $orderingCutoffScheduleConfiguration->dayWeeks;
                    $cutOffDayWeeks = $orderingCutoffScheduleConfiguration->cutOffDayWeeks;
                    $schedules = [];
                    $data = [];
                    $day = 1;
                }
                // // 1st step: Generate Schedule first
                // if (is_null($request->deliveryDateRangeEnd)) {
                //     while ($day <= $numberOfDays) { // LOOP AROUND
                //         $weekNumber = Carbon::parse($dateStart)->weekNumberInMonth;
                //         $dayName = $dateStart->isoFormat('dddd');
                //         $weekName = "Week" . $weekNumber;
                //         $cutOff = "";

                //         if ($dayWeeks[$weekName][$dayName]) { // DAYWEEK VALIDATION, IF TRUE: MEANS, THE COMBINATION OF WEEKNAME AND DAYNAME IS TRUE TO THE SCHEDULE CONFIG
                //             $des = 0;
                //             $cutOffDate = Carbon::parse($dateStart->copy())->addDay();

                //             while ($des === 0) {
                //                 $cutOffDayName = $cutOffDate->isoFormat('dddd');
                //                 $cutOffWeekName = "Week" . $cutOffDate->weekNumberInMonth;

                //                 if ($cutOffDayWeeks[$cutOffWeekName][$cutOffDayName]) {
                //                     $cutOff = Carbon::parse($cutOffDate)->toDateString();
                //                     break;
                //                 }
                //                 $cutOffDate->addDay();
                //             }

                //             array_push($data, [
                //                 "day_number" => $day,
                //                 "day_name" => $dayName,
                //                 "week_number" => $weekNumber,
                //                 "deadline" => Carbon::parse($dateStart)->toDateString(),
                //                 "date" => $cutOff,
                //                 "quantity" => 0
                //             ]);

                //             $day++; // ADD DAY TO DAY (UNTIL IT REACH THE NUMBEROFDAYS REQUEST)
                //         }
                //         $dateStart->addDay(); // ADD DAY TO DATESTART (ITERATION OF DATE START)
                //     }
                // } else {
                //     while ($dateStart <= $request->deliveryDateRangeEnd) { // LOOP AROUND
                //         $weekNumber = Carbon::parse($dateStart)->weekNumberInMonth;
                //         $dayName = $dateStart->isoFormat('dddd');
                //         $weekName = "Week" . $weekNumber;
                //         $cutOff = "";

                //         if ($dayWeeks[$weekName][$dayName]) { // DAYWEEK VALIDATION, IF TRUE: MEANS, THE COMBINATION OF WEEKNAME AND DAYNAME IS TRUE TO THE SCHEDULE CONFIG
                //             $des = 0;
                //             $cutOffDate = Carbon::parse($dateStart->copy())->addDay();

                //             while ($des === 0) {
                //                 $cutOffDayName = $cutOffDate->isoFormat('dddd');
                //                 $cutOffWeekName = "Week" . $cutOffDate->weekNumberInMonth;

                //                 if ($cutOffDayWeeks[$cutOffWeekName][$cutOffDayName]) {
                //                     $cutOff = Carbon::parse($cutOffDate)->toDateString();
                //                     break;
                //                 }
                //                 $cutOffDate->addDay();
                //             }

                //             array_push($data, [
                //                 "day_number" => $day,
                //                 "day_name" => $dayName,
                //                 "week_number" => $weekNumber,
                //                 "deadline" => Carbon::parse($dateStart)->toDateString(),
                //                 "date" => $cutOff,
                //                 "quantity" => 0
                //             ]);

                //             $day++; // ADD DAY TO DAY (UNTIL IT REACH THE NUMBEROFDAYS REQUEST)
                //         }
                //         $dateStart->addDay(); // ADD DAY TO DATESTART (ITERATION OF DATE START)
                //     }
                // }



                // foreach ($materials as $material) {
                //     $material->schedule = $data;
                // }

                return $this->successResponse("Material(s) fetched successfully!", new MaterialsCollection($materials));
            }
        } catch (ModelNotFoundException $exception) {
            return $this->modelExceptionResponse($exception, [
                "storeCode" => $storeCode,
                "orderingGroupCode" => $orderingGroupCode,
                "materialGroupCode" => $materialGroupCode
            ]);
        }
    }
}
