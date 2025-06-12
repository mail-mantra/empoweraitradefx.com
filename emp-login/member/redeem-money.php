<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$now = now();
$today = today();


/*if(!isset($_SERVER['HTTP_REFERER'])){
	$systemDenied=true;
	include('include/forced-logout.php');
}*/


$con = $db->connect();
$q_pm = mysqli_query($con, "SELECT status FROM `payment_settings` where id='1'");
$db->dbDisconnet($con);
$r_pm = mysqli_fetch_assoc($q_pm);
$status = $r_pm['status'];
if ($status == 0) {
    $_SESSION['e'] = "IMPS Service has been closed today";
    header("Location:dashboard");
    die;
}

$disabled = false;
$con = $db->connect();
$working_wallet = get_wallet_balance_of_member($con, $user_id, 'working_wallet_balance');
$roi_wallet = get_wallet_balance_of_member($con, $user_id, 'roi_wallet_balance');
$q = mysqli_query($con, "select bank_update,bnk_nm,acc_nm,acc_no,ifsc,pan_no from member_details where member_id='" . $user_id . "'");
$db->dbDisconnet($con);
$r = mysqli_fetch_assoc($q);
if ($r['bank_update'] == 1) {
    $disabled = true;
}

/////////

$withdraw_status = true;
// $con = $db->connect();
// $_q = mysqli_query($con, "SELECT * FROM `redeem_money` WHERE member_id='" . $user_id . "' and paid_status='0'");
// $db->dbDisconnet($con);
// if (mysqli_num_rows($_q) > 0) {
//     $withdraw_status = true;
// }


// working - 1,2,3 and 16,17,18 every month 
// roi - 3 to 7 every month

// if (date('d') == "1" || date('d') == "2" || date('d') == "3" || date('d') == "4" || date('d') == "16" || date('d') == "17" || date('d') == "18") {
//     $working = true;
// }

