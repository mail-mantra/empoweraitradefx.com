<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();


$user_id = $_SESSION['user_data']['user_id'];
$user_code = $_SESSION['user_data']['user_code'];

$sql_exist = "SELECT id FROM refunds WHERE current_status<>2 AND `member_id`='$user_id' AND MONTH(CURDATE()) = MONTH(`created_at`) AND YEAR(CURDATE()) = YEAR(`created_at`); ";
$con = $db->connect();
$res_exist = $con->query($sql_exist);
$con->close();
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
                    <div class="dashboard-title-2" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="caption-2">
                            <h2>REFUND ENTRY</h2>
                            <p>Refund to Company</p>
                        </div>
                        <div>
                            <a href="view-refund-entry" class="btn btn-warning">View Refund Entry</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <?php include('include/alert.php'); ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <form id="frmAdd" action="refund-entryc.php" method="post" enctype='multipart/form-data'>
                                <div class="row">

                                    <div class="form-group col-md-12">
                                        <label>Amount (USD)*</label>
                                        <input type="number" step="0.01" min="0.01" max="99999999" name="refund_amount" id="refund_amount" class="form-control" data-rule-digits="true" data-rule-required="true" placeholder="Enter USD amount" />
                                        <div id="inrAmount"></div>
                                        <div id="inWord"></div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Date*</label>
                                        <input type="date" name="transaction_date" class="form-control" data-rule-required="true" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Transaction Number*</label>
                                        <input type="text" name="transaction_number" class="form-control" data-rule-required="true" placeholder="UTR Number" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Screen Shot *</label>
                                        <input type="file" name="transaction_img" class="form-control-file" required data-rule-required="true" accept="image/jpeg" />
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="hidden" value="1" name="declaration" required style="visibility: visible;" checked>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <?php /* ?>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="validatedCustomFile" required>
                                                        <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                                                        <div class="invalid-feedback">Example invalid custom file feedback</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php */ ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button type="submit" name="submit" id="submit" class="btn btn-warning px-4" value="Submit">
                                                SUBMIT
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </form>

                        </div>
                        <div class="col-md-6 mt-3">
                            <h4>Please deposite your amount at -</h4>
                            NAME -
                            <br />
                            Bank Name -
                            <br />
                            A/C NO-
                            <br />
                            IFSC-
                        </div>
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
            $.fn.getInrAmount = function() {
                var refund_amount = $("#refund_amount").val();
                $.ajax({
                    type: "POST",
                    url: "../ajax/get-refund-usd-to-inr",
                    data: {
                        usd_amount: refund_amount
                    },
                    cache: false,
                    success: function(result) {
                        var res = $.parseJSON(result);
                        if (res.status) {
                            $("#inrAmount").html(`INR ${res.inrAmount}`);

                            $("#inWord").html(`In Word : ${getConvertNumberToWords(refund_amount)}`);
                        } else {
                            $("#inrAmount").html('Invalid');
                        }
                    }
                });
                return false;
            }
            $.fn.verify_intro();

        });

        $(document).on("change keyup", "#refund_amount", function() {
            $.fn.getInrAmount();
        });

        function getConvertNumberToWords(num) {
            var ones = ["", "One ", "Two ", "Three ", "Four ", "Five ", "Six ", "Seven ", "Eight ", "Nine ", "Ten ", "Eleven ", "Twelve ", "Thirteen ", "Fourteen ", "Fifteen ", "Sixteen ", "Seventeen ", "Eighteen ", "Nineteen "];
            var tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
            if ((num = num.toString()).length > 9) return "Overflow: Maximum 9 digits supported";
            n = ("000000000" + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
            if (!n) return;
            var str = "";
            str += n[1] != 0 ? (ones[Number(n[1])] || tens[n[1][0]] + " " + ones[n[1][1]]) + "Crore " : "";
            str += n[2] != 0 ? (ones[Number(n[2])] || tens[n[2][0]] + " " + ones[n[2][1]]) + "Lakh " : "";
            str += n[3] != 0 ? (ones[Number(n[3])] || tens[n[3][0]] + " " + ones[n[3][1]]) + "Thousand " : "";
            str += n[4] != 0 ? (ones[Number(n[4])] || tens[n[4][0]] + " " + ones[n[4][1]]) + "Hundred " : "";
            str += n[5] != 0 ? (str != "" ? "and " : "") + (ones[Number(n[5])] || tens[n[5][0]] + " " + ones[n[5][1]]) : "";

            return str;
        }
    </script>
</body>

</html>