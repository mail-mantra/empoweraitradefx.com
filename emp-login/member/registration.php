<?php
session_start();
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

if (isset($_SESSION['joining_email'])) {
    unset($_SESSION['joining_email']);
}

if (isset($_SESSION['joining_otp'])) {
    unset($_SESSION['joining_otp']);
}
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
                            <h2>New Registration</h2>
                            <p>Register yourself from here</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title-2 mb-4">Fill all details to complete your registration</div>
                    </div>
                    <div class="col-lg-12">

                        <?php include('include/alert.php'); ?>

                        <form id="frmAdd" action="referral-joinc" method="post">
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

                                <?php /* ?><div class="form-group col-md-6 col-sm-6">
                                    <label>Country*</label>
                                    <select name="country" class="form-control" required>
                                        <option value="">- - - Select - - -</option>
                                        <?php
                                        $sql1 = "SELECT * FROM `countries` ORDER BY `name` ASC ";
                                        $con = $db->connect();
                                        $res1 = mysqli_query($con, $sql1);
                                        $con->close();
                                        if (mysqli_num_rows($res1)) {
                                            while ($arr1 = mysqli_fetch_assoc($res1)) {
                                        ?>
                                                <option value="<?php echo $arr1['name']; ?>" <?= ($arr1['name'] == 'India') ? "selected" : ""; ?>><?php echo $arr1['name']; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div><?php */ ?>

                                <div class="form-group col-md-6 col-sm-6">
                                    <label for="mobile">Mobile No.*</label>
                                    <div class="input-group mb-4">
                                        <input type="text" name="mobile" id="mobile" class="form-control" maxlength="10" data-rule-required="true" data-rule-digits="true" data-rule-minlength="10" data-rule-maxlength="10" />
                                    </div>
                                </div>

                                <div class="form-group col-md-6 col-sm-6">
                                    <label for="email">Email ID*</label>
                                    <div class="input-group mb-4">
                                        <input type="email" name="email" id="email" class="form-control" data-rule-required="true" />
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label>OTP </label>
                                        <div class="input-group">
                                            <input type="text" name="user_otp" class="form-control required" id="user_otp" pattern="[0-9]{4}" disabled="" required="">
                                            <span class="input-group-btn">
                                                <button class="btn btn-secondary" id="send_otp" type="button">Send OTP</button>
                                            </span>
                                        </div>
                                        <div id="send_otp_label" style="color: #fff"></div>
                                    </div>
                                </div>
                                <!-- <div class="form-group col-md-6 col-sm-6">
                                    <label for="pan">PAN No.</label>
                                    <div class="input-group mb-4">
                                        <input type="text" name="pan_no" id="pan_no" class="form-control" />
                                    </div>
                                </div> -->

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
            //$.fn.verify_intro();

            /*$("#no_pan").change(function() {
                if(this.checked) {
                    $('#pan_no').val('A/F');
                    $('#pan_no').prop("readonly", true);
                }else{
                    $('#pan_no').val('');
                    $('#pan_no').prop("readonly", false);
                }
            });*/

            // OTP
            $.fn.otp_send = function() {
                var email = $("#email").val();

                //alert(email);

                if (email != "") {
                    $("#send_otp_label").html("");
                    $("#send_otp").html("Sending OTP...");

                    $("#email").prop('readonly', true);
                    $("#user_otp").prop('disabled', false);
                    $("#send_btn").prop('disabled', false);

                    $.ajax({
                        type: "POST",
                        url: "../ajax/send-otp",
                        data: {
                            otp_for: 'joining',
                            email: email,
                        },
                        cache: false,
                        success: function(result) {
                            //alert(result.message);
                            $('#reset_form_label').show();
                            $('#send_otp_label').html(result.message);
                            try {
                                try {
                                    var obj = JSON.parse(result);
                                } catch (e) {
                                    var obj = result;
                                }
                                if (obj.status == '0') {
                                    $("#send_otp").html("Resend OTP");
                                } else {
                                    $("#send_otp").html("Resend Otp");
                                }
                            } catch (e) {
                                $("#send_otp").html("Resend otp");
                            }
                        }
                    });
                } else if (val_transfer_amount <= 0) {
                    $("#send_otp_label").html("Invalid Amount " + val_transfer_amount);
                } else {
                    console.log("name: " + val_label_success + " theend");
                    $("#send_otp_label").html("Input Valid Member Code");
                }
                return false;

            };

            $(document).on("click", "#send_otp", function() {
                $.fn.otp_send();

            });

            $(document).on("click", "#reset_form_label", function() {
                $('#email').prop('readonly', false);
                $("#user_otp").prop('disabled', true);
                $("#send_btn").prop('disabled', true);
                $('#reset_form_label').hide();

            });
            // .OTP
        });

        $(document).on("change keyup", "#intro", function() {
            $.fn.verify_intro();
        });
    </script>

    <!-- particles -->
    <script src="web-assets/js/particles.min.js"></script>
    <script src="web-assets/js/app.js"></script>
</body>

</html>