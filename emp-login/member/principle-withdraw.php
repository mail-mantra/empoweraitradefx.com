<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$open = false;
$query = "SELECT `value` FROM `settings` WHERE `key` = 'principle_withdraw'";
$con = $db->connect();
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);
if ($row && $row['value']) {
    $open = true;
}
$db->dbDisconnet($con);
$page_name = 'principle-withdraw';
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
                            <h2>Principal Withdraw</h2>
                            <p>Investment List</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="row">
                    <?php
                    if ($open) {
                        $query = "SELECT m1.*, m3.package_name FROM member_package_update_log m1 
                        INNER JOIN join_package m3 ON m1.package_id=m3.id
                        WHERE m1.member_id='" . $user_id . "' and m1.amount > 0 order by m1.id desc";
                        $con = $db->connect();
                        $sql = mysqli_query($con, $query);
                        $db->dbDisconnet($con);
                        $nsql = mysqli_num_rows($sql);
                        if ($nsql > 0) {
                            $no = 0;
                    ?>
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-custom table-hover">
                                        <thead>
                                            <tr>
                                                <th>SL.</th>
                                                <th>Date / Narration</th>
                                                <th>Package</th>
                                                <th>Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($r1 = mysqli_fetch_assoc($sql)) {
                                                $no++;
                                                $topup_id = $r1['id'];
                                            ?>
                                                <tr>
                                                    <td><?php echo $no; ?>.</td>
                                                    <td>
                                                        <?php echo dmy($r1['created_on']); ?><br />
                                                        Narration : <br />
                                                        <?php echo $r1['narration']; ?>
                                                    </td>
                                                    <td><?php echo $r1['package_name']; ?></td>
                                                    <td><?php echo CURRENCY_ICON . number_format($r1['actual_amount'], 2); ?></td>
                                                    <td><a href="principle-withdraw-code?id=<?php echo hash_encode($topup_id); ?>&action=pay" class="btn btn-sm btn-primary">Withdraw</a></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                    <?php
                        } else {
                            echo '<div class="col-lg-12"><div class="alert alert-info"  role="alert">No records found...!</div></div>';
                        }
                    } else {
                        echo '<div class="col-lg-12"><div class="alert alert-danger"  role="alert">Principal Withdraw is closed...!</div></div>';
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

    <!-- particles -->
    <script src="../web-assets/js/particles.min.js"></script>
    <script src="../web-assets/js/app.js"></script>

</body>

</html>