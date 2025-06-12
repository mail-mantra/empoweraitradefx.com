<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$con = $db->connect();
$working_wallet = get_wallet_balance_of_member($con, $user_id, 'working_wallet_balance');
$roi_wallet = get_wallet_balance_of_member($con, $user_id, 'roi_wallet_balance');
$country = $member_data_['country'];
$db->dbDisconnet($con);

$con = $db->connect();
$member_data = member_id_details($con, $user_id);
$db->dbDisconnet($con);
$open = false;

if (date('d') == 2 || date('d') == 4 || date('d') == 17) {
    $open = true;
} else {
    $open = true;
}


$crypto_deposit = true;

/* if (date("d-m-Y") == "13-09-2023" || date("d-m-Y") == "14-09-2023") {
    $open = true;
} 
*/

/* if ($user_id == 6914) {
    $open = true;
} */

/* $con = $db->connect();
$sell_rate = get_sale_rate($con);
$con->close(); */

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php include('include/title.php'); ?></title>

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
                            <h2>USDT(BEP20) Withdraw Request</h2>
                            <p>Withdraw fund from your Account USDT(BEP20)</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title-2 mb-4">
                            <?php if ($working_wallet <= 0) { ?>
                                <span class="btn btn-danger">Working Balance : <?php echo CURRENCY_ICON . $working_wallet; ?></span>
                            <?php } else { ?>
                                <span class="btn btn-success">Working Balance : <?php echo CURRENCY_ICON . $working_wallet; ?></span>
                            <?php } ?>

                            <?php if ($roi_wallet <= 0) { ?>
                                <span class="btn btn-danger">ROI Balance : <?php echo CURRENCY_ICON . $roi_wallet; ?></span>
                            <?php } else { ?>
                                <span class="btn btn-success">ROI Balance : <?php echo CURRENCY_ICON . $roi_wallet; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <?php include('include/alert.php'); ?>
                        <?php
                        if ($crypto_deposit) {
                            if ($open) {
                        ?>
                                <form id="frmAdd" action="redeem-money-bep20c" method="post">
                                    <div class="row">
                                        <div class="form-group col-md-6 col-sm-6">
                                            <label>Wallet *</label>
                                            <select class="form-control" name="wallet_name" id="wallet" required>
                                                <option value="">---Select---</option>
                                                <option value="working">Working Wallet</option>
                                                <option value="roi">ROI Wallet</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6 col-sm-6">
                                            <label>Amount*</label>
                                            <input type="text" name="amount" id="amount" class="form-control" data-rule-required="true" data-rule-number="true" onchange="getInr()" />
                                            <div id="inrValue"></div>
                                            <input type="hidden" id="sell_rate" value="<?php echo $sell_rate ?>" />
                                        </div>

                                        <div class="form-group col-md-6 col-sm-6">
                                            <label>Address*</label>
                                            <input type="text" value="<?php echo $member_data['crypto_address_bep20']; ?>" name="crypto_address" id="crypto_address" class="form-control" placeholder="Enter Address" data-rule-required="true" <?php if ($member_data['crypto_address_bep20'] != '') {
                                                                                                                                                                                                                                                    echo 'readonly';
                                                                                                                                                                                                                                                } ?> />
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" name="submit" id="submit" value="Proceed" class="btn btn-primary col-md-2 col-sm-3">Proceed</button>
                                        </div>
                                    </div>
                                </form>
                                <span class="" style="color:#fff;"> <br> NOTE : Minimum withdraw 10 USDT.</span>
                            <?php
                            } else {
                            ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-lg-12">
                                            <div class="alert alert-warning" role="alert">Each 2nd,3rd, and 17th day of every month will open.</div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="col-lg-12">
                                        <div class="alert alert-danger" role="alert">You are not eligible for this service.</div>
                                    </div>
                                </div>
                            </div>
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
    <!-- bank details -->
    <script type="text/javascript">
        $(document).on("keyup", "#amount", function() {
            let amount = $('#amount').val();
            /* let sell_rate = $('#sell_rate').val();
            let texValue = (amount * 75) / sell_rate;
            $('#inrValue').html('USDT ' + texValue); */
            let fetchRes = fetch(
                "https://api.coingecko.com/api/v3/simple/price?ids=tether&vs_currencies=usd");
            fetchRes.then(res =>
                res.json()).then(d => {
                let usdRate = 1 / d.tether.usd;
                let texValue = Math.round((amount * usdRate) * 100) / 100;
                $('#inrValue').html('USDT ' + texValue);
            })
        });
    </script>
    <!-- /bank details -->
    <!-- particles -->
    <script src="../web-assets/js/particles.min.js"></script>
    <script src="../web-assets/js/app.js"></script>
</body>

</html>