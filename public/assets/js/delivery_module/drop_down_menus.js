

$(function() {
    $(document).ready(function (e) {

        /*

            For drop down of Store

        */
        axios({
            method: "get",
            headers: {
                'Accept': "application/vnd.api+json",
                'Content-Type': "application/vnd.api+json",
                'Authorization': "Bearer "+ token
            },
            url: "http://127.0.0.1:8000/api/v1/entities/stores/cost-center/" + costCenterCode
        })
        .then(function(response){
            var customer = response.data.data;
            customer.forEach(function(customer) {
                $('#customerMenu').append('<option value=' + customer.code + '>' + customer.description + ' - ' + customer.code + '</option>');
            });

        })
        .catch((error) => {
            alert(error);
        });


        /*

            For drop down of Ordering Group based on selected Store

        */
        $('#customerMenu').change(() => {
            $('#leadTimesMenu').val('Select a Lead Time');
            $('#orderingGroupMenu').empty();
            $('#orderingGroupMenu').append('<option disabled selected>Select an Ordering Group</option>');
            $('#materialGroupMenu').empty();
            $('#materialGroupMenu').append('<option disabled selected>Select an Material Group</option>');
            var storeCode = $(this).find(':selected').val();
            axios({
                method: "get",
                headers: {
                    'Accept': "application/vnd.api+json",
                    'Content-Type': "application/vnd.api+json",
                    'Authorization': "Bearer "+ token
                },
                url: 'http://127.0.0.1:8000/api/v1/deliveries/ordering-groups/store/' + storeCode
            })
            .then((response) => {
                var options = response.data.data;

                options.forEach(function(option) {
                    $('#orderingGroupMenu').append('<option value=' + option.code + '>' + option.description + ' - ' + option.code + '</option>');
                });
            })
            .catch((error) => {
                console.log(error);
            });
        });


        /*

            For drop down of Ordering Group based on selected Store

        */
        $('#orderingGroupMenu').change(() => {
            $('#leadTimesMenu').val('Select a Lead Time');
            $('#materialGroupMenu').empty();
            $('#materialGroupMenu').append('<option disabled selected>Select an Material Group</option>');
            var orderingGroupCode = $('#orderingGroupMenu').find(':selected').val();
            var storeCode = $('#customerMenu').find(':selected').val();

            // Check if Store and Ordering Group has Schedule
            axios({
                method: "get",
                headers: {
                    'Accept': "application/vnd.api+json",
                    'Content-Type': "application/vnd.api+json",
                    'Authorization': "Bearer "+ token
                },
                url: 'http://127.0.0.1:8000/api/v1/deliveries/ordering-cutoff-schedule-configs/' + storeCode + '/' + orderingGroupCode
            })
            .then((response) => {
                var data = response.data;

                if(data.status === "error") {
                    Swal.fire({
                        icon: data.status,
                        title: 'Oops...',
                        text: data.message.exception,
                        footer: '<a href="http://127.0.0.1:8000/schedule-config">Contact Admin for Schedule Configuration.</a>'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    // Get Material Group
                    axios({
                        method: "get",
                        headers: {
                            'Accept': "application/vnd.api+json",
                            'Content-Type': "application/vnd.api+json",
                            'Authorization': "Bearer "+ token
                        },
                        url: 'http://127.0.0.1:8000/api/v1/materials/material-groups/' + storeCode + '/' + orderingGroupCode
                    })
                    .then((response) => {
                        var options = response.data.data;

                        options.forEach(function(option) {
                            $('#materialGroupMenu').append('<option value=' + option.code + '>' + option.description + ' - ' + option.code + '</option>');
                        });

                        /*

                            For table of Materials

                        */
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                }
            })
            .catch((error) => {
                console.log(error);
            });
        });

        /*

            For drop down of Lead Time 

        */
        $('#leadTimesMenu').change(function() {
            var selectedLeadTime = $(this).val();

            $("tbody[name=materials]").each(function() {
                var leadTimeCell = $("input[name=lead_time]").val();

                if (leadTimeCell === selectedLeadTime || selectedLeadTime === '') {
                $(this).show(); // Show the row if the lead time matches or no filter is selected
                } else {
                $(this).hide(); // Hide the row if the lead time doesn't match
                }
            });
        });

    });
});













