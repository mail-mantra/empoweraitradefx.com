<?php
session_start();
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
</head>

<body>
    <!--start-mm-menu-direction-->
    <?php include('include/menu-direction-reg.php'); ?>
    <!--end-mm-menu-direction-->

    <!--start-body-content-->
    <div class="body-content">

        <!--start-mm-top-header-->
        <?php include('include/mm-top-header-reg.php'); ?>
        <!--end-mm-top-header-->

        <div class="container">
            <div class="col-lg-12">
                <div class="row">
                    <div class="dashboard-title-2">
                        <div class="caption-2">
                            <h2>Member Registration</h2>
                            <p>Welcome to Member panel.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title-2 mb-4">Joining Details</div>
                    </div>
                    <div class="col-lg-12">

                        <?php include('include/alert.php'); ?>

                        <form id="frmAdd" action="member-signupc" method="post">
                            <div class="row">
                                <div class="form-group col-md-6 col-sm-6">
                                    <label>Introducer Code*</label>
                                    <input type="text" name="intro" id="intro" class="form-control" data-rule-required="true" />
                                </div>

                                <div class="form-group col-md-6 col-sm-6" id="introSection"></div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6 col-sm-6">
                                    <label>Name*</label>
                                    <input type="text" name="name" class="form-control" data-rule-required="true" />
                                </div>
                                <div class="form-group col-md-6 col-sm-6">
                                    <label for="mobile">Mobile No.*</label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="mobile" id="mobile" class="form-control" maxlength="10"
                                            data-rule-required="true" data-rule-digits="true" data-rule-minlength="10"
                                            data-rule-maxlength="10" />
                                    </div>
                                </div>
                                <div class="form-group col-md-6 col-sm-6">
                                    <label for="email">Email ID*</label>
                                    <div class="input-group mb-3">
                                        <input type="email" name="email" id="email" class="form-control" data-rule-required="true" />
                                    </div>
                                </div>

                                <div class="form-group col-md-6 col-sm-6">
                                    <label>Package*</label>
                                    <select name="package_id" class="form-control" required>
                                        <option value="1" selected>Joining Package - $10</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" name="submit" id="submit" value="Submit" class="btn btn-danger col-md-2 col-sm-3">Submit
                                    </button>
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
            $.fn.verify_intro = function() {
                var intro = $("#intro").val();
                $.ajax({
                    type: "POST",
                    url: "../ajax/intro",
                    data: {
                        intro: intro
                    },
                    cache: false,
                    success: function(result) {
                        $("#introSection").html(result);
                    }
                });
                return false;
            }

            /*$("#no_pan").change(function() {
                if(this.checked) {
                    $('#pan_no').val('A/F');
                    $('#pan_no').prop("readonly", true);
                }else{
                    $('#pan_no').val('');
                    $('#pan_no').prop("readonly", false);
                }
            });*/
        });

        $(document).on("change keyup", "#intro", function() {
            //$.fn.verify_intro();
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Function to enable the button
            $.fn.enableResendButton = function($button_id) {
                $($button_id).prop('disabled', false).text('Resend OTP');
            }

            // Function to start the countdown
            $.fn.startCountdown = function($button_id) {
                let countdown = 60; // 1 minute in seconds
                $($button_id).text(`Resend OTP (${countdown}s)`);

                const timer = setInterval(function() {
                    countdown--;
                    $($button_id).text(`Resend OTP (${countdown}s)`);

                    if (countdown <= 0) {
                        clearInterval(timer);
                        $.fn.enableResendButton($button_id);
                    }
                }, 1000); // Update every second
            }

            // Initially start the countdown
            // startCountdown($button_id);

            // On click, disable the button and start a new countdown
            // $($button_id).click(function() {
            //     $(this).prop('disabled', true);
            //     startCountdown($button_id);
            //     startCountdown("#send-mobile-otp-btn");
            // });


            $(document).on("click", "#send-email-otp-btn", function() {
                let val_email = $('#email').val().trim().toLowerCase();

                // Show full page LoadingOverlay
                $.LoadingOverlay("show");

                $.ajax({
                    type: "POST",
                    url: "../ajax/member-join-send-email-otp.php",
                    data: {
                        email: val_email,
                        otp_for: 'referral_join',
                    },
                    cache: false,
                    success: function(result) {
                        // Show full page LoadingOverlay
                        $.LoadingOverlay("hide");
                        // try {
                        try {
                            var obj = JSON.parse(result);
                        } catch (e) {
                            var obj = result;
                        }


                        // $thisForm.find("input[type=text], input[type=date], input[type=password], input[type=email], input[type=number], input[type=url], select, textarea").val("");
                        // $thisForm.find("select option[value='']").prop('selected', 'selected').change();

                        if (obj.status == '0') {
                            // $thisForm[0].reset();
                            $.fn.cbn(obj.message, 'success');

                            $("#send-email-otp-btn").prop('disabled', true);
                            $.fn.startCountdown("#send-email-otp-btn");
                        } else
                        /*if (obj.w != '') {
                                               // alert(obj.message);
                                               $.fn.cbn(obj.message, 'warning');

                                           }
                                           else*/
                        {
                            // alert(obj.message);
                            $.fn.cbn(obj.message, 'danger');
                        }

                    }
                });
            });


            $(document).on("click", "#send-mobile-otp-btn", function() {
                let val_mobile = $('#mobile').val().trim().toLowerCase();

                // Show full page LoadingOverlay
                $.LoadingOverlay("show");

                $.ajax({
                    type: "POST",
                    url: "../ajax/member-join-send-mobile-otp.php",
                    data: {
                        mobile: val_mobile,
                        otp_for: 'referral_join',
                    },
                    cache: false,
                    success: function(result) {
                        // Show full page LoadingOverlay
                        $.LoadingOverlay("hide");
                        // try {
                        try {
                            var obj = JSON.parse(result);
                        } catch (e) {
                            var obj = result;
                        }


                        // $thisForm.find("input[type=text], input[type=date], input[type=password], input[type=email], input[type=number], input[type=url], select, textarea").val("");
                        // $thisForm.find("select option[value='']").prop('selected', 'selected').change();

                        if (obj.status == '0') {
                            // $thisForm[0].reset();
                            $.fn.cbn(obj.message, 'success');

                            $("#send-mobile-otp-btn").prop('disabled', true);
                            $.fn.startCountdown("#send-mobile-otp-btn");
                        } else
                        /*if (obj.w != '') {
                                               // alert(obj.message);
                                               $.fn.cbn(obj.message, 'warning');

                                           }
                                           else*/
                        {
                            // alert(obj.message);
                            $.fn.cbn(obj.message, 'danger');
                        }

                    }
                });
            });
        });
    </script>
    <!-- particles -->
    <script src="web-assets/js/particles.min.js"></script>
    <script src="web-assets/js/app.js"></script>
</body>

</html>