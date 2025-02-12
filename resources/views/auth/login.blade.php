<!DOCTYPE html>
<html>

<head>
    <title>Back Office</title>
    <!-- CSS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/login.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/login.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/sweetalert.min.css') }}" />
    <script src="{{ url('assets/js/axios.min.js') }}"></script>
    <script src="{{ url('assets/js/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ url('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ url('assets/js/sweetalert.min.js') }}"></script>
    <script src="{{ url('assets/js/common.js') }}"></script>

</head>

<body class="body">
    <script>
        if (localStorage.getItem("token")) {
            localStorage.removeItem("token");
        }
    </script>
    <div class="container bootstrap snippets bootdey">
        <div class="row">

            <div class="main" style="border-style: groove;">
                <div id="login-box" class="col-md-12">
                    <div class="login-or">
                        <hr class="hr-or">
                        <span class="span-or">LOGIN</span>
                    </div>

                    <form role="form">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" minlength="4" class="form-control" id="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" minlength="8" class="form-control" id="password" required>
                        </div>

                        <button type="submit" class="btn btn btn-primary" id="submit">Log In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ url('assets/js/auth/login.js') }}"></script>
</body>

</html>
