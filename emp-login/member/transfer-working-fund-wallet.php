<?php
//die('under maintenance ');
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$con = $db->connect();
$working_wallet_balance = get_wallet_balance_of_member($con, $user_id, 'working_wallet_balance', $domainId);
$db->dbDisconnet($con);
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
                            <i class="fa fa-key" aria-hidden="true"></i>
                        </div>
                        <div class="caption">
                            <h2>TRANSFER working TO FUND WALLET</h2>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title-2 mb-4">
                            <?php if ($working_wallet_balance <= 0) { ?>
                                <span class="btn btn-danger">working Balance : <?php echo $working_wallet_balance; ?></span>
                            <?php } else { ?>
                                <span class="btn btn-success">working Balance : <?php echo $working_wallet_balance; ?></span>
                            <?php } ?>
                        </div>

                        <?php include('include/alert.php'); ?>

                        <form id="frmAdd" action="transfer-working-fund-walletc" method="post">
                            <div class="row">
                                <div class="form-group col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="number" name="amount" id="transfer_amount" class="form-control" data-rule-digits="true" data-rule-required="true" />
                                        <span id="validAmount"></span>
                                        <!-- <span>Minimum transfer amount is 1</span> -->
                                    </div>
                                </div>

                                <?php /*<div class="form-group col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label>OTP Type*</label>
                                        <select class="form-control" name="otp_type" id="otp_type" data-rule-required="true">
                                            <option value="">--CHOOSE ONE---</option>
                                            <option value="SMS">On SMS</option>
                                            <option value="MAIL">On EMAIL</option>
                                        </select>
                                        <span id="for_otp_type_sms" style="display: none;">To: <?= $_SESSION['user_data']['mobile']; ?></span>
                                        <span id="for_otp_type_mail" style="display: none;">To: <?= $_SESSION['user_data']['email']; ?></span>

                                    </div>
                                </div>

                                <div class="form-group col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label>OTP*</label>
                                        <div class="input-group">
                                            <input type="text" name="user_otp" class="form-control required" id="user_otp" pattern="[0-9]{4}" disabled="" required="">
                                            <span class="input-group-btn">
                                                <button class="btn btn-secondary" id="send_transfer_rf_wallet_otp" type="button">Send OTP</button>
                                            </span>
                                        </div>
                                        <div id="send_transfer_rf_wallet_otp_label"></div>
                                    </div>
                                </div><? */ ?>

                                <div class="form-group col-md-12 col-sm-6">
                                    <div class="form-group">
                                        <input id="send_transfer_rf_wallet_btn" type="submit" name="submit" id="submit" class="btn btn-primary" value="Proceed">
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

    <script type="text/javascript">
        $(document).ready(function() {
            $("#submit").click(function(event) {
                if (!confirm('Are you sure that you want to Transfer fund?'))
                    event.preventDefault();
            });
            $.fn.verify_amount = function() {
                var amount = $("#transfer_amount").val();

                $.ajax({
                    type: "POST",
                    url: "../ajax/verify_working_amount",
                    data: {
                        amount: amount
                    },
                    cache: false,
                    success: function(result) {
                        try {
                            var obj = JSON.parse(result);
                            //var valid_amt = amount%100;
                            //alert(valid_amt);
                            /*if(valid_amt!=0)
                            {
                                $("#validAmount").html("<span style='color:#C00;'>You are allowed to transfer in multiple of Rs.100/- only.</span>");
                            }
                            else*/
                            if (obj.status == 1) {
                                $("#validAmount").html("<span class='badge badge-success'>Valid</span> ");

                            } else if (obj.status == 3) {
                                $("#validAmount").html("<span class='badge badge-danger'>Invalid Amount</span>");
                            } else {
                                $("#validAmount").html("<span class='badge badge-danger'>Your wallet balance is low to transfer</span>");
                            }
                        } catch (e) {

                        }
                    }
                });
                return false;
            };

            $(document).on("change keyup", "#transfer_amount", function() {
                $.fn.verify_amount();
            });

            $.fn.getMemberName = function() {
                var mem_code = $('#mem_code').val();
                if (mem_code == "") {
                    $('.badge-danger').text("");
                    $('.badge-success').text("");
                } else if (mem_code == window.user_code) {
                    $('.badge-danger').text("Own Transfer Not Possible");
                    $('.badge-success').text("");
                } else {
                    $.ajax({
                        type: "POST",
                        url: "../ajax/verify_member_downline.php",
                        data: {
                            mem_code: mem_code
                        },
                        cache: !1,
                        success: function(a) {
                            var s = JSON.parse(a);
                            if (s.status == 1) {
                                $('.badge-success').text(s.name);
                                $('.badge-danger').text("");
                            } else {
                                $('.badge-danger').text("Invalid Member Code");
                                $('.badge-success').text("");
                            }
                            //location.reload();
                        }
                    });
                }
            };

            $(document).on("change keyup", "#mem_code", function() {
                $.fn.getMemberName();
            });

            $.fn.transfer_rf_wallet_otp_send = function() {
                var val_mem_code = "<?php echo $user_code; ?>"; //$("#mem_code").val();
                var val_transfer_amount = $("#transfer_amount").val();
                var val_label_success = $('.badge-success').text();
                var otp_type = $('#otp_type').find(":selected").val();

                console.log(val_mem_code);

                if (Math.floor(val_transfer_amount) == val_transfer_amount && $.isNumeric(val_transfer_amount) && (Math.abs(val_transfer_amount) == val_transfer_amount) && (val_transfer_amount > 0) /* && (val_mem_code!="") */ ) {
                    $("#send_transfer_rf_wallet_otp_label").html("");
                    $("#send_transfer_rf_wallet_otp").html("Sending OTP...");

                    // $("#transfer_amount").prop('disabled', true);
                    $("#mem_code").prop('readonly', true);
                    $("#transfer_amount").prop('readonly', true);
                    $('#reset_transfer_rf_wallet_form_label').hide();


                    $("#user_otp").prop('disabled', false);
                    $("#send_transfer_rf_wallet_btn").prop('disabled', false);

                    $.ajax({
                        type: "POST",
                        url: "../ajax/otp_send_to_member_own-dynamic",
                        data: {
                            otp_for: 'transfer_rf_wallet',
                            transfer_amount: val_transfer_amount,
                            transfer_to_mem_code: val_mem_code,
                            otp_type: otp_type,
                        },
                        cache: false,
                        success: function(result) {
                            //alert(result.message);
                            $('#reset_transfer_rf_wallet_form_label').show();
                            $('#send_transfer_rf_wallet_otp_label').html(result.message);


                            try {
                                try {
                                    var obj = JSON.parse(result);
                                } catch (e) {
                                    var obj = result;
                                }

                                if (obj.status == '0') {
                                    $("#send_transfer_rf_wallet_otp").html("Resend OTP");
                                } else {
                                    $("#send_transfer_rf_wallet_otp").html("Resend Otp");
                                }
                            } catch (e) {
                                $("#send_transfer_rf_wallet_otp").html("Resend otp");
                            }

                        }
                    });

                } else if (val_transfer_amount <= 0) {
                    $("#send_transfer_rf_wallet_otp_label").html("Invalid Amount " + val_transfer_amount);
                } else {
                    $("#send_transfer_rf_wallet_otp_label").html("Input Valid Member Code");
                }
                return false;
            };

            // $.fn.update_debit_limit_otp_send();
            $(document).on("click", "#send_transfer_rf_wallet_otp", function() {
                // $.fn.getMemberName();
                $.fn.transfer_rf_wallet_otp_send();
            });

            // $.fn.update_debit_limit_otp_send();
            $(document).on("click", "#reset_transfer_rf_wallet_form_label", function() {
                // $('#transfer_amount').prop('disabled', false);
                $('#transfer_amount').prop('readonly', false);
                $('#mem_code').prop('readonly', false);
                $("#user_otp").prop('disabled', true);
                $("#send_transfer_rf_wallet_btn").prop('disabled', true);
                $('#reset_transfer_rf_wallet_form_label').hide();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on("change", "#otp_type", function() {
                $("#for_otp_type_mail").hide();
                $("#for_otp_type_sms").hide();
                if ($(this).val() === 'MAIL') {
                    $("#for_otp_type_mail").show();
                } else if ($(this).val() === 'SMS') {
                    $("#for_otp_type_sms").show();
                }
            });
        });
    </script>

    <!-- particles -->
    <script src="../web-assets/js/particles.min.js"></script>
    <script src="../web-assets/js/app.js"></script>
</body>

</html>