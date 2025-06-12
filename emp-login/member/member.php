<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

if (isset($_REQUEST['intro_code'])) {
    $intro_code = addslashes(trim($_REQUEST['intro_code']));
} else {
    $intro_code = $user_code;
}

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
                            <h2>Add Member</h2>
                            <p>Welcome to Member panel.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title-2 mb-4">
                            Joining Details
                        </div>
                    </div>

                    <div class="col-lg-12">

                        <?php include('include/alert.php'); ?>

                        <form id="frmAdd" action="memberc" method="post">
                            <div class="row">
                                <div class="form-group col-md-6 col-sm-6">
                                    <label>Introducer Code*</label>
                                    <input type="text" name="intro" id="intro" class="form-control"
                                        value="<?= $intro_code; ?>" data-rule-required="true" />
                                </div>

                                <div class="form-group col-md-6 col-sm-6" id="introSection"></div>

                                <div class="form-group col-md-6 col-sm-6">
                                    <label>Name*</label>
                                    <input type="text" name="name" class="form-control" data-rule-required="true" />
                                </div>

                                <?php /* ?><div class="form-group col-md-6 col-sm-6">
                                    <label>Country*</label>
                                    <select name="country" class="form-control" required>
                                        <option value="">- - - Select Country - - -</option>
                                        <?php
                                        $sql1 = "SELECT * FROM `countries` ORDER BY `name` ASC";
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
                                    <div class="input-group mb-3">
                                        <input type="text" name="mobile" id="mobile" class="form-control" maxlength="10"
                                            data-rule-required="true" data-rule-digits="true" data-rule-minlength="10"
                                            data-rule-maxlength="10" />
                                    </div>
                                </div>

                                <div class="form-group col-md-6 col-sm-6">
                                    <label for="email">Email ID*</label>
                                    <div class="input-group mb-3">
                                        <input type="email" name="email" id="email" class="form-control"
                                            data-rule-required="true" />
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

                                <!--<div class="form-group col-md-6 col-sm-6">
                                    <label>Package*</label>
                                    <select name="package_id" class="form-control" required>
                                        <option value="1" selected>Joining Package - $10</option>
                                    </select>
                                </div>-->
                            </div>

                            <!-- <div class="row">
                                <div class="form-group col-md-6 col-sm-6 mb-3">
                                    <label for="pan_no">PAN No.*</label>
                                    <input type="text" name="pan_no" id="pan_no" class="form-control"
                                        data-rule-required="true" />
                                    <small class="form-text text-muted" id="pan_no_Help"></small>
                                </div>
                            </div> -->


                            <div class="col-md-12">
                                <label><input type="checkbox" value="yes" name="terms_and_conditions"
                                        data-rule-required="true">
                                    I agree to the <a href="#" target="_blank"><span>Privacy Policy</span></a>
                                    <a href="#" target="_blank"><span>Terms of Service</span></a>
                                </label>
                            </div>


                            <div class="col-md-12">
                                <button type="submit" name="submit" id="submit" value="Submit"
                                    class="btn btn-danger col-md-2 col-sm-3">Submit
                                </button>
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
            $.fn.verify_intro();

            /*$("#no_pan").change(function() {
                if(this.checked) {
                    $('#pan_no').val('A/F');
                    $('#pan_no').prop("readonly", true);
                }else{
                    $('#pan_no').val('');
                    $('#pan_no').prop("readonly", false);
                }
            });*/


            $.fn.show_confirm_password = function() {
                var new_pwd = $("#new_pwd").val();
                var conf_pwd = $("#conf_pwd").val();
                $.ajax({
                    type: "POST",
                    url: "../ajax/confirm_password",
                    data: {
                        new_pwd: new_pwd,
                        conf_pwd: conf_pwd
                    },
                    cache: false,
                    success: function(result) {
                        try {
                            var obj = JSON.parse(result);
                            if (obj.status == 1) {
                                $("#confirm_password").html("<span class='badge badge-success'>Correct</span>");

                            } else {
                                $("#confirm_password").html("<span class='badge badge-danger'>Password Mismatch</span>");
                            }
                        } catch (e) {

                        }
                    }
                });
                return false;
            }


            // OTP
            $.fn.otp_send = function() {
                var email = $("#email").val();

                alert(email);

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

        $(document).on("change keyup", "#conf_pwd", function() {
            $.fn.show_confirm_password();
        });
    </script>

    <!-- particles -->
    <script src="web-assets/js/particles.min.js"></script>
    <script src="web-assets/js/app.js"></script>


    <script type="text/javascript">
        $(document).ready(function() {
            $.fn.verify_pan_no = function() {
                var val_pan_no = $("#pan_no").val();
                $("#pan_no_Help").text('');

                if (val_pan_no.length == 10) {
                    $.ajax({
                        type: "POST",
                        url: "../../ajax/get_kyc_data.php",
                        data: {
                            pan: val_pan_no
                        },
                        cache: false,
                        success: function(result) {
                            var val_obj = JSON.parse(result);
                            console.log(val_obj.data.user_full_name);
                            if (val_obj.status == 0) {
                                $("#pan_no_Help").text(val_obj.data.user_full_name);
                            } else {
                                console.log(val_obj.message);
                                $("#pan_no_Help").text('Invalid PAN');
                            }

                            // alert(s.status);
                            // if (s.status == 1) {
                            //     $('.badge-success').text(s.name);
                            //     $('.badge-danger').text("");
                            // }
                            // else {
                            //     $('.badge-danger').text("Invalid Member Code");
                            //     $('.badge-success').text("");
                            // }
                        }
                    });
                }
                return false;
            }

            $.fn.verify_pan_no();


            $(document).on("change", "#pan_no", function() {
                $.fn.verify_pan_no();
            });

            $(document).on("change", "#free_insurance", function() {
                var free_insurance = $('#free_insurance').find(":selected").val();
                if (free_insurance == "yes") {
                    $('#nom').show();
                    $('#nom_relation').show();
                } else {
                    $('#nom').hide();
                    $('#nom_relation').hide();
                }
            });
        });
    </script>

</body>

</html>