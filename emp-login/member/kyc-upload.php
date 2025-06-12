<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
require_once(__DIR__ . '/../../config/spaces_config.php');
$space = new SpacesConnect(SPACE_ACCESS_KEY, SPACE_SECRET_KEY, SPACE_SPACENAME, SPACE_REGION);

$db = new Database();

$con = $db->connect();
$s1 = "SELECT *, count(*) from kyc_details m1 where member_id='" . $user_id . "'";
$q1 = mysqli_query($con, $s1);
$db->dbDisconnet($con);
if (mysqli_num_rows($q1) == 1) {
    $r1 = mysqli_fetch_assoc($q1);
}

$pan_img = ($r1['pan_img']) ? ($r1['pan_img']) : ("blank_pan.png");
$aadhar_front_img = ($r1['aadhar_front_img']) ? ($r1['aadhar_front_img']) : ("blank_aadhar.png");
$aadhar_back_img = ($r1['aadhar_back_img']) ? ($r1['aadhar_back_img']) : ("blank_aadhar2.png");
$bank_img = ($r1['bank_img']) ? ($r1['bank_img']) : ("blank_bank.png");

$pan_img = get_space_url($pan_img, "pan_img/");
$aadhar_front_img = get_space_url($aadhar_front_img, "aadhar_img/");
$aadhar_back_img = get_space_url($aadhar_back_img, "aadhar_img/");
$bank_img = get_space_url($bank_img, "bank_img/");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php include('include/title.php'); ?></title>

    <?php include('include/header-common-file.php'); ?>
</head>

