<!DOCTYPE html>
<html>

<head>
    <title>Back Office</title>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/sweetalert.min.css') }}" />
    <script src="{{ url('assets/js/axios.min.js') }}"></script>
    <script src="{{ url('assets/js/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ url('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ url('assets/js/sweetalert.min.js') }}"></script>
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
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <div class="form-group">
                                <label for="customerMenu">Customer</label>
                                <select class="form-control" id="customerMenu" name="customerMenu">
                                    <option value="none" disabled selected>Select a Customer</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4 mb-3">
                            <label for="leadTimesMenu">Lead Times</label>
                            <select class="form-control" id="leadTimesMenu" name="leadTimesMenu">
                                <option disabled selected>Select a Lead Time</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="orderingGroupMenu">Ordering Group</label>
                                <select class="form-control" id="orderingGroupMenu" name="orderingGroupMenu">
                                    <option value="none" disabled selected>Select an Ordering Group</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4 mb-3" style="padding:0px">
                            <div class="form-group">
                                <div id="filterDate2">

                                    <!-- Datepicker as text field -->
                                    <div class="col-lg-6 col-sm-12 mb-3">
                                        <label for="exampleFormControlSelect1">Delivery Date From:</label>
                                        <div class="input-group date" data-date-format="mm/dd/yyyy">
                                            <input type="date" name="date-start" id="date-start" class="form-control"
                                                placeholder="mm/dd/yyyy">

                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12 mb-3">
                                        <label for="exampleFormControlSelect1">Delivery Date To:</label>
                                        <div class="input-group date" data-date-format="mm/dd/yyyy">
                                            <input type="date" name="date-end" id="date-end" class="form-control"
                                                placeholder="mm/dd/yyyy">

                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="materialGroupMenu">Material Group</label>
                            <select class="form-control" id="materialGroupMenu" name="materialGroupMenu">
                                <option disabled selected>Select an Material Group</option>
                            </select>
                        </div>
                        <div class="col-sm-4 ">
                            <div class="form-outline" style="width: 14.5rem;">
                                <label class="form-label" for="typeNumber">Number of Days</label>
                                <input min="0" max="30" type="number" id="typeNumber"
                                    class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-left: 50rem; margin-top: 2rem;">
                        <!-- BUTTON -->
                        <button type="reset" class="btn btn-danger" name="reset" id="reset">Reset</button>
                    </div>
                </div>
                <!-- DROPDOWN ORDERING-GROUP -->
                <div class="col-lg-4">

                </div>


                <!-- SEARCHES -->
                <div class="col-lg-4">





                </div>
            </div>
        </div>

        <!-- WELL Save Changes -->
        <div class="well well-sm" style=" width: 1140px; height:60px; margin: 0 auto;">Click Save Changes to apply
            <button type="submit" class="btn btn-success" style="margin-left: 3rem" id="saveOrder">Save
                Changes</button>

            <i><span style="color: #f00; margin-left: 40rem">Notice: Please encode '0' if you wish to remove
                    quantities.</span></i>
        </div>

        <!-- TABLE -->
        <div class="table-responsive" style=" width: 1140px; height:500px; overflow:auto;  margin: 0 auto;">

            <table id="materials-table" class="table table-bordered table-hover table-striped"
                style=" width: 100%; height: 100%;">
                <thead>
                    <tr id="material-header">
                        <th> Material Code </th>
                        <th> Material Description </th>
                        <th> Selling Unit </th>
                        <th> Lead Time </th>
                        <th> &nbsp;</th>
                        <th> Day1</th>
                        <th> Day2</th>
                        <th> Day3</th>
                        <th> Day4</th>

                    </tr>
                </thead>

                <tbody id="material-body">
                    <tr>
                        <td colspan="9">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>



        <!-- Latest compiled and minified JavaScript -->
        <!-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"
        integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->

        <!-- <script>
            $(document).ready(function() {
                $('#example').DataTable();
                $('.input-group.date').datepicker({
                    format: "mm/dd/yyyy"
                });
            });
        </script> -->
        <script src="{{ url('assets/js/delivery_module/drop_down_menus.js') }}"></script>
        <script src="{{ url('assets/js/delivery_module/material_table.js') }}"></script>
        <script src="{{ url('assets/js/delivery_module/order_saving.js') }}"></script>
</body>

</html>
