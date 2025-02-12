$(function () {
    $("#saveOrder").on("click", function () {

        var materials = [];
        var data = [];
        var err = [];
        var orderingGroupCode = $('#orderingGroupMenu').find(':selected').val();
        var storeCode = $('#customerMenu').find(':selected').val();
        $("td[material-code]").each(function(){
            var materialCode = $(this).attr("material-code");
            var plantCode = $("input[name=plant"+ materialCode +"]").val();
            var unitCode = $("input[name=selling_unit"+ materialCode +"]").val();
            materials.push({
                "materialCode" : materialCode,
                "plantCode": plantCode,
                "unitCode": unitCode,
                "materialDescription": null,
                "days": []
            });
        });

        $("td[material-description]").each(function(i){
            var materialDescription = $(this).attr("material-description");
            materials[i].materialDescription = materialDescription;
        });

        materials.forEach(function(data, i) {
            $("input[class="+ data.materialCode +"_deadline]").each(function() {
                var deadlineDate = $(this).val();
                var quantityOrdered = $("input[name='["+ data.materialCode +"]["+ deadlineDate +"]_qty']").val();
                materials[i]['days'].push({
                    "deliveryDate": null,
                    "quantityOrdered": quantityOrdered,
                });
            });
        });

        materials.forEach(function(data, i) {
            $("input[class="+ data.materialCode +"_date]").each(function(j) {
                var deliveryDate = $(this).val();
                materials[i]['days'][j].deliveryDate = deliveryDate;
            });
        });

        if (materials.length !== 0) {
            materials.forEach(function(material, i) {
                material.days.forEach(function(day, i) {
                    let quantity = day.quantityOrdered;
                    switch (quantity) {
                        case '':
                            break;
                        default:
                            if (containsOnlyNumbers(quantity)) {
                                data.push({
                                    storeCode: storeCode,
                                    deliveryDate: day.deliveryDate,
                                    orderingGroupCode: orderingGroupCode,
                                    statusCode: "OPEN",
                                    plantCode: material.plantCode,
                                    materialCode: material.materialCode,
                                    unitCode: material.unitCode,
                                    quantityOrdered: day.quantityOrdered
                                });
                            } else {
                                err.push({
                                    deliveryDate: day.deliveryDate,
                                    materialDescription: material.materialDescription,
                                    quantityOrdered: day.quantityOrdered
                                });
                            }
                            break;
                    }
                })
            });

            if (err.length === 0) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                      confirmButton: 'btn btn-success',
                      cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                  })

                  swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "Your changes will be saved.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, save i!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true

                  }).then((result) => {
                    if (result.isConfirmed) {

                        data.forEach((d) => {
                            axios({
                                method: "post",
                                headers: {
                                    'Accept': "application/vnd.api+json",
                                    'Content-Type': "application/vnd.api+json",
                                    'Authorization': "Bearer "+ token
                                },
                                data: d,
                                url: "http://127.0.0.1:8000/api/v1/deliveries/delivery-orders/"
                            })
                            .catch((error) => {
                                Swal.fire(
                                    'Error.',
                                    `${error}`,
                                    'warning'
                                  )
                            });
                        });

                        let timerInterval
                        Swal.fire({
                          title: 'Saving changes...',
                          html: 'I will close in <b></b> seconds.',
                          timer: data.length * 500,
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
                        }).then(() => {
                            location.reload();
                        })
                    } else if (
                      result.dismiss === Swal.DismissReason.cancel
                    ) {
                      swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your changes are not saved.',
                        'info'
                      )
                    }
                  })
            } else {
                var footer = "";
                err.forEach((e) => {
                    footer += `<br>Material Description: ${e.materialDescription} <br> Quantity: ${e.quantityOrdered} <br>`
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Invalid entry has been entered!',
                    footer: `<p style="text-align: center;">Please check: </br> ${footer} </p>`
                  })
            }
        } else {
            Swal.fire(
                'Invalid selected fields.',
                'Choose a Store, Ordering Group, or Material first!',
                'info'
              )
        }
    })

    $('#reset').click(() => {
        location.reload();
    })

    function containsOnlyNumbers(str) {
        return /^\d+$/.test(str);
    }
})





