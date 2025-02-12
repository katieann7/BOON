<!DOCTYPE html>
<html>

<head>
    <title>Back Office</title>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/bootstrap.css') }}" />
    <script src="{{ url('assets/js/axios.min.js') }}"></script>
    <script src="{{ url('assets/js/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ url('assets/js/common.js') }}"></script>
</head>


<body>
    <div class="container">

        <!-- SEARCHES -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Search Parameters</h3>
            </div>
            <!-- DROPDOWN CUSTOMER -->
            <div class="panel-body">
                <div class="form-group">

                    <!-- Store -->
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <div class="form-group">
                                <label for="storeMenu">Store</label>
                                <select class="form-control" id="storeMenu" name="storeMenu">
                                    <option disabled selected>Select a Store</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Ordering Group -->
                    <div class="row mt-5">
                        <div class="col-sm-4 mb-3">
                            <div class="form-group">
                                <label for="orderingGroupMenu">Ordering Group</label>
                                <select class="form-control" id="orderingGroupMenu" name="orderingGroupMenu">
                                    <option disabled selected>Select an Ordering Group</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Upload Cutoff Schedule Type -->
                    <div class="row mt-5">
                        <div class="col-sm-4 mb-3">
                            <div class="form-group">
                                <label for="uploadCutoffMenu">Upload Cutoff Schedule Type</label>
                                <select class="form-control" id="uploadCutoffMenu" name="uploadCutoffMenu">
                                    <option disabled selected>Select an Upload Cutoff Schedule Type</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <!-- Ordering Schedule Type -->
                    <div class="row mt-5">
                        <div class="col-sm-4 mb-3">
                            <div class="form-group">
                                <label for="orderingScheduleMenu">Ordering Schedule Type</label>
                                <select class="form-control" id="orderingScheduleMenu" name="orderingScheduleMenu">
                                    <option disabled selected>Select an Ordering Schedule Type</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- TIME Upload Time Cutoff -->
                    <div class="row">
                        <div class="col-sm-4 mb-3" style="margin-left: 1.8rem;">
                            <div class="form-group">
                                <label for="appt-time">
                                    Upload Time Cutoff:
                                </label>
                            </div>
                            <input id="time" type="time" name="appt-time" min="12:00" max="18:00" required
                                pattern="[0-9]{2}:[0-9]{2}" />
                            <span class="validity"></span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row" style="margin-left: 48rem; margin-bottom: 15px;">
                <button type="submit" class="btn btn-danger" name="view">Reset</button>

                <button type="submit" class="btn btn-success" name="view" style="margin-left: 3rem;">Save
                    Changes</button>
            </div>
        </div>

        <!-- ORDER TABLE -->
        <div class="table-responsive" style=" width: 1140px; overflow:auto;  margin: 0 auto;">
            <table id="table_order" class="table table-bordered table-hover table-striped"
                style=" width: 100%; height: 100%;">
                <thead>
                    <tr>
                        <th>Week</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                        <th>Sunday</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Week 1</td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_monday_week_1"
                                    value="order_monday_week_1" name="order_monday_week_1">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_tuesday_week_1"
                                    value="order_tuesday_week_1" name="order_tuesday_week_1">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_wednesday_week_1"
                                    value="order_wednesday_week_1" name="order_wednesday_week_1">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_thursday_week_1"
                                    value="order_thursday_week_1" name="order_thursday_week_1">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_friday_week_1"
                                    value="order_friday_week_1" name="order_friday_week_1">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_saturday_week_1"
                                    value="order_saturday_week_1" name="order_saturday_week_1">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_sunday_week_1"
                                    value="order_sunday_week_1" name="order_sunday_week_1">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Week 2</td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_monday_week_2"
                                    value="order_monday_week_2" name="order_monday_week_2">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_tuesday_week_2"
                                    value="order_tuesday_week_2" name="order_tuesday_week_2">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_wednesday_week_2"
                                    value="order_wednesday_week_2" name="order_wednesday_week_2">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_thursday_week_2"
                                    value="order_thursday_week_2" name="order_thursday_week_2">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_friday_week_2"
                                    value="order_friday_week_2" name="order_friday_week_2">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_saturday_week_2"
                                    value="order_saturday_week_2" name="order_saturday_week_2">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_sunday_week_2"
                                    value="order_sunday_week_2" name="order_sunday_week_2">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Week 3</td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_monday_week_3"
                                    value="order_monday_week_3" name="order_monday_week_3">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_tuesday_week_3"
                                    value="order_tuesday_week_3" name="order_tuesday_week_3">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_wednesday_week_3"
                                    value="order_wednesday_week_3" name="order_wednesday_week_3">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_thursday_week_3"
                                    value="order_thursday_week_3" name="order_thursday_week_3">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_friday_week_3"
                                    value="order_friday_week_3" name="order_friday_week_3">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_saturday_week_3"
                                    value="order_saturday_week_3" name="order_saturday_week_3">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_sunday_week_3"
                                    value="order_sunday_week_3" name="order_sunday_week_3">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Week 4</td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_monday_week_4"
                                    value="order_monday_week_4" name="order_monday_week_4">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_tuesday_week_4"
                                    value="order_tuesday_week_4" name="order_tuesday_week_4">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_wednesday_week_4"
                                    value="order_wednesday_week_4" name="order_wednesday_week_4">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_thursday_week_4"
                                    value="order_thursday_week_4" name="order_thursday_week_4">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_friday_week_4"
                                    value="order_friday_week_4" name="order_friday_week_4">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_saturday_week_4"
                                    value="order_saturday_week_4" name="order_saturday_week_4">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_sunday_week_4"
                                    value="order_sunday_week_4" name="order_sunday_week_4">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Week 5</td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_monday_week_5"
                                    value="order_monday_week_5" name="order_monday_week_5">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_tuesday_week_5"
                                    value="order_tuesday_week_5" name="order_tuesday_week_5">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_wednesday_week_5"
                                    value="order_wednesday_week_5" name="order_wednesday_week_5">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_thursday_week_5"
                                    value="order_thursday_week_5" name="order_thursday_week_5">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_friday_week_5"
                                    value="order_friday_week_5" name="order_friday_week_5">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_saturday_week_5"
                                    value="order_saturday_week_5" name="order_saturday_week_5">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_sunday_week_5"
                                    value="order_sunday_week_5" name="order_sunday_week_5">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Week 6</td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_monday_week_6"
                                    value="order_monday_week_6" name="order_monday_week_6">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_tuesday_week_6"
                                    value="order_tuesday_week_6" name="order_tuesday_week_6">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_wednesday_week_6"
                                    value="order_wednesday_week_6" name="order_wednesday_week_6">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_thursday_week_6"
                                    value="order_thursday_week_6" name="order_thursday_week_6">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_friday_week_6"
                                    value="order_friday_week_6" name="order_friday_week_6">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_saturday_week_6"
                                    value="order_saturday_week_6" name="order_saturday_week_6">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="order_sunday_week_6"
                                    value="order_sunday_week_6" name="order_sunday_week_6">
                            </div>
                        </td>
                    </tr>
                    </tfoot>
            </table>
        </div>

        <!-- CUTOFF TABLE -->
        <div class="table-responsive" style=" width: 1140px; overflow:auto;  margin: 0 auto;">
            <table id="table_cutoff" class="table table-bordered table-hover table-striped"
                style=" width: 100%; height: 100%;">
                <thead>
                    <tr>
                        <th>Week</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                        <th>Sunday</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Week 1</td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_monday_week_1"
                                    value="cutoff_monday_week_1" name="cutoff_monday_week_1">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_tuesday_week_1"
                                    value="cutoff_tuesday_week_1" name="cutoff_tuesday_week_1">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_wednesday_week_1"
                                    value="cutoff_wednesday_week_1" name="cutoff_wednesday_week_1">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_thursday_week_1"
                                    value="cutoff_thursday_week_1" name="cutoff_thursday_week_1">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_friday_week_1"
                                    value="cutoff_friday_week_1" name="cutoff_friday_week_1">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_saturday_week_1"
                                    value="cutoff_saturday_week_1" name="cutoff_saturday_week_1">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_sunday_week_1"
                                    value="cutoff_sunday_week_1" name="cutoff_sunday_week_1">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Week 2</td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_monday_week_2"
                                    value="cutoff_monday_week_2" name="cutoff_monday_week_2">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_tuesday_week_2"
                                    value="cutoff_tuesday_week_2" name="cutoff_tuesday_week_2">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_wednesday_week_2"
                                    value="cutoff_wednesday_week_2" name="cutoff_wednesday_week_2">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_thursday_week_2"
                                    value="cutoff_thursday_week_2" name="cutoff_thursday_week_2">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_friday_week_2"
                                    value="cutoff_friday_week_2" name="cutoff_friday_week_2">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_saturday_week_2"
                                    value="cutoff_saturday_week_2" name="cutoff_saturday_week_2">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_sunday_week_2"
                                    value="cutoff_sunday_week_2" name="cutoff_sunday_week_2">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Week 3</td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_monday_week_3"
                                    value="cutoff_monday_week_3" name="cutoff_monday_week_3">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_tuesday_week_3"
                                    value="cutoff_tuesday_week_3" name="cutoff_tuesday_week_3">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_wednesday_week_3"
                                    value="cutoff_wednesday_week_3" name="cutoff_wednesday_week_3">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_thursday_week_3"
                                    value="cutoff_thursday_week_3" name="cutoff_thursday_week_3">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_friday_week_3"
                                    value="cutoff_friday_week_3" name="cutoff_friday_week_3">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_saturday_week_3"
                                    value="cutoff_saturday_week_3" name="cutoff_saturday_week_3">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_sunday_week_3"
                                    value="cutoff_sunday_week_3" name="cutoff_sunday_week_3">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Week 4</td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_monday_week_4"
                                    value="cutoff_monday_week_4" name="cutoff_monday_week_4">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_tuesday_week_4"
                                    value="cutoff_tuesday_week_4" name="cutoff_tuesday_week_4">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_wednesday_week_4"
                                    value="cutoff_wednesday_week_4" name="cutoff_wednesday_week_4">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_thursday_week_4"
                                    value="cutoff_thursday_week_4" name="cutoff_thursday_week_4">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_friday_week_4"
                                    value="cutoff_friday_week_4" name="cutoff_friday_week_4">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_saturday_week_4"
                                    value="cutoff_saturday_week_4" name="cutoff_saturday_week_4">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_sunday_week_4"
                                    value="cutoff_sunday_week_4" name="cutoff_sunday_week_4">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Week 5</td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_monday_week_5"
                                    value="cutoff_monday_week_5" name="cutoff_monday_week_5">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_tuesday_week_5"
                                    value="cutoff_tuesday_week_5" name="cutoff_tuesday_week_5">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_wednesday_week_5"
                                    value="cutoff_wednesday_week_5" name="cutoff_wednesday_week_5">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_thursday_week_5"
                                    value="cutoff_thursday_week_5" name="cutoff_thursday_week_5">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_friday_week_5"
                                    value="cutoff_friday_week_5" name="cutoff_friday_week_5">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_saturday_week_5"
                                    value="cutoff_saturday_week_5" name="cutoff_saturday_week_5">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_sunday_week_5"
                                    value="cutoff_sunday_week_5" name="cutoff_sunday_week_5">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Week 6</td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_monday_week_6"
                                    value="cutoff_monday_week_6" name="cutoff_monday_week_6">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_tuesday_week_6"
                                    value="cutoff_tuesday_week_6" name="cutoff_tuesday_week_6">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_wednesday_week_6"
                                    value="cutoff_wednesday_week_6" name="cutoff_wednesday_week_6">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_thursday_week_6"
                                    value="cutoff_thursday_week_6" name="cutoff_thursday_week_6">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_friday_week_6"
                                    value="cutoff_friday_week_6" name="cutoff_friday_week_6">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_saturday_week_6"
                                    value="cutoff_saturday_week_6" name="cutoff_saturday_week_6">
                            </div>
                        </td>
                        <td>
                            <div class="form-check checkbox-xl"
                                style="text-align:center; position: relative; left: 5px;">
                                <input class="form-check-input" type="checkbox" id="cutoff_sunday_week_6"
                                    value="cutoff_sunday_week_6" name="cutoff_sunday_week_6">
                            </div>
                        </td>
                    </tr>
                    </tfoot>
            </table>
        </div>

        {{-- ROLLING --}}
        <div class="table-responsive">
            <div id="calendar" class="form-group">
                MAY CALENDAR NA LALABAS
            </div>
        </div>
    </div>

    <script src="{{ url('assets/js/delivery_module/order_schedule_config.js') }}"></script>
</body>

</html>