/* if (date('d') == "3" || date('d') == "7") {
    $roi = true;
} */
$working = true;
$roi = true;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php include('include/title.php'); ?></title>
    <link rel="shortcut icon" href="images/fab_icon.gif" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php include('include/header-common-file.php'); ?>
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
                            <h2>Withdraw Request</h2>
                            <p>Withdraw fund from your Account</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-panel">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="title-2 mb-4">
                            <?php if ($working_wallet <= 0) { ?>
                                <span class="btn btn-sm btn-danger">Working Balance : <?php echo CURRENCY_ICON . $working_wallet; ?></span>
                            <?php } else { ?>
                                <span class="btn btn-sm btn-success">Working Balance : <?php echo CURRENCY_ICON . $working_wallet; ?></span>
                            <?php } ?>

                            <?php if ($roi_wallet <= 0) { ?>
                                <span class="btn btn-sm btn-danger">RoI Balance : <?php echo CURRENCY_ICON . $roi_wallet; ?></span>
                            <?php } else { ?>
                                <span class="btn btn-sm btn-success">RoI Balance : <?php echo CURRENCY_ICON . $roi_wallet; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">

                        <?php include('include/alert.php'); ?>
                        <?php
                        if ($withdraw_status) {
                        ?>
                            <form id="frmAdd" action="redeem-moneyc" method="post">
                                <?php
                                if (empty($_SESSION['redeem_update'])) {
                                ?>
                                    <div class="row">
                                        <!-- <div class="form-group col-md-12 col-sm-12">
                                            Note : All withdrawals will be processed within 24 to 72 hrs.
                                        </div> -->
                                        <div class="form-group col-md-6 col-sm-6">
                                            <label>Wallet * </label>
                                            <select class="form-control" name="wallet_name" id="wallet" onchange="getAmount()" required>
                                                <option value="">---Select---</option>
                                                <?php if ($working) { ?>
                                                    <option value="working">Working Wallet</option>
                                                <?php } ?>
                                                <?php if ($roi) { ?>
                                                    <option value="roi">RoI Wallet</option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6 col-sm-6">
                                            <label>Amount*</label>
                                            <input type="text" name="amount" id="amount" class="form-control" data-rule-required="true" data-rule-number="true" onchange="getAmount()" />
                                            <div id="validAmount"></div>
                                        </div>

                                        <div class="form-group col-md-12 col-sm-12">
                                            <label>Select the Payment Method</label><br>
                                            <label>
                                                <input type="radio" name="pay_through" value="bank" checked required><img src="../web-assets/images/redeem/7.png" />
                                            </label>
                                        </div>



                                        <div class="form-group col-md-4 col-sm-4">
                                            <label>Bank Name*</label>
                                            <input type="text" name="bank_name" value="<?php echo $r['bnk_nm']; ?>" class="form-control" data-rule-required="true" <?= $disabled ? 'readonly' : '' ?> />
                                        </div>

                                        <div class="form-group col-md-4 col-sm-4">
                                            <label>Account Name*</label>
                                            <input type="text" name="acc_name" value="<?php echo $r['acc_nm']; ?>" class="form-control" data-rule-required="true" <?= $disabled ? 'readonly' : '' ?> />
                                        </div>

                                        <div class="form-group col-md-4 col-sm-4">
                                            <label>Account No.*</label>
                                            <input type="text" name="acc_no" value="<?php echo $r['acc_no']; ?>" class="form-control" data-rule-required="true" <?= $disabled ? 'readonly' : '' ?> />
                                        </div>

                                        <div class="form-group col-md-4 col-sm-4">
                                            <label>IFSC Code*</label>
                                            <input type="text" name="ifsc" value="<?php echo $r['ifsc']; ?>" class="form-control" data-rule-required="true" <?= $disabled ? 'readonly' : '' ?> />
                                        </div>

                                        <div class="form-group col-md-4 col-sm-4">
                                            <label>PAN No.</label>
                                            <input type="text" name="pan_no" value="<?php echo $r['pan_no']; ?>" class="form-control" <?= $disabled ? 'readonly' : '' ?> />
                                        </div>



                                        <div class="col-md-12">
                                            <button type="submit" name="submit" id="submit" value="Proceed" class="btn btn-danger col-md-2 col-sm-3">Proceed</button>
                                        </div>
                                    </div>

                                <?php } else { ?>

                                    <div class="row">
                                        <div class="form-group col-md-6 col-sm-6">
                                            <label>Wallet *</label>
                                            <select class="form-control" name="wallet_name" id="wallet" required>
                                                <option value="">---Select---</option>
                                                <option value="working" <?php if ($_SESSION['redeem_update']['wallet_name'] == 'working') { ?>selected <?php } ?>>Working Wallet</option>
                                            </select>
                                            <!--<p>Working wallet will be open on Monday and Tuesday</p>-->
                                        </div>

                                        <div class="form-group col-md-6 col-sm-6">
                                            <label>Amount*</label>
                                            <input type="number" name="amount" id="amount" class="form-control" value="<?php echo $_SESSION['redeem_update']['amount'] ?>" readonly placeholder="Enter Amount To Redeem" data-rule-digits="true" />
                                            <div id="validAmount"></div>
                                        </div>

                                        <div class="form-group col-md-12 col-sm-12">
                                            <label>Select the Payment Method</label><br>
                                            <?php if ($redeem_with == 'usd') { ?>
                                                <label>
                                                    <input type="radio" name="pay_through" id="pay_through" value="trx" required><img src="../assets/images/redeem/trx.png" />
                                                </label>
                                                <label>
                                                    <input type="radio" name="pay_through" id="pay_through" value="btc" required><img src="../assets/images/redeem/4.png" />
                                                </label>
                                                <label>
                                                    <input type="radio" name="pay_through" id="pay_through" value="eth" required><img src="../assets/images/redeem/6.png" />
                                                </label>
                                            <?php } else if ($redeem_with == 'inr') { ?>
                                                <label>
                                                    <input type="radio" name="pay_through" value="bank" checked required><img src="../assets/images/redeem/7.png" />
                                                </label>
                                            <?php } else if ($redeem_with == 'crypto') {
                                            ?>
                                                <label>
                                                    <input type="radio" name="pay_through" value="crypto" checked required><img src="../assets/images/redeem/crypto.png" />
                                                </label>
                                            <?php
                                            }
                                            ?>
                                        </div>

                                        <?php if ($redeem_with == 'usd' || $redeem_with == 'crypto') { ?>
                                            <div class="form-group col-md-6 col-sm-6">
                                                <label>Address*</label>
                                                <input type="text" value="<?php echo $_SESSION['redeem_update']['payment_address'] ?>" readonly name="payment_address" id="payment_address" class="form-control" placeholder="Enter Address" <?php if ($r['crypto_address'] != '') {
                                                                                                                                                                                                                                                    echo 'readonly';
                                                                                                                                                                                                                                                } ?> />
                                                <p class="text-danger">Minimum Withdrawal is USD 10</p>
                                            </div>
                                            <?php
                                            if ($_SESSION['redeem_update']['amount']) {
                                            ?>
                                                <div class="form-group col-md-6 col-sm-6">
                                                    <label>OTP*</label>
                                                    <input type="text" name="otp" class="form-control" data-rule-required="true" />
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        <?php } else if ($redeem_with == 'inr') { ?>

                                            <div class="form-group col-md-4 col-sm-4">
                                                <label>Bank Name*</label>
                                                <input type="text" name="bank_name" value="<?php echo $r['bnk_nm']; ?>" class="form-control" placeholder="Enter Bank Name" data-rule-required="true" <?php if ($r['bnk_nm'] != '') {
                                                                                                                                                                                                            echo 'readonly';
                                                                                                                                                                                                        } ?> />
                                            </div>

                                            <div class="form-group col-md-4 col-sm-4">
                                                <label>Account Name*</label>
                                                <input type="text" name="acc_name" value="<?php echo $r['acc_nm']; ?>" class="form-control" placeholder="Enter A/c Name" data-rule-required="true" <?php if ($r['acc_nm'] != '') {
                                                                                                                                                                                                        echo 'readonly';
                                                                                                                                                                                                    } ?> />
                                            </div>

                                            <div class="form-group col-md-4 col-sm-4">
                                                <label>Account No.*</label>
                                                <input type="text" name="acc_no" value="<?php echo $r['acc_no']; ?>" class="form-control" placeholder="Enter A/c No." data-rule-required="true" <?php if ($r['acc_no'] != '') {
                                                                                                                                                                                                    echo 'readonly';
                                                                                                                                                                                                } ?> />
                                            </div>

                                            <div class="form-group col-md-4 col-sm-4">
                                                <label>IFSC Code*</label>
                                                <input type="text" name="ifsc" value="<?php echo $r['ifsc']; ?>" class="form-control" placeholder="Enter IFSC Code" data-rule-required="true" <?php if ($r['ifsc'] != '') {
                                                                                                                                                                                                    echo 'readonly';
                                                                                                                                                                                                } ?> />
                                            </div>

                                            <div class="form-group col-md-4 col-sm-4">
                                                <label>PAN No.</label>
                                                <input type="text" name="pan_no" value="<?php echo $r['pan_no']; ?>" class="form-control" placeholder="Enter PAN No." <?php if ($r['pan_no'] != '') {
                                                                                                                                                                            echo 'readonly';
                                                                                                                                                                        } ?> />
                                            </div>

                                        <?php } else {
                                            echo '';
                                        } ?>

                                        <div class="col-md-12">
                                            <input type="hidden" name="redeem_with" value="<?php echo $_SESSION['redeem_update']['redeem_with'] ?>" readonly data-rule-required="true" />
                                            <button type="submit" name="submit" id="submit" value="Proceed" class="btn btn-danger col-md-2 col-sm-3">Proceed</button>
                                        </div>
                                    </div>

                                <?php } ?>
                            </form>
                        <?php } else { ?>
                            <div class="alert alert-danger">You Cant Proceed Further ! Your Last Withdraw Request is Pending Now.</div>
                        <?php } ?>

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

    <script>
        function getAmount() {
            let amount = $('#amount').val();
            let wallet = $('#wallet').val();
            if (wallet == 'working') {
                $('#validAmount').html('Amount : ' + (amount * 78))
            }
            if (wallet == 'roi') {
                $('#validAmount').html('Amount : ' + (amount * 78))
            }
        }
        $('body').on('click', '#payBtn', function() {
            $('#payBtn').hide();
            $('#payMsg').html('<h4><i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i> Loading...</h4>');
        });
        <?php
        if (isset($_REQUEST['pay']) && $_REQUEST['pay'] == true) {
            $id = $_REQUEST['id'];
            echo "var id='$id';";
        } ?>
    </script>
    <?php if (isset($_REQUEST['pay']) && $_REQUEST['pay'] == true) { ?>
        <script src="../web-assets/js/customjs.js"></script>
    <?php } ?>
</body>

</html>