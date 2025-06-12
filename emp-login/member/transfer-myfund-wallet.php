<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$con = $db->connect();
$myfund_wallet_balance = get_wallet_balance_of_member($con, $user_id, 'myfund_wallet_balance');
$db->dbDisconnet($con);
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
                            <h2>Transfer Fund to Other Member</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title-2 mb-4">
                            <?php
                            $balance_class = $myfund_wallet_balance <= 0 ? 'btn-danger' : 'btn-success';
                            ?>
                            <span class="btn btn-sm <?php echo $balance_class; ?>">Balance : <?php echo CURRENCY_ICON . $myfund_wallet_balance; ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <?php include('include/alert.php'); ?>
                        <form id="frmAdd" action="transfer-myfund-walletc" method="post">
                            <div class="row">
                                <div class="form-group col-md-4 col-sm-6">
                                    <label>Member Code</label>
                                    <input type="text" name="mem_code" id="mem_code" class="form-control text-uppercase" required style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" />
                                    <span id="valid_name" class='badge badge-success'></span>
                                    <span id="invalid_name" class='badge badge-danger'></span>
                                </div>
                                <div class="form-group col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="number" name="amount" id="transfer_amount" class="form-control" data-rule-digits="true" data-rule-required="true" />
                                        <span id="validAmount"></span>
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label>OTP </label>
                                        <div class="input-group">
                                            <input type="text" name="user_otp" class="form-control required" id="user_otp" pattern="[0-9]{4}" disabled="" required="">
                                            <span class="input-group-btn">
                                                <button class="btn btn-secondary" id="send_transfer_myfund_wallet_otp" type="button">Send OTP</button>
                                            </span>
                                        </div>
                                        <div id="send_transfer_myfund_wallet_otp_label"></div>
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <input id="send_transfer_myfund_wallet_btn" type="submit" name="submit" id="submit" class="btn btn-primary" value="Proceed">
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

            $.fn.verify_amount = function() {
                var amount = $("#transfer_amount").val();


                $.ajax({
                    type: "POST",
                    url: "../ajax/verify_myfund_amount",
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


                $('#valid_name').text("");
                $('#invalid_name').text("");


                if (mem_code == "") {
                    $('#invalid_name').text("Member Code Required");
                } else if (mem_code == window.user_code) {
                    $('#invalid_name').text("Own Transfer Not Possible");
                    $('#valid_name').text("");
                } else {
                    $.ajax({
                        type: "POST",
                        url: "../ajax/verify_member_downline.php",
                        data: {
                            mem_code: mem_code
                        },
                        cache: !1,
                        async: false,
                        success: function(a) {
                            var s = JSON.parse(a);
                            if (s.status == 1) {
                                $('#valid_name').text(s.name);
                            } else {
                                $('#invalid_name').text("Invalid Member Code");
                            }
                            //location.reload();
                        }
                    });
                }

            };

            $(document).on("change keyup", "#mem_code", function() {
                $.fn.getMemberName();

            });

            $.fn.transfer_myfund_wallet_otp_send = function() {
                var val_mem_code = $("#mem_code").val();
                var val_transfer_amount = $("#transfer_amount").val();
                var val_label_success = $('#valid_name').text();

                if (Math.floor(val_transfer_amount) == val_transfer_amount && $.isNumeric(val_transfer_amount) && (Math.abs(val_transfer_amount) == val_transfer_amount) && (val_transfer_amount > 0) /* && (val_mem_code!="") */ && (val_label_success != "")) {
                    $("#send_transfer_myfund_wallet_otp_label").html("");
                    $("#send_transfer_myfund_wallet_otp").html("Sending OTP...");

                    // $("#transfer_amount").prop('disabled', true);
                    $("#mem_code").prop('readonly', true);
                    $("#transfer_amount").prop('readonly', true);
                    $('#reset_transfer_myfund_wallet_form_label').hide();
                    $("#user_otp").prop('disabled', false);
                    $("#send_transfer_myfund_wallet_btn").prop('disabled', false);

                    $.ajax({
                        type: "POST",
                        url: "../ajax/otp_send_to_member_own",
                        data: {
                            otp_type: 'MAIL',
                            otp_for: 'transfer_myfund_wallet',
                            transfer_amount: val_transfer_amount,
                            transfer_to_mem_code: val_mem_code,
                        },
                        cache: false,
                        success: function(result) {
                            //console.log(result);
                            //alert(result.message);
                            $('#reset_transfer_myfund_wallet_form_label').show();
                            $('#send_transfer_myfund_wallet_otp_label').html(result.message);
                            try {
                                try {
                                    var obj = JSON.parse(result);
                                } catch (e) {
                                    var obj = result;
                                }
                                if (obj.status == '0') {
                                    $("#send_transfer_myfund_wallet_otp").html("Resend OTP");
                                } else {
                                    $("#send_transfer_myfund_wallet_otp").html("Resend Otp");
                                }
                            } catch (e) {
                                $("#send_transfer_myfund_wallet_otp").html("Resend otp");
                            }
                        }
                    });
                } else if (val_transfer_amount <= 0) {
                    $("#send_transfer_myfund_wallet_otp_label").html("Invalid Amount " + val_transfer_amount);
                } else {
                    console.log("name: " + val_label_success + " theend");
                    $("#send_transfer_myfund_wallet_otp_label").html("Input Valid Member Code");
                }
                return false;

            };

            // $.fn.update_debit_limit_otp_send();

            $(document).on("click", "#send_transfer_myfund_wallet_otp", function() {
                $.fn.getMemberName();
                $.fn.transfer_myfund_wallet_otp_send();

            });

            // $.fn.update_debit_limit_otp_send();

            $(document).on("click", "#reset_transfer_myfund_wallet_form_label", function() {
                // $('#transfer_amount').prop('disabled', false);
                $('#transfer_amount').prop('readonly', false);
                $('#mem_code').prop('readonly', false);
                $("#user_otp").prop('disabled', true);
                $("#send_transfer_myfund_wallet_btn").prop('disabled', true);
                $('#reset_transfer_myfund_wallet_form_label').hide();

            });

        });
    </script>

    <!-- particles -->
    <script src="web-assets/js/particles.min.js"></script>
    <script src="web-assets/js/app.js"></script>
</body>

</html>