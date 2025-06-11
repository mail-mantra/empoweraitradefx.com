<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$con = $db->connect();
$myfund_wallet = get_wallet_balance_of_member($con, $user_id, 'myfund_wallet_balance');
$qpk = mysqli_query($con, "select * from member_package_update_log where member_id='" . $user_id . "' and DATEDIFF('$current_date', DATE(created_on))>=15 order by id asc limit 1");
$npk = mysqli_num_rows($qpk);
/*$rpk = mysqli_fetch_assoc($qpk);
$user_last_package_id = $rpk['package_id'];
$user_last_package_amount = $rpk['amount'];
$ba_wallet_balance = get_wallet_balance_of_member($con, $user_id, 'ba_wallet_balance');*/
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
                            <h2>Premium Signal Plan Investment</h2>
                            <p>Invest Yourself</p>
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
                    
                    <?php if($npk>=1){ ?>
                    
                        <div class="col-lg-12">
                            
                            <?php include('include/alert.php'); ?>
    
                            <form id="frmAdd" action="topup-signalc" method="post">
                                <div class="row">
                                    <div class="form-group col-md-4 col-sm-6">
                                        <label>Plan*</label>
                                        <select class="form-control" name="package_id" required>
                                            <option value="">Select</option>
                                            <?php
                                            $query = "select * from join_package_signal where status=1";
                                            $con=$db->connect();
                                            $q = mysqli_query($con, $query);
                                            $db->dbDisconnet($con);
                                            while($r = mysqli_fetch_assoc($q)){
                                            ?>
                                            <option value="<?php echo $r['id']; ?>"><?php echo $r['package_name'].' - '. $r['package_amount']. ' '.CURRENCY_NAME; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group col-md-6 col-sm-6">
                                        <label>Your Telegram ID*</label>
                                        <input type="text" name="tg_link" id="tg_link" class="form-control" data-rule-required="true" placeholder="Enter Your Telegram Link" />
                                    </div>
    
                                    <div class="col-md-12">
                                        <button type="submit" name="submit" id="submit" value="Submit" class="btn btn-sm btn-danger col-md-1 col-sm-3">Submit</button>
                                    </div>
                                </div>
                            </form>
                            
                        </div>
                    <?php
                    } else {
                        echo '<div class="col-lg-12"><div class="alert alert-info" role="alert">Wait for 15days from your date of activation to avail this plan..</div></div>';
                    }
                    ?>
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