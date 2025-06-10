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
                            <h2>List of Direct Members</h2>
                            <p>Direct ID List</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        $query = "SELECT `member_id`, `mem_code`, `doj`, `name` FROM `member` WHERE `intro_id` = '" . $user_id . "' ORDER BY `doj`";
                        $con = $db->connect();
                        $sql = mysqli_query($con, $query);
                        $db->dbDisconnet($con);
                        $nsql = mysqli_num_rows($sql);
                        if ($nsql > 0) {
                        ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-custom table-hover">
                                    <tr>
                                        <th>SL.</th>
                                        <th>Member Code</th>
                                        <th>Name</th>
                                        <th>Date Of Joining</th>
                                        <th>Status</th>
                                        <th>Investments</th>
                                        <th>Total Business</th>
                                    </tr>
                                    <tbody>
                                        <?php
                                        $con = $db->connect();
                                        $no = 0;
                                        while ($r1 = mysqli_fetch_assoc($sql)) {
                                            $no++;
                                            $member_id = $r1['member_id'];

                                            if (member_is_active($con, $member_id) > 0) {
                                                $topup_date = '<br>' . dmy_time(current_package_date_of_member($con, $member_id));
                                            } else {
                                                $topup_date = '';
                                            }
                                            
                                            $qb = mysqli_query($con,"select INTROWISE_SELF_BUSINESS('$member_id','2021-01-01','$current_date') as total_business");
                                            $rb = mysqli_fetch_assoc($qb);
                                            $total_business = $rb['total_business'];
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?>.</td>
                                                <td><?php echo $r1['mem_code']; ?></td>
                                                <td><?php echo $r1['name']; ?></td>
                                                <td><?php echo dmy($r1['doj']); ?></td>
                                                <td><?php echo active_inactive_label_show($con, $member_id) . $topup_date; ?></td>
                                                <td>
                                                    <?php
                                                    $query_g = "SELECT `amount` FROM `member_package_update_log` WHERE `member_id` = '" . $member_id . "'";
                                                    $sql_g = mysqli_query($con, $query_g);
                                                    $nsql_g = mysqli_num_rows($sql_g);
                                                    if ($nsql_g > 0) {
                                                        while ($r_g = mysqli_fetch_assoc($sql_g)) {
                                                    ?>
                                                            <span class="badge badge-warning" style="border: 1px solid orange"> <?php echo $r_g['amount'] ?></span>
                                                    <?php
                                                        }
                                                    }
                                                    else{ echo '-'; }
                                                    ?>
                                                </td>
                                                <td><?php echo CURRENCY_ICON.$total_business; ?></td>
                                            </tr>
                                        <?php
                                        }
                                        $db->dbDisconnet($con);
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                        } else {
                            echo '<div class="col-lg-12"><div class="alert alert-info"  role="alert">No records found...!</div></div>';
                        }
                        ?>
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

</body>

</html>