<?php
include('include/privilege.php');
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
                            <h2>Balance Deposit Request</h2>
                            <p>Balance request from your account</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title-2 mb-4">
                            <a href="view-myfund-request" class="btn btn-sm btn-warning">View Balance Request</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <hr />
                        <?php include('include/alert.php'); ?>

                        <!-- Admin Bank Details Section -->
                        <?php
                        $con = $db->connect();
                        $query = "SELECT `bank_name`, `account_no`, `ifsc`, `branch` FROM `bank_settings` WHERE `type` = 'fund_request'";
                        $result = $con->query($query);

                        if ($result && $result->num_rows > 0) {
                            echo '<div class="admin-bank-details mb-3">';
                            echo '<h4 class="mb-2">Admin Bank Details</h4>';
                            while ($row = $result->fetch_assoc()) {
                                echo '<p class="mb-1"><strong>Bank Name:</strong> ' . htmlspecialchars($row['bank_name']) . '</p>';
                                echo '<p class="mb-1"><strong>Account Number:</strong> ' . htmlspecialchars($row['account_no']) . '</p>';
                                echo '<p class="mb-1"><strong>IFSC Code:</strong> ' . htmlspecialchars($row['ifsc']) . '</p>';
                                echo '<p class="mb-1"><strong>Branch:</strong> ' . htmlspecialchars($row['branch']) . '</p>';
                            }
                            echo '</div>';
                        } else {
                            echo '<div class="admin-bank-details mb-3">';
                            echo '<h4 class="mb-2">Admin Bank Details</h4>';
                            echo '<p class="mb-1">No bank details available.</p>';
                            echo '</div>';
                        }
                        $con->close();
                        ?>
                        <!-- End Admin Bank Details Section -->

                        <form id="frmAdd" action="myfund-requestc" method="post" enctype='multipart/form-data'>

                            <div class="row">
                                <div class="form-group col-md-6 col-sm-6">
                                    <label>Amount*</label>
                                    <input type="text" name="amount" id="amount" class="form-control" data-rule-digits="true" data-rule-required="true" placeholder="Enter the amount" required />
                                    <div id="validAmount"></div>
                                </div>

                                <div class="form-group col-md-6 col-sm-6">
                                    <label>Payment Mode <span>*</span></label>
                                    <select name="payment_type" id="payment_type" class="form-control" data-rule-required="true" required>
                                        <option value="">---Select---</option>
                                        <option value="cash_office">Cash at Office</option>
                                        <option value="cash_bank">Cash Deposit in Bank</option>
                                        <option value="cheque_office">Cheque at Office</option>
                                        <option value="cheque_bank">Cheque Deposit in Bank</option>
                                        <option value="neft">NEFT (National Electronic Fund Transfer)</option>
                                        <option value="paytm">PAYTM</option>
                                        <option value="upi">UPI (Unified Payments Interface)</option>
                                        <option value="money_portal">Online Payment</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="payment">
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>Upload The Image <span>*</span></label>
                                    <input type="file" name="screenshots" class="form-control" required="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <input type="submit" name="submit" id="submit" class="btn btn-danger" value="Submit">
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

    <!-- particles -->
    <script src="../web-assets/js/particles.min.js"></script>
    <script src="../web-assets/js/app.js"></script>

    <script type="text/javascript">
        $.fn.show_payment_mode = function() {
            var payment_type = $("#payment_type").val();

            $("#payment").html("<div style='text-align:left;'><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i><span class='sr-only'>Loading...</span></div>");

            $.ajax({
                type: "POST",
                url: "../ajax/payment-mode",
                data: {
                    payment_type: payment_type,
                },
                cache: false,
                //async:false,
                success: function(result) {
                    $("#payment").html(result);
                    showdtpckr();
                }
            });
            return false;
        }

        $.fn.verify_amount = function() {
            var amount = $("#amount").val();
            $.ajax({
                type: "POST",
                url: "../ajax/verify_amount",
                data: {
                    amount: amount
                },
                cache: false,
                success: function(result2) {
                    try {
                        var obj = JSON.parse(result2);
                        //alert(obj2.status);
                        if (obj.status == 1) {
                            $("#validAmount").html(`<span class='badge badge-success'>USD ${obj.rate}</span>`);
                        } else {
                            $("#validAmount").html("<span class='badge badge-danger'>Invalid Amount</span>");
                        }
                    } catch (e) {

                    }
                }
            });
            return false;
        }

        $(document).on("change keyup", "#amount", function() {
            $.fn.verify_amount();
        });

        $(document).on("change keyup", "#payment_type", function() {
            $.fn.show_payment_mode();

        });
    </script>




    <script type="text/javascript">
        $(document).ready(function() {
            /*$.fn.add_form = function () {
                if($('#recipiant_name').val()=='') {
                    $.fn.cbn("Recipiant Name Required", 'danger');
                }
                else {

                    // Show full page LoadingOverlay
                    $.LoadingOverlay("show");

                    var $thisForm = $('#frmAdd');
                    var formData = new FormData($thisForm[0]);
                    // formData.append( 'file', $( '#image' )[0].files[0] );
                    formData.append('response', 'json');

                    $.ajax({
                        type: "POST",
                        url: $thisForm.attr('action'),
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        //async:false,
                        success: function (result, textStatus, xhr) {

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

                            if(obj.status == '0') {
                                $thisForm[0].reset();

                                // alert(obj.message);
                                // $.fn.cbn(obj.message, 'success');
                                Swal.fire({
                                    // position: 'top-end',
                                    icon: 'success',
                                    title: obj.message,
                                    text: obj.ticket_no,
                                    // showConfirmButton: false,
                                    // timer: 5000,
                                    timerProgressBar: true,
                                }).then((result) => {
                                    // alert('hi');
                                     if(obj.refresh) {
                                        if (window.location.href != obj.back) {
                                            location.href = obj.back;
                                            // console.log(655);
                                        } else {
                                            location.reload();
                                            // console.log(658);
                                        }
                                    }
                                    else {
                                        $thisForm[0].reset();
                                    }

                                    // console.log(window.location.href);
                                    // console.log(obj.back);
                                });


                                return;

                            }
                            else if(obj.w != '') {
                                // alert(obj.message);
                                $.fn.cbn(obj.message, 'warning');

                            } else {
                                // alert(obj.message);
                                $.fn.cbn(obj.message, 'danger');
                            }

                            /*
                        }
                        catch (e) {
                            $.fn.cbn('Please Refresh the page, and try again');
                        }

                             */
            $thisForm.find("button[type=submit]").prop("disabled", false);
        }
        });

        }
        return false;
        }*/

        $(document).on("submit", "#frmAdd", function(event) {
        event.preventDefault();
        $.fn.add_form();
        });
        });
    </script>

</body>

</html>