<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$con = $db->connect();
$q1 = mysqli_query($con, "select crypto_address_bep20 from member_details where member_id='" . $user_id . "'");
$db->dbDisconnet($con);
if (mysqli_num_rows($q1) == 1) {
    $r1 = mysqli_fetch_assoc($q1);
    $crypto_address_bep20 = $r1['crypto_address_bep20'];
    $editData = true;
} else {
    $editData = false;
}
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
                            <i class="fa fa-btc" aria-hidden="true"></i>
                        </div>
                        <div class="caption">
                            <h2>Crypto Address</h2>
                            <p>Add Crypto Address</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-panel">
                <div class="row">


                    <div class="col-lg-12">

                        <?php include('include/alert.php'); ?>

                        <?php
                        if ($editData == true) {
                        ?>
                            <form id="frmEdit" action="my-crypto-addressc" method="post">
                                <div class="row">
                                    <?php
                                    if (empty($_SESSION['crypto_address_bep20_update'])) {
                                    ?>
                                        <div class="form-group col-md-6 col-sm-6">
                                            <label>Your Address*</label>
                                            <input type="text" id="crypto_address_bep20" name="crypto_address_bep20" value="<?php echo $r1['crypto_address_bep20'] ?>" class="form-control" data-rule-required="true" />
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="form-group col-md-6 col-sm-6">
                                            <label>Your Address*</label>
                                            <input type="text" name="crypto_address_bep20" value="<?php echo $_SESSION['crypto_address_bep20_update']['crypto_address_bep20'] ?>" readonly class="form-control" style="background-color: transparent; border: 1px solid #333;" />
                                        </div>

                                        <div class="form-group col-md-6 col-sm-6">
                                            <label>OTP*</label>
                                            <input type="text" name="otp" class="form-control" data-rule-required="true" />
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-12">
                                        <button type="submit" name="submit" id="submit" value="Update" class="btn btn-danger col-md-2 col-sm-3">Update</button>
                                    </div>
                                </div>
                            </form>
                        <?php
                        }
                        ?>
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