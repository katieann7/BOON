$(() => {
    $(document).ready(function (e) {
        $('#submit').click((i) => {
            i.preventDefault();
            var username = $('#username').val();
            var password = $('#password').val();

            axios({
                method: "post",
                headers: {
                    'Accept': "application/vnd.api+json",
                    'Content-Type': "application/vnd.api+json"
                },
                url: "http://127.0.0.1:8000/api/auth",
                data: {
                    "username": username,
                    "password": password
                }
            })
            .then((response) => {
                localStorage.setItem("token", response.data.data[0]);
                localStorage.setItem("costCenterCode", response.data.data[1].costCenter.code);
                Swal.fire({
                    icon: response.data.data.status,
                    title: 'Successfully logged in!',
                    text: response.data.message,
                  }).then(() => {
                    location.replace('/order');
                  });
            })
            .catch((error) => {
                console.log(error.response.data)
                Swal.fire({
                    icon: error.response.data.status,
                    title: 'Unauthorized!',
                    text: error.response.data.message.response,
                    footer: '<a href="http://127.0.0.1:8000">Contact Admin for further information.</a>'
                  }).then(() => {
                    location.reload();
                  });
            });
        });
    });
});
