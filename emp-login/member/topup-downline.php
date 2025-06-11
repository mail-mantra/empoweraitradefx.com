<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$con = $db->connect();
$myfund_wallet = get_wallet_balance_of_member($con, $user_id, 'myfund_wallet_balance');
$db->dbDisconnet($con);

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
                            <h2>Community Trade Investment (Downline)</h2>
                            <p>Downline Investment</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title-2 mb-4">
                            <?php if ($myfund_wallet <= 0) { ?>
                                <span class="btn btn-sm btn-danger">Fund Balance : <?php echo CURRENCY_ICON . $myfund_wallet; ?></span>
                            <?php } else { ?>
                                <span class="btn btn-sm btn-success">Fund Balance : <?php echo CURRENCY_ICON . $myfund_wallet; ?></span>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <?php include('include/alert.php'); ?>

                        <form id="frmAdd" action="topup-downlinec" method="post">
                            <div class="row">
                                <!--<div class="form-group col-md-4 col-sm-6">
                                    <label>Select Wallet*</label>
                                    <select name="wallet" class="form-control" data-rule-required="true">
                                        <option value="fund">My Fund Wallet</option>
                                        <option value="working">Working Wallet</option>
                                    </select>
                                </div>-->
                                
                                <div class="form-group col-md-4 col-sm-6">
                                    <label>Member Code*</label>
                                    <input type="text" name="mem_code" id="mem_code" class="form-control" required onkeyup="getMemberName()" placeholder="Enter member code*">
                                    <div id="validMember"></div>
                                </div>

                                <div class="form-group col-md-4 col-sm-6">
                                    <label>Amount*</label>
                                    <input type="number" name="amount" id="amount" value="50" class="form-control" data-rule-digits="true" data-rule-required="true" placeholder="Enter the amount" />
                                    <span class="text-danger">Minimum 50 and multiple of 50</span>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" name="submit" id="submit" value="Submit" class="btn btn-sm btn-danger col-md-1 col-sm-3">Submit</button>
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
        function getMemberName() {
            var mem_code = $('#mem_code').val();
            $.ajax({
                type: "POST",
                url: "../ajax/verify_member.php",
                data: {
                    mem_code: mem_code
                },
                cache: !1,
                success: function(a) {
                    var s = JSON.parse(a);
                    if (s.status == 1) {
                        $("#validMember").html("<span class='badge badge-success'>" + s.name + "</span>");
                    } else {
                        $("#validMember").html("<span class='badge badge-danger'>Invalid ID</span>");
                    }

                    //location.reload();
                }
            });
        }
    </script>
    
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).ready(function() {
                $("#frmAdd").submit(function(event) {
                    if (!confirm('Are you sure that you want to submit the form'))
                        event.preventDefault();
                });
            });

        });
    </script>

    <!-- particles -->
    <script src="../web-assets/js/particles.min.js"></script>
    <script src="../web-assets/js/app.js"></script>
</body>
</html>