<?php
session_start();
include('../class/DbClass.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo PROJECT_NAME ?></title>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../login/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../login/assets/css/font-awesome.min.css">

    <script src="../login/assets/js/jquery.min.js"></script>
    <script src="../login/assets/js/popper.min.js"></script>
    <script src="../login/assets/js/bootstrap.min.js"></script>

    <style type="text/css">
        body {
            background: url(../login/2.jpg) no-repeat top center fixed;
            background-size: cover;
        }

        .login-box {
            position: relative;
            width: 100%;
            height: 100%;
            margin-top: 40%;
            /*background:#7f88a3;*/
        }

        /*.login-box:before{position:absolute; left:0; top:-3px; width:50%; height:5px; content:""; background:#7f88a3;}
        .login-box:after{position:absolute; right:0; top:-3px; width:50%; height:5px; content:""; background:#ccd6ed;}*/

        .login-box h2 {
            font-family: 'Open Sans', sans-serif;
            font-size: 24px;
            line-height: 30px;
            color: #FFF;
            margin-bottom: 30px;
            text-align: center;
        }

        .login-box h4 {
            font-family: 'Open Sans', sans-serif;
            font-size: 14px;
            line-height: 20px;
            color: #75c0c7;
            margin: 10px 0;
            text-align: center;
        }

        .login-box h4 a {
            color: #1e4090;
        }

        .login-box h5 {
            font-family: 'Open Sans', sans-serif;
            font-size: 14px;
            line-height: 20px;
            color: #b6070a;
            margin-bottom: 10px;
            text-align: center;
        }

        .login-box h5 a {
            color: #1e4090;
        }

        #Div1 {
            background: #ffffff91;
            width: 100%;
            padding: 20px;
            z-index: 1;
            position: relative;
        }

        #Div2 {
            background: #ffffff91;
            width: 100%;
            padding: 20px;
            z-index: 5;
            position: relative;
            display: none;
        }

        #Div3 {
            background: #110426;
            width: 100%;
            padding: 20px;
            z-index: 15;
            position: relative;
            display: none;
        }

        .formsec {
            padding-top: 30px;
        }

        span.green {
            color: #00f5e1;
        }

        span.white {
            color: #FFF;
        }

        .form-control {
            background: #858181;
            border: none !important;
        }

        .form-control input {
            width: 90%;
            background: none;
            border: none !important;
            color: #FFF;
        }

        ::placeholder {
            color: #ffffffa6;
            opacity: 1;
        }

        .fa {
            color: #FFF;
        }

        .btn {
            width: 100%;
            border-radius: 0 !important;
            background: #24324d;
            border-radius: 5px !important;
            border: none;
            color: #fff;
        }

        .btn-signup {
            width: 100%;
            border-radius: 0 !important;
            background: rgb(99, 100, 102);
            border-radius: 5px !important;
            border: none;
            color: #fff;
        }

        .btn:hover {
            background: #0e3a8f;
        }

        .logo {
            position: absolute;
            width: 80px;
            height: 80px;
            content: "";
            background: #354363;
            border: solid 4px #7f88a3;
            top: 0;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            border-radius: 50%;
        }

        .logo img {
            margin: 15px auto;
            display: block;
        }

        .logo2 {}

        .logo2 img {
            margin: 10px auto;
            display: block;
        }

        @media (max-width: 768px) {
            .login-box {
                margin-top: 15%;
            }
        }

        @media (max-width: 500px) {
            .login-box {
                margin-top: 20%;
            }
        }
    </style>

</head>

<body>

    <div class="container">
        <div class="col-md-4 offset-md-0 col-sm-8">

            <div class="login-box">

                <!----------------------log in form---------------------->

                <div id="Div1">

                    <?php include('include/alert.php'); ?>

                    <div class="logo2"><img src="<?php echo PROJECT_LOGO ?>?v=12" class="img-fluid" style="width:160px;"></div>

                    <div class="formsec">
                        <form action="loginc" id="frm_login" novalidate="novalidate" enctype="multipart/form-data" method="post" accept-charset="utf-8">

                            <div class="form-group">
                                <div class="form-control">
                                    <span class="fa fa-user"></span>
                                    <input type="text" name="un" placeholder="Member Code" data-rule-required="true" required id="uname">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-control">
                                    <span class="fa fa-key"></span>
                                    <input type="password" name="pw" placeholder="Enter Password" data-rule-required="true" required id="passcode">
                                </div>
                            </div>

                            <!--<div class="form-group form-check">
                          <input class="form-check-input" type="checkbox"> <span class="white">Remember me</span>
                        </div>-->

                            <div class="form-group">
                                <button type="submit" name="submit" id="submit" value="Login" class="btn btn-primary">Login</button>
                            </div>
                            <h4 style="color: #fff;">OR</a></h4>
                            <a href="member-signup" class="btn btn-signup">Register as new Member</a>
                        </form>

                        <h4><a href="#" id="Button1" type="button" value="Click" onclick="switchVisible();" style="color: #fff;">Forgot Your Password ?</a></h4>
                        </form>
                    </div>

                </div>

                <!----------------------forgot your password---------------------->

                <div id="Div2">

                    <?php include('include/alert.php'); ?>

                    <div class="logo2"><img src="<?php echo PROJECT_LOGO ?>" class="img-fluid" style="width:160px;"></div>

                    <div class="formsec">
                        <h5>Forgot Your Password</h5>

                        <form action="forgot-passwordc" id="frm_login" novalidate="novalidate" enctype="multipart/form-data" method="post" accept-charset="utf-8">

                            <div class="form-group">
                                <div class="form-control">
                                    <span class="fa fa-user"></span>
                                    <input type="text" placeholder="Username" name="un" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary">Submit</button>
                            </div>
                            <h4><a href="#" id="Button4" type="button" value="Click" onclick="switchVisible4();">Back to Login</a></h4>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script type="text/javascript">
        function switchVisible() {
            if (document.getElementById('Div1')) {

                if (document.getElementById('Div1').style.display == 'none') {
                    document.getElementById('Div1').style.display = 'block';
                    document.getElementById('Div2').style.display = 'none';
                } else {
                    document.getElementById('Div1').style.display = 'none';
                    document.getElementById('Div2').style.display = 'block';

                }
            }

        }

        function switchVisible2() {
            if (document.getElementById('Div1')) {

                if (document.getElementById('Div1').style.display == 'none') {
                    document.getElementById('Div1').style.display = 'block';
                    document.getElementById('Div3').style.display = 'none';
                } else {
                    document.getElementById('Div1').style.display = 'none';
                    document.getElementById('Div3').style.display = 'block';
                }
            }

        }

        function switchVisible3() {
            if (document.getElementById('Div3')) {

                if (document.getElementById('Div3').style.display == 'none') {
                    document.getElementById('Div3').style.display = 'block';
                    document.getElementById('Div1').style.display = 'none';
                } else {
                    document.getElementById('Div3').style.display = 'none';
                    document.getElementById('Div1').style.display = 'block';
                }
            }

        }

        function switchVisible4() {
            if (document.getElementById('Div2')) {

                if (document.getElementById('Div2').style.display == 'none') {
                    document.getElementById('Div2').style.display = 'block';
                    document.getElementById('Div1').style.display = 'none';
                } else {
                    document.getElementById('Div2').style.display = 'none';
                    document.getElementById('Div1').style.display = 'block';
                }
            }

        }
    </script>

</body>

</html>