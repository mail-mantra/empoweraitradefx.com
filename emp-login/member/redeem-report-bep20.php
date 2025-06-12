<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'redeem-report-bep20';
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
                            <h2>Withdraw Report USDT(BEP20)</h2>
                            <p>Withdraw Report</p>
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

                    $query = "SELECT r.* from redeem_money_bep20 r inner join member_details m2 on r.member_id=m2.member_id where 1 and r.member_id='" . $user_id . "'";
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
                                            <th>Crypto Address</th>
                                            <th>USDT Amount</th>
                                            <th>USDT Amount</th>
                                            <th>Request On</th>
                                            <th>Wallet</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($r1 = mysqli_fetch_assoc($sql)) {
                                            $no++;
                                            $reject_reason = $r1['reject_reason'];
                                            $request_no = $r1['id'];
                                            $con = $db->connect();
                                            $q4 = mysqli_query($con, "SELECT hash FROM `api_send_response_bep20` where request_no='" . $request_no . "' order by id desc limit 0,1");
                                            $db->dbDisconnet($con);
                                            if ($q4->num_rows) {
                                                $r4 = mysqli_fetch_assoc($q4);
                                                $hash = $r4['hash'];
                                            } else {
                                                $hash = '';
                                            }
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?>.</td>
                                                <td><?php echo $r1['crypto_address']; ?>
                                                    <?php
                                                    if ($hash != '') {
                                                    ?><br>
                                                        <a href="https://bscscan.com/tx/<?php echo $hash ?>" target="_BLANK">Check Transaction</a>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    USDT Gross : <?php echo number_format($r1['usd_amount'], 2); ?><br />
                                                    Service Charge : <?php echo number_format($r1['usd_amount'] - $r1['usd_amount_net'], 2); ?><br />
                                                    USDT NET : <?php echo number_format($r1['usd_amount_net'], 2); ?>
                                                </td>
                                                <td>
                                                    USDT <?php echo number_format($r1['usdt_amount'], 2); ?>
                                                </td>
                                                <td>
                                                    <?php echo dmy($r1['created_on']); ?><br />
                                                    <?php echo ($r1['manual_status']) ? '<span class="badge badge-warning">Manual</span>' : ''; ?>
                                                </td>
                                                <td>
                                                    <?php echo ($r1['wallet_type']); ?>
                                                </td>
                                                <td>
                                                    <?php if ($r1['paid_status'] == 1) { ?>
                                                        <span class="badge badge-success">Paid</span><br> on <?php echo dmy($r1['paid_date']); ?>
                                                    <?php } else if ($r1['paid_status'] == 2) { ?>
                                                        <span class="badge badge-danger">Rejected</span><br> on <?php echo dmy($r1['paid_date']); ?><br />
                                                        <a href="#" data-toggle="modal" class="btn btn-secondary btn-sm" data-target="#myModal<?php echo $r1['id']; ?>">View Reason</a>
                                                        <!-- Modal -->
                                                        <div id="myModal<?php echo $r1['id']; ?>" class="modal fade" role="dialog">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Reason For Rejection</h4>
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
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
                                                </td>
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