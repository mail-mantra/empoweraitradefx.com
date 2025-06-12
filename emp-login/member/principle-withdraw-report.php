<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'principle-withdraw-report';
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
                            <h2>Principal Withdraw Report</h2>
                            <p>Principal Withdraw List</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="table-panel">
                <div class="row">
                    <?php include('include/alert.php'); ?>
                    <?php
                    if (isset($_GET['cp'])) {
                        $cn = $_GET['cp'];
                    } else {
                        $cn = 1;
                    }
                    $uplim = 100;
                    $lowlim = $uplim * ($cn - 1);
                    $no = $lowlim;

                    $query = "SELECT m2.mem_code, m2.name, m2.mobile, m1.* FROM principle_withdraw_log m1 
                    INNER JOIN member m2 ON m1.member_id=m2.member_id
    			    WHERE m1.member_id='" . $user_id . "'";


                    $con = $db->connect();
                    $ss = mysqli_query($con, "$query order by m1.id desc");
                    $db->dbDisconnet($con);
                    $t = mysqli_num_rows($ss);
                    $tot_page = ceil($t / $uplim);
                    $con = $db->connect();
                    $sql = mysqli_query($con, "$query order by m1.id desc limit $lowlim,$uplim");
                    $db->dbDisconnet($con);
                    $nsql = mysqli_num_rows($sql);

                    if ($nsql > 0) {

                    ?>
                        <div class="col-lg-12">
                            <div class="title-2 mb-4">
                                Withdrawal <?php echo $period; ?>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-dark">
                                    <thead>
                                        <tr>
                                            <th>SL.</th>
                                            <th>Withdraw Date</th>
                                            <th>Withdraw Amount  (<?php echo CURRENCY_ICON; ?>)</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //$i=0;
                                        while ($r1 = mysqli_fetch_assoc($sql)) {
                                            $no++;
                                            $request_no = $r1['id'];
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?>.</td>
                                                <td><?php echo dmy($r1['created_on']); ?></td>
                                                <td>Code : <?php echo $r1['mem_code']; ?><br />Name: <?php echo $r1['name']; ?></td>
                                                <td><?php echo number_format($r1['net_amount'], 2); ?> </span></td>
                                                <td>
                                                    <?php if ($r1['paid_status'] == 1) { ?>
                                                        <span class="badge badge-success">Paid</span><br> on <?php echo (date('d-m-Y h:m:s A', strtotime($r1['paid_date']))); ?>

                                                    <?php } else if ($r1['paid_status'] == 2) { ?>
                                                        <span class="badge badge-danger">Reject</span><br> on <?php echo (date('d-m-Y  h:m:s A', strtotime($r1['paid_date']))); ?>

                                                    <?php } else if ($r1['paid_status'] == 3) { ?>
                                                        <span class="badge badge-danger">Cancelled</span><br> on <?php echo (date('d-m-Y  h:m:s A', strtotime($r1['paid_date']))); ?>

                                                    <?php } else { ?>
                                                        <span class="badge badge-warning">Pending</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <!--</form>-->

                                <ul class="pagination">
                                    <?php
                                    if ($cn != 1) {
                                        $prev = $cn - 1;
                                        $first = 1;
                                    ?>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $first; ?>" id="pagination" title="First Page"><?php echo "First"; ?></a></li>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $prev; ?>" id="pagination" title="Previous Page"><?php echo "Previous"; ?></a></li>
                                        <?php
                                    }
                                    $limit = ($tot_page - $cn);
                                    if ($cn > 5) {
                                        $st_page = $cn - 4;
                                        if ($limit < 4) {
                                            $en_page = $cn + $limit;
                                        } else {
                                            $en_page = $cn + 4;
                                        }
                                    } else {
                                        $st_page = 1;
                                        if ($limit > 4) {
                                            $en_page = $cn + 4;
                                        } else {
                                            $en_page = $cn + $limit;
                                        }
                                    }
                                    for ($i = $st_page; $i <= $en_page; $i++) {
                                        if ($cn != $i) {
                                        ?>
                                            <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $i; ?>" id="pagination" title="Page No. <?php echo "$i"; ?>"><?php
                                                                                                                                                                                                                                                                                                                                                                                echo

                                                                                                                                                                                                                                                                                                                                                                                "$i";
                                                                                                                                                                                                                                                                                                                                                                                ?></a></li>
                                        <?php
                                        } else {
                                        ?>
                                            <li class="page-item active"><a class="page-link" href="#"><?php echo "$i"; ?></a></li>
                                        <?php
                                        }
                                    }
                                    $next = $cn + 1;
                                    if ($tot_page >= $next) {
                                        ?>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $next; ?>" id="pagination" title="Next Page"><?php echo "Next"; ?></a></li>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $tot_page; ?>" id="pagination" title="Last Page"><?php echo "Last"; ?></a></li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
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
    <!--end-body-content-->

    <!--start-mm-footer-->
    <?php include('include/mm-footer.php'); ?>
    <!--end-mm-footer-->

    <!-- particles -->
    <script src="../web-assets/js/particles.min.js"></script>
    <script src="../web-assets/js/app.js"></script>

</body>
</html>