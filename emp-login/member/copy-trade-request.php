<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php include('include/title.php'); ?></title>
    <link rel="shortcut icon" href="images/fab_icon.gif" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php include('include/header-common-file.php'); ?>
    <style>
        label>input {
            visibility: hidden;
            position: absolute;
        }

        label>input+img {
            cursor: pointer;
            border: 2px solid transparent;
        }

        label>input:checked+img {
            border: 2px solid #ff3f04;
        }
    </style>
</head>

<body>
    <!--start-mm-menu-direction-->
    <?php include('include/menu-direction.php'); ?>
    <!--end-mm-menu-direction-->

    <!--start-body-content-->
    <div class="body-content">

        <!--start-mm-top-header-->
        <?php include('include/mm-top-header.php'); ?>
        <!--end-mm-top-header-->

        <div class="container">
            <div class="col-lg-12">
                <div class="row">
                    <div class="dashboard-title-2">
                        <div class="caption-2">
                            <h2>Copy Trade Request</h2>
                            <p>Submit your copy trade request</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title-2 mb-4">
                            <a href="view-copy-trade-request" class="btn btn-sm btn-warning">My Requests</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <hr />
                        <?php include('include/alert.php'); ?>

                        <form id="frmAdd" action="copy-trade-requestc" method="post" enctype='multipart/form-data'>

                            <div class="row">
                                <div class="form-group col-md-6 col-sm-6">
                                    <label>Mobile No.*</label>
                                    <input type="text" name="mobile" id="mobile" class="form-control" data-rule-required="true" placeholder="Enter your mobile number" required />
                                </div>
                                <div class="form-group col-md-6 col-sm-6">
                                    <label>Email*</label>
                                    <input type="email" name="email" id="email" class="form-control" data-rule-required="true" placeholder="Enter your email" required />
                                </div>
                                <div class="form-group col-md-6 col-sm-6">
                                    <label>Password*</label>
                                    <input type="password" name="password" id="password" class="form-control" data-rule-required="true" placeholder="Enter your password" required />
                                </div>
                                <div class="form-group col-md-6 col-sm-6">
                                    <label>Investment Amount*</label>
                                    <input type="text" name="investment_amount" id="investment_amount" class="form-control" data-rule-digits="true" data-rule-required="true" placeholder="Enter the investment amount" required />
                                </div>
                                <div class="form-group col-md-6 col-sm-6">
                                    <label>Broker Name*</label>
                                    <input type="text" name="broker_name" id="broker_name" class="form-control" data-rule-required="true" placeholder="Enter the broker name" required />
                                </div>
                                <div class="form-group col-md-6 col-sm-6">
                                    <label>MT5 Id (optional Rox)</label>
                                    <input type="text" name="mt5_id" id="mt5_id" class="form-control" placeholder="Enter the MT5 Id (if applicable)" />
                                </div>
                                <div class="form-group col-md-6 col-sm-6">
                                    <label>MT5 Password (optional Rox)</label>
                                    <input type="password" name="mt5_password" id="mt5_password" class="form-control" placeholder="Enter the MT5 password (if applicable)" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <input type="submit" name="submit" id="submit" class="btn btn-danger" value="Submit">
                                </div>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!--end-body-content-->

    <!--start-mm-footer-->
    <?php include('include/mm-footer.php'); ?>
    <!--end-mm-footer-->

    <!-- particles -->
    <script src="../web-assets/js/particles.min.js"></script>
    <script src="../web-assets/js/app.js"></script>
</body>

</html>