<body>


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
                            <i class="fa fa-id-card" aria-hidden="true"></i>
                        </div>
                        <div class="caption">
                            <h2>KYC Document Upload</h2>
                            <p>Upload your PAN/Aadhar/Bank photo for KYC.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <?php include('include/alert.php'); ?>
                        <div class="row">
                            <div class="col-lg-12 text-center" style="border: 1px solid red;">
                                <p style="padding-top: 15px;">Note : Screenshots are not not allowed. Please upload <span style="color: red; font-weight: bold; background-color: yellow;">SCAN COPY</span>
                                    of your documents.</p>
                            </div>
                            <div class="col-lg-4" style="padding-top: 20px;">
                                <picture>
                                    <img src="<?= $pan_img; ?>" class="img-responsive" style="padding-bottom: 10px; width: 100%;" />
                                </picture>
                                <br>
                                <?php
                                if ($r1['pan_status'] == 1) {
                                    echo "Congratulation your PAN is verified.";
                                } else {
                                    if ($r1['pan_status'] == 2) {
                                        //echo "Rejected. Please Update again.(Reason : ".$r1['pan_reject__reason'].")";
                                        echo "Rejected. Please Update again.";
                                    }
                                ?>
                                    <form id="frmEdit1" action="pan-uploadc.php" method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="form-group col-md-12 col-sm-12">
                                                <label>PAN Photo*</label>
                                                <input type="file" name="pan_img" class="form-control" data-rule-required="true" />
                                            </div>
                                            <div class="col-md-12">
                                                <input type="submit" name="submit_pan" value="Upload" class="btn btn-primary">
                                            </div>
                                        </div>
                                    </form>
                                <?php
                                }
                                ?>
                            </div>

                            <!-- aadhar front -->
                            <div class="col-lg-4" style="padding-top: 20px;">
                                <img src="<?= $aadhar_front_img ?>" class="img-responsive" style="padding-bottom: 10px; width: 100%" /><br>
                                <?php
                                if ($r1['aadhar_front_status'] == 1) {
                                    echo "Congratulation your Aadhar Front is verified.";
                                } else {
                                    if ($r1['aadhar_front_status'] == 2)
                                        echo "Rejected. Please Update again.";
                                ?>
                                    <form id="frmEdit2" action="aadhar-front-uploadc.php" method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="form-group col-md-12 col-sm-12">
                                                <label>Aadhar Front Photo*</label>
                                                <input type="file" name="aadhar_front_img" class="form-control" data-rule-required="true" />
                                            </div>
                                            <div class="col-md-12">
                                                <input type="submit" name="submit_aadhar_front" value="Upload" class="btn btn-primary">
                                            </div>
                                        </div>
                                    </form>
                                <?php
                                }
                                ?>
                            </div>
                            <!-- aadhar front -->

                            <!-- aadhar back -->
                            <div class="col-lg-4" style="padding-top: 20px;">
                                <img src="<?= $aadhar_back_img ?>" class="img-responsive" style="padding-bottom: 10px; width: 100%" /><br>
                                <?php
                                if ($r1['aadhar_back_status'] == 1) {
                                    echo "Congratulation your Aadhar Back is verified.";
                                } else {
                                    if ($r1['aadhar_back_status'] == 2)
                                        echo "Rejected. Please Update again.";
                                ?>
                                    <form id="frmEdit2" action="aadhar-back-uploadc.php" method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="form-group col-md-12 col-sm-12">
                                                <label>Aadhar Back Photo*</label>
                                                <input type="file" name="aadhar_back_img" class="form-control" data-rule-required="true" />
                                            </div>
                                            <div class="col-md-12">
                                                <input type="submit" name="submit_aadhar_back" value="Upload" class="btn btn-primary">
                                            </div>
                                        </div>
                                    </form>
                                <?php
                                }
                                ?>
                            </div>
                            <!-- aadhar back -->

                            <!-- Bank -->
                            <div class="col-lg-4" style="padding-top: 20px;">
                                <img src="<?= $bank_img ?>" class="img-responsive" style="padding-bottom: 10px; width: 100%" /><br>
                                <?php
                                if ($r1['bank_status'] == 1) {
                                    echo "Congratulation your bank is verified.";
                                } else {
                                    if ($r1['bank_status'] == 2)
                                        echo "Rejected. Please Update again.";
                                ?>
                                    <form id="frmEdit1" action="bank-uploadc.php" method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="form-group col-md-12 col-sm-12">
                                                <label>Bank Photo*</label>
                                                <input type="file" name="bank_img" class="form-control" data-rule-required="true" />
                                            </div>
                                            <div class="col-md-12">
                                                <input type="submit" name="submit_bank" value="Upload" class="btn btn-primary">
                                            </div>
                                        </div>
                                    </form>
                                <?php
                                }
                                ?>
                            </div>
                            <!-- Bank -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end-body-content-->

    <!--start-mm-footer-->
    <?php //include('include/mm-footer.php'); 
    ?>
    <div class="mm-footer">
        <p>© 2022 <?php include('include/title.php'); ?> | All Rights Reserved.</p>
    </div>

    <script type="text/javascript" src="../web-assets/js/pushbar.js"></script>
    <script type="text/javascript">
        var pushbar = new Pushbar({
            blur: true,
            overlay: true,
        });
    </script>

    <script>
        $(".mm-sidebar-dropdown > a").click(function() {
            $(".mm-sidebar-submenu").slideUp(200);
            if (
                $(this)
                .parent()
                .hasClass("active")
            ) {
                $(".mm-sidebar-dropdown").removeClass("active");
                $(this)
                    .parent()
                    .removeClass("active");
            } else {
                $(".mm-sidebar-dropdown").removeClass("active");
                $(this)
                    .next(".mm-sidebar-submenu")
                    .slideDown(200);
                $(this)
                    .parent()
                    .addClass("active");
            }
        });

        //$("#close-sidebar").click(function() {
        //  $(".page-wrapper").removeClass("toggled");
        //});
        //$("#show-sidebar").click(function() {
        //  $(".page-wrapper").addClass("toggled");
        //});
    </script>
    <script>
        $(window).on('load', function() {
            setTimeout(function() { // allowing 3 secs to fade out loader
                $('.page-loader').fadeOut('slow');
            }, 100);
        });
    </script>

    <!-- <script src="../web-assets/js/jquery.validate.min.js"></script>
<script>
    $("form").validate({
        submitHandler: function () {
            $("#submit").prop("disabled", !0), $("#submit").val('Please Wait...'), form.submit();
        },
    });
</script> -->
    <!--end-mm-footer-->

    <!-- particles -->
    <script src="../web-assets/js/particles.min.js"></script>
    <script src="../web-assets/js/app.js"></script>
</body>

</html>