<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'show_growth';

$user_id = $_SESSION['user_data']['user_id'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php include('include/title.php'); ?></title>

    <?php include('include/header-common-file.php'); ?>
</head>


<body>
    <!-- particles -->
    <div id="particles-js"></div>

    <!--start-page-loader-->
    <div class="page-loader">
        <div class="loader"></div>
    </div>
    <!--end-page-loader-->

    <!--start-mm-menu-direction-->
    <?php include('include/menu-direction.php'); ?>
    <!--end-mm-menu-direction-->

    <!--start-mm-top-header-->
    <?php include('include/mm-top-header.php'); ?>
    <!--end-mm-top-header-->

    <!--start-body-content-->
    <div class="body-content">
        <div class="container">
            <div class="col-lg-12">
                <div class="row">
                    <div class="dashboard-title">
                        <div class="icon">
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </div>
                        <div class="caption">
                            <h2>Business Growth</h2>
                            <p>Show Business Growth</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="table-panel">
                <div class="row">

                    <div class="col-lg-12">
                        <div class="title-2 mb-4">
                            Report
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-dark">
                                <thead>
                                    <tr>
                                        <th>Max Business</th>
                                        <th>Max Side Growth</th>
                                        <th>Min Business</th>
                                        <th>Min Side Growth</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $con = $db->connect();
                                    $sql_show_growth = "CALL `Show_Growth`($user_id);";
                                    $res_show_growth = $con->query($sql_show_growth);
                                    $con->close();
                                    if ($res_show_growth->num_rows) {
                                        while ($arr_show_growth = $res_show_growth->fetch_assoc()) {
                                            $var_max_business = $arr_show_growth['var_max_business'];
                                            $var_max_side_growth = $arr_show_growth['var_max_side_growth'];
                                            $var_min_business = $arr_show_growth['var_min_business'];
                                            $var_min_side_growth = $arr_show_growth['var_min_side_growth'];
                                    ?>
                                            <tr>
                                                <td class="text-center"><?= $var_max_business; ?></td>
                                                <td class="text-center"><?= $var_max_side_growth; ?>%</td>
                                                <td class="text-center"><?= $var_min_business; ?></td>
                                                <td class="text-center"><?= $var_min_side_growth; ?>%</td>
                                            </tr>
                                    <?php
                                        }
                                    }/*  else {
                                        $var_max_business = '';
                                        $var_max_side_growth = '';
                                        $var_min_business = '';
                                        $var_min_side_growth = '';
                                    } */
                                    ?>


                                </tbody>
                            </table>
                        </div>
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