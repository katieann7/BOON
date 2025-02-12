$(function() {
    $('#materialGroupMenu').change(() => {
        $('#leadTimesMenu').val('Select a Lead Time');
        Swal.fire({
            title: 'Loading materials matrix...',
            html: 'I will close in <b></b> seconds.',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
            Swal.showLoading()
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval = setInterval(() => {
            b.textContent = parseInt(Swal.getTimerLeft() / 1000)
            }, 100)
        },
        willClose: () => {
            clearInterval(timerInterval)
        }
        })

        var materialGroupCode = $('#materialGroupMenu').find(':selected').val();
        var orderingGroupCode = $('#orderingGroupMenu').find(':selected').val();
        var storeCode = $('#customerMenu').find(':selected').val();

        axios({
            method: "get",
            headers: {
                'Accept': "application/vnd.api+json",
                'Content-Type': "application/vnd.api+json",
                'Authorization': "Bearer "+ token

            },
            url: 'http://127.0.0.1:8000/api/v1/materials/materials-table/' + storeCode + '/' + orderingGroupCode + '/' + materialGroupCode
        })
        .then((response) => {
            var materialsTable = $("#materials-table");
            var header = $("#material-header");
            var body = $("#material-body");
            var tableHeader = "<th> Material Code </th> <th> Material Description </th> <th> Selling Unit </th> <th> Lead Time </th> <th> &nbsp;</th>";
            var tableBody = "";
            var n = 0;
            var materials = response.data.data;

            materials[0].schedule.forEach(function (day) {
                tableHeader += "<th>Day "+ day.day_number +"</th>";
            });
            header.html(tableHeader);

            $("tbody[name=materials]").each(function() {
                $(this).remove();
            });

            materials.forEach(function (material) {
                var dayName = "";
                var date = "";
                var deadline = "";
                var forecast = "";
                var quantity = "";
                var sourceCode = "";    
                if (typeof(material.plant) !== 'undefined') {
                    sourceCode = material.plant.code;
                } else {
                    sourceCode = material.vendor.code;
                }
                tableBody += '<tbody name=materials><tr><td rowspan="6" style="text-align: center;" id="material_code" material-code="' + material.code + '"> '+ material.code +'\
                            <input type="hidden" name="plant'+ material.code +'" value="'+ sourceCode +'">&nbsp; </td> \
                            <td rowspan="6" material-description="' + material.description+'"> '+ material.description +' </td> \
                            <td rowspan="6"> <input type="text" name="selling_unit'+ material.code +'" \
                                    value="'+ material.unit.code +'" readonly="readonly" size="5" \
                            </td> \
                            <td rowspan="6"> <input type="text" name="lead_time" \
                                    value="'+ material.conversionLeadTime +'" readonly="readonly" size="5" class="mirage"> \
                            </td> <tr><td>Day</td>';

                for (i=0; i< material.schedule.length; i++) {
                    dayName += '<td>'+ material.schedule[i].day_name+'</td>'
                }

                tableBody += dayName + '</tr> <tr> <td>Date</td>';

                for (i=0; i< material.schedule.length; i++) {
                    date += '<td> <input type="text" value="' + material.schedule[i].date + '"name="'+ material.code + '_date' + '" readonly="readonly" size="10" class="'+ material.code + '_date' +'"></td>'
                }

                tableBody += date + '</tr> <tr> <td>Deadline</td>';

                for (i=0; i< material.schedule.length; i++) {
                    deadline += '<td> <input type="text" value="' + material.schedule[i].deadline + '"name="'+ material.code + '_deadline' + '" readonly="readonly" size="10" class="'+ material.code + '_deadline' +'"></td>'
                }

                tableBody += deadline + '</tr> <tr> <td>Forecast</td>';

                for (i=0; i< material.schedule.length; i++) {
                    forecast += '<td><input type="text" value="" name="'+ material.code + '_forecast' + '" size="12" maxlength="10" style="border: 0; background: transparent;" readonly="readonly" class="qty_forecasted"></td>'
                }

                tableBody += forecast + '</tr> <tr> <td>Qty</td>';

                for (i=0; i< material.schedule.length; i++) {
                    let qty = "";
                    if (material.schedule[i].quantity == null) {
                        qty = "";
                    } else if (material.schedule[i].quantity != null) {
                        qty = material.schedule[i].quantity;
                    }

                    var currentDate = new Date();
                    let deadlineDate = new Date(material.schedule[i].deadline);
                    if (deadlineDate >= currentDate) {
                        quantity += '<td><input type="text" value="'+ qty +'" name="['+ material.code + ']['+ material.schedule[i].deadline +']_qty' +'" size="12" maxlength="10"></td>';
                    } else {
                        quantity += '<td><input type="text" value="'+ qty +'" name="['+ material.code + ']['+ material.schedule[i].deadline +']_qty' +'" size="12" maxlength="10" disabled></td>';
                    }
                }

                tableBody += quantity + '</tr></tr><tbody>';

            });
            body.hide();
            materialsTable.append(tableBody);
        })
        .catch((error) => {
            console.log(error);
        });
    });


})
