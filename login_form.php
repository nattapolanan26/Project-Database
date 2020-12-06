<!DOCTYPE html>
<html>

<head>
    <?php include('h.php'); ?>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
    <!-- JQuery -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <style>
        body {
            font-family: "Prompt", sans-serif;
            background-color: white;
        }

        .main-head {
            height: 100px;
            background: #FFF;

        }

        .sidenav {
            height: 100%;
            background-color: #000;
            overflow-x: hidden;
            padding-top: 60px;

        }


        .main {
            padding: 0px;
        }

        @media screen and (max-height: 450px) {
            .sidenav {
                padding-top: 15px;
            }
        }

        @media screen and (max-width: 450px) {
            .login-form {
                margin-top: 10%;
            }

            .register-form {
                margin-top: 10%;
            }
        }

        @media screen and (min-width: 768px) {
            .main {
                margin-left: 55%;
            }

            .sidenav {
                width: 38%;
                position: fixed;
                z-index: 1;
                top: 0;
                left: 0;

            }

            .login-form {
                margin-top: 40%;
            }

            .register-form {
                margin-top: 20%;
            }
        }

        /* เนื้อหาฝั่งหัวข้อ */
        .login-main-text {
            margin-top: 20%;
            padding: 50px;
            color: #fff;
        }

        .login-main-text h2 {
            font-weight: 400;
        }
    </style>
    <!------ Include the above in your HEAD tag ---------->
</head>

<body>
    <form action="login_check.php" name="loginform" id="loginform" method="post">
        <div class="sidenav">
            <div class="login-main-text">
                <h3>ร้านไทยเจริญก่อสร้าง</h3>
                <p>Welcome to the project Website Computer engineering.</p>
                <hr class="my-3" style="background-color:white;">
                <p class="mt-3 text-muted">ปรึกษาโดย อ.ศิลปกร ปิยะปัญญาพงษ์</p>
            </div>
        </div>
        <div class="main">
            <div class="col-md-8 col-sm-12">
                <div class="login-form">
                    <form>
                        <!-- Material form login -->
                        <div class="card">

                            <h5 class="card-header info-color white-text text-center py-4">
                                <strong>Sign in</strong>
                            </h5>

                            <!--Card content-->
                            <div class="card-body px-lg-5 pt-0">

                                <!-- Form -->
                                <form class="text-center" style="color: #757575;" action="#!">

                                    <!-- Email -->
                                    <div class="md-form">
                                        <input type="text" name="username" id="username" class="form-control">
                                        <label for="materialLoginFormEmail">Username</label>
                                    </div>

                                    <!-- Password -->
                                    <div class="md-form">
                                        <input type="password" name="password" id="password" class="form-control">
                                        <label for="materialLoginFormPassword">Password</label>
                                    </div>

                                    <div class="d-flex justify-content-around">
                                        <div>
                                            <!-- Remember me -->
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="materialLoginFormRemember">
                                                <label class="form-check-label" for="materialLoginFormRemember">Remember me</label>
                                            </div>
                                        </div>
                                        <div>
                                            <!-- Forgot password -->
                                            <a href="">Forgot password?</a>
                                        </div>
                                    </div>

                                    <!-- Sign in button -->
                                    <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit" name="loginform" id="loginform">เข้าสู่ระบบ</button>

                                    <!-- Register -->
                                    <p>Not a member?
                                        <a href="">Register</a>
                                    </p>

                                    <!-- Social login -->
                                    <p>or sign in with:</p>
                                    <a type="button" class="btn-floating btn-fb btn-sm">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a type="button" class="btn-floating btn-tw btn-sm">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a type="button" class="btn-floating btn-li btn-sm">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                    <a type="button" class="btn-floating btn-git btn-sm">
                                        <i class="fab fa-github"></i>
                                    </a>

                                </form>
                                <!-- Form -->

                            </div>

                        </div>
                        <!-- Material form login -->
                    </form>
                </div>
            </div>
        </div>
</body>

</html>