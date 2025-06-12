<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'redeem-report';
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

    <!--start-mm-top-header-->
    <?php include('include/mm-top-header.php'); ?>
    <!--end-mm-top-header-->

    <!--start-body-content-->
    <div class="body-content">
        <div class="container">
            <div class="col-lg-12">
                <div class="row">
                    <div class="dashboard-title">
                        <div class="icon">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                        </div>
                        <div class="caption">
                            <h2>Withdraw Report</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="row">
                    <?php
                    if (isset($_GET['cp'])) {
                        $cn = $_GET['cp'];
                    } else {
                        $cn = 1;
                    }
                    $uplim = 100;
                    $lowlim = $uplim * ($cn - 1);
                    $no = $lowlim;

                    $query = "SELECT m1.*,m2.bnk_nm,m2.brnch_nm,m2.acc_nm,m2.acc_no,m2.acc_type,m2.ifsc,m2.pan_no,r.* from member m1 inner join member_details m2 on m1.member_id=m2.member_id inner join redeem_money r on m1.member_id=r.member_id where r.member_id='" . $user_id . "'";
                    //echo $query;
                    $con = $db->connect();
                    $ss = mysqli_query($con, "$query order by r.id desc");
                    $db->dbDisconnet($con);
                    $t = mysqli_num_rows($ss);
                    $tot_page = ceil($t / $uplim);
                    $con = $db->connect();
                    $sql = mysqli_query($con, "$query order by r.id desc limit $lowlim,$uplim");
                    $db->dbDisconnet($con);
                    $nsql = mysqli_num_rows($sql);
                    if ($nsql > 0) {
                    ?>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-custom table-hover">
                                    <thead>
                                        <tr>
                                            <th>SL.</th>
                                            <th>Amount</th>
                                            <th>Wallet</th>
                                            <th>Bank Details</th>
                                            <th>Request On</th>
                                            <th>Paid</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($r1 = mysqli_fetch_assoc($sql)) {
                                            $no++;
                                            $reject_reason = $r1['reject_reason'];
                                            $bank_details = 'Bank : ' . $r1['bnk_nm'] . '<br>Branch : ' . $r1['brnch_nm'] . '<br>A/c Name : ' . $r1['acc_nm'] . '<br>A/c No. : ' . $r1['acc_no'] . '<br>Type : ' . $r1['acc_type'] . '<br>IFSC : ' . $r1['ifsc'] . '<br>PAN No. : ' . $r1['pan_no'];
                                            $amount = $r1['net_amount'];
                                            //$rate = $r1['wallet_type'] == 'working_wallet' ? 82 : 85;
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?>.</td>
                                                <td>
                                                    <?php echo CURRENCY_ICON . number_format($amount, 2); ?><br />
                                                    / </td>
                                                <td><?php echo $r1['wallet_type']; ?></td>
                                                <td><?php echo $bank_details; ?></td>
                                                <td><?php echo dmy($r1['created_on']); ?></td>
                                                <td>
                                                    <?php if ($r1['paid_status'] == 1) { ?>
                                                        <span class="badge badge-success">Paid</span><br> on <?php echo dmy($r1['paid_date']); ?>
                                                    <?php } else if ($r1['paid_status'] == 2) { ?>
                                                        <span class="badge badge-danger">Rejected</span><br> on <?php echo dmy($r1['paid_date']); ?>
                                                        <a href="#" data-toggle="modal" data-target="#myModal<?php echo $r1['id']; ?>"><i class="fa fa-search"></i></a>
                                                        <!-- Modal -->
                                                        <div id="myModal<?php echo $r1['id']; ?>" class="modal fade" role="dialog">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Reason For Rejection</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p><?php echo $reject_reason; ?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    } else { ?>
                                                        <span class="badge badge-warning">Pending</span><br>
                                                    <?php } ?>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>

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