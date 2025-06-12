<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'view-copy-trade-request';
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
                            <h2>Copy Trade Request List</h2>
                            <p>Report</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title-2 mb-4">
                            <a href="copy-trade-request" class="btn btn-sm btn-info">New Request</a>
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

                    $query = "SELECT * FROM copy_trade_request WHERE member_id='" . $user_id . "' ORDER BY id DESC";

                    $con = $db->connect();
                    $ss = mysqli_query($con, $query);
                    $db->dbDisconnet($con);
                    $t = mysqli_num_rows($ss);
                    $tot_page = ceil($t / $uplim);
                    $con = $db->connect();
                    $sql = mysqli_query($con, "$query LIMIT $lowlim,$uplim");
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
                                            <th>Details</th>
                                            <th>Investment Amount</th>
                                            <th>Date</th>
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
                                                    Mobile: <?php echo $r1['mobile']; ?><br>
                                                    Email: <?php echo $r1['email']; ?><br>
                                                    Broker Name: <?php echo $r1['broker_name']; ?><br>
                                                    MT5 ID: <?php echo $r1['mt5_id']; ?><br>
                                                    MT5 Password: <?php echo $r1['mt5_password']; ?>
                                                </td>
                                                <td><?php echo $r1['amount']; ?></td>
                                                <td><?php echo dmy($r1['created_on']); ?></td>
                                                <td>
                                                    <?php if ($r1['status'] == 0) { ?>
                                                        <span class="badge badge-warning">Pending</span>
                                                    <?php } else if ($r1['status'] == 1) { ?>
                                                        <span class="badge badge-success">Approved</span>
                                                    <?php } else if ($r1['status'] == 2) { ?>
                                                        <span class="badge badge-danger">Rejected</span>
                                                        <?php if (!empty($r1['reject_reason'])) { ?>
                                                            <span class="text-danger"><br />Reason: <?php echo $r1['reject_reason']; ?></span>
                                                        <?php } ?>
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
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $first; ?>" id="pagination" title="First Page">First</a></li>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $prev; ?>" id="pagination" title="Previous Page">Previous</a></li>
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
                                            <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $i; ?>" id="pagination" title="Page No. <?php echo "$i"; ?>"><?php echo "$i"; ?></a></li>
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
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $next; ?>" id="pagination" title="Next Page">Next</a></li>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $tot_page; ?>" id="pagination" title="Last Page">Last</a></li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>

                    <?php
                    } else {
                        echo '<div class="col-lg-12"><div class="alert alert-info" role="alert">No records found...!</div></div>';
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