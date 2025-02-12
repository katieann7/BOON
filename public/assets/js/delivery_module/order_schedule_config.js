$(function() {
    $(document).ready(function (e) {
        hideField();
        /*

            For drop down of Store

        */
        axios({
            method: "get",
            headers: {
                'Accept': "application/vnd.api+json",
                'Content-Type': "application/vnd.api+json",
                "Authorization": "Bearer " + token
            },
            url: "http://127.0.0.1:8000/api/v1/entities/stores/cost-center/" + costCenterCode
        })
        .then(function(response){

            var store = response.data.data;
            store.forEach(function(store) {
                $('#storeMenu').append('<option value=' + store.code + '>' + store.description + ' - ' + store.code + '</option>');
            });


            /*

                For drop down of Ordering Group based on selected Store

            */
            $('#storeMenu').change(() => {
                hideField();
                $('#orderingGroupMenu').empty();
                $('#orderingGroupMenu').append('<option disabled selected>Select an Ordering Group</option>');
                $('#uploadCutoffMenu').empty();
                $('#uploadCutoffMenu').append('<option disabled selected>Select an Upload Cutoff Schedule Type</option>');
                $('#orderingScheduleMenu').empty();
                $('#orderingScheduleMenu').append('<option disabled selected>Select an Ordering Schedule Type</option>');

                var storeCode = $('#storeMenu').find(':selected').val();
                axios({
                    method: "get",
                    headers: {
                        'Accept': "application/vnd.api+json",
                        'Content-Type': "application/vnd.api+json",
                        "Authorization": "Bearer " + token
                    },
                    url: 'http://127.0.0.1:8000/api/v1/deliveries/ordering-groups/store/' + storeCode
                })
                .then((response) => {

                    var options = response.data.data;
                    options.forEach(function(option) {
                        $('#orderingGroupMenu').append('<option value=' + option.code + '>' + option.description + ' - ' + option.code + '</option>');
                    });

                    /*

                        For Checking if the selected Store and Ordering Group has an existing or saved configuration

                    */
                    $('#orderingGroupMenu').change(() => {
                        hideField();

                        $('#uploadCutoffMenu').empty();
                        $('#uploadCutoffMenu').append('<option disabled selected>Select an Upload Cutoff Schedule Type</option><option value="LEAD TIME BY MATERIAL">LEAD TIME BY MATERIAL</option><option value="BY SCHEDULE">BY SCHEDULE</option>');
                        $('#orderingScheduleMenu').empty();
                        $('#orderingScheduleMenu').append('<option disabled selected>Select an Ordering Schedule Type</option>');

                        var storeCode = $('#storeMenu').find(':selected').val();
                        var orderingGroupCode = $('#orderingGroupMenu').find(':selected').val();

                        axios({
                            method: "get",
                            headers: {
                                'Accept': "application/vnd.api+json",
                                'Content-Type': "application/vnd.api+json",
                                "Authorization": "Bearer " + token
                            },
                            url: "http://127.0.0.1:8000/api/v1/deliveries/ordering-cutoff-schedule-configs/" + storeCode + '/' + orderingGroupCode
                        })
                        .then((response) => {

                            if (response.data.status == "success") {
                                // IPAPAKITA NA NAKACHECK
                                var uploadScheduleType = response.data.data.uploadScheduleType;
                                var orderingScheduleType = response.data.data.orderingScheduleType;

                                $('#uploadCutoffMenu').val(uploadScheduleType).change();
                                if (uploadScheduleType === "LEAD TIME BY MATERIAL"){
                                    $('#orderingScheduleMenu').append('<option value="DAYWEEK">DAYWEEK</option>');
                                    $('#orderingScheduleMenu').append('<option value="ROLLING">ROLLING</option>');
                                } else if (uploadScheduleType === "BY SCHEDULE"){
                                    $('#orderingScheduleMenu').append('<option value="DAYWEEK">DAYWEEK</option>');
                                }
                                $('#orderingScheduleMenu').val(orderingScheduleType).change();

                                $('#time').val(response.data.data.uploadCutOff);

                                if (orderingScheduleType === "ROLLING") {
                                    $('#calendar').toggle();
                                }

                                if (orderingScheduleType === "DAYWEEK") {
                                    $('#table_order').toggle();
                                }

                                if (uploadScheduleType === "BY SCHEDULE") {
                                    $('#table_cutoff').toggle();
                                }


                            } else {
                                /*

                                    For drop down of Ordering Schedule Type

                                */
                                $('#uploadCutoffMenu').change(() => {
                                    $('#orderingScheduleMenu').empty();
                                    $('#orderingScheduleMenu').append('<option disabled selected>Select an Ordering Schedule Type</option>');

                                    var uplaodCutoff = $('#uploadCutoffMenu').find(':selected').val();
                                    if (uplaodCutoff === "LEAD TIME BY MATERIAL"){
                                        $('#orderingScheduleMenu').append('<option value="DAYWEEK">DAYWEEK</option>');
                                        $('#orderingScheduleMenu').append('<option value="ROLLING">ROLLING</option>');
                                    } else if (uplaodCutoff === "BY SCHEDULE"){
                                        $('#orderingScheduleMenu').append('<option value="DAYWEEK">DAYWEEK</option>');
                                    }

                                    $('#orderingScheduleMenu').change(() => {
                                        var uploadCutoff = $('#uploadCutoffMenu').find(':selected').val();
                                        var orderingSchedule = $('#orderingScheduleMenu').find(':selected').val();
                                        console.log(`${uploadCutoff} - ${orderingSchedule} dito sa error`);

                                        if (orderingSchedule === "DAYWEEK") {
                                            $('#table_order').toggle();
                                        }

                                        if (orderingSchedule === "ROLLING") {
                                            $('#table_cutoff').toggle();
                                        }

                                        if (uploadCutoff === "BY SCHEDULE") {
                                            $('#table_cutoff').toggle();
                                        }
                                    });

                                });


                            } // End of Else (error status)
                        })
                        .catch((error) => {
                            alert(error)

                        });
                    });

                })
                .catch((error) => {
                    alert(error);
                });
            });

        })
        .catch((error) => {
            alert(error);
        });


        function hideField() {
            $('#table_order').hide();
            $('#table_cutoff').hide();
            $('#calendar').hide();
        }
    });
});













