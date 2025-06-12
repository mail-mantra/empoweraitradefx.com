<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'view-myfund-request';
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
                            <h2>Balance Deposit</h2>
                            <p>Balance Request</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title-2 mb-4">
                            <a href="myfund-request" class="btn btn-sm btn-info">New Request</a>
                        </div>
                    </div>
                </div>

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

                    $query = "select * from myfund_request where member_id='" . $user_id . "' order by id desc";

                    $con = $db->connect();
                    $ss = mysqli_query($con, $query);
                    $db->dbDisconnet($con);
                    $t = mysqli_num_rows($ss);
                    $tot_page = ceil($t / $uplim);
                    $con = $db->connect();
                    $sql = mysqli_query($con, "$query limit $lowlim,$uplim");
                    $db->dbDisconnet($con);
                    $nsql = mysqli_num_rows($sql);
                    if ($nsql > 0) {
                    ?>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-dark">
                                    <thead>
                                        <tr>
                                            <th>Sl.</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Payment Details</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($r1 = mysqli_fetch_assoc($sql)) {
                                            $no++;

                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?>.</td>
                                                <td>
                                                    INR <?php echo $r1['amount']; ?><br />
                                                    USD <?php echo number_format($r1['mf_amount'], 2); ?>
                                                </td>
                                                <td><?php echo dmy($r1['created_on']); ?></td>
                                                <td>
                                                    <a href="#" data-toggle="modal" data-target="#myModal<?php echo $r1['txn_id']; ?>" class="btn btn-sm btn-info">
                                                        <i class="fa fa-search"></i> View
                                                    </a>
                                                    <!-- Modal -->
                                                    <div id="myModal<?php echo $r1['txn_id']; ?>" class="modal fade" role="dialog">
                                                        <div class="modal-dialog  modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Payment Details</h5>
                                                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <?php include('../ajax/payment-details.php'); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if ($r1['paid_status'] == 0) { ?>
                                                        <span class="badge badge-warning"><?php echo $r1['request_status']; ?></span><br>
                                                    <?php } else if ($r1['paid_status'] == 1) { ?>
                                                        <span class="badge badge-success"><?php echo $r1['request_status']; ?></span><br> on <?php echo dmy($r1['modified_on']); ?>
                                                    <?php } else if ($r1['paid_status'] == 2) { ?>
                                                        <span class="badge badge-danger"><?php echo $r1['request_status']; ?></span><br> on <?php echo dmy($r1['modified_on']); ?>
                                                    <?php } else {
                                                        echo '';
                                                    }  ?>
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