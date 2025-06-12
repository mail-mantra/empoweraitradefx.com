<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'report-ba-transaction';

$table = 'ba_wallet_transaction';

if (isset($_REQUEST['start_date']) && isset($_REQUEST['end_date'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $start_date = ymd($_REQUEST['start_date']);
        $end_date = ymd($_REQUEST['end_date']);
    } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $start_date = ymd(base64_decode($_REQUEST['start_date']));
        $end_date = ymd(base64_decode($_REQUEST['end_date']));
    }
} else {
    $start_date = $first_day_this_month;
    $end_date = $current_date;
}

$period = dmy($start_date) . " To " . dmy($end_date);

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
                            <h2>BA Wallet</h2>
                            <p>BA Wallet History</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="row">
                    <div class="col-lg-12">

                        <form id="frmAdd" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                            <div class="row">
                                <div class="form-group col-md-4 col-sm-6">
                                    <label>From Date</label>
                                    <input type="date" name="start_date" value="<?php if (isset($_REQUEST['start_date'])) {
                                                                                    echo $start_date;
                                                                                } ?>" class="dp-1 datepicker form-control" placeholder="From Date" />
                                </div>
                                <div class="form-group col-md-4 col-sm-6">
                                    <label>To date</label>
                                    <input type="date" name="end_date" value="<?php if (isset($_REQUEST['end_date'])) {
                                                                                    echo $end_date;
                                                                                } ?>" class="dp-1 datepicker form-control" placeholder="To Date" />
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" name="submit" id="submit" class="btn btn-danger col-md-2 col-sm-3">Search</button>
                                </div>
                            </div>
                        </form>
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

                    if (isset($_REQUEST['start_date']) && ($_REQUEST['end_date'])) {
                        $search_with_date_range = " AND (`txn_date` BETWEEN '" . $start_date . "' AND '" . $end_date . "') ";
                    } else {
                        $search_with_date_range = "";
                    }

                    $query = "select * from $table where member_id='" . $user_id . "' " . $search_with_date_range;

                    $con = $db->connect();
                    $ss = mysqli_query($con, "$query order by id desc");
                    $db->dbDisconnet($con);
                    $t = mysqli_num_rows($ss);
                    $tot_page = ceil($t / $uplim);
                    $con = $db->connect();
                    $sql = mysqli_query($con, "$query order by id desc limit $lowlim,$uplim");
                    $db->dbDisconnet($con);
                    $nsql = mysqli_num_rows($sql);
                    if ($nsql > 0) {
                    ?>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-dark">
                                    <thead>
                                        <tr>
                                            <th>SL.</th>
                                            <th>Date</th>
                                            <th>Particulars</th>
                                            <th>Credit</th>
                                            <th>Debit</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //$tot_gross = 0;
                                        while ($r1 = mysqli_fetch_assoc($sql)) {
                                            $no++;
                                            //$tot_gross += $r1['balance'];
                                            $particulars = str_replace('_', ' ', $r1['particulars']);
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?>.</td>
                                                <td><?php echo dmy($r1['txn_date']); ?></td>
                                                <td><?php echo $particulars; ?></td>
                                                <td><?php echo number_format($r1['credit'], 6); ?></td>
                                                <td><?php echo number_format($r1['debit'], 6); ?></td>
                                                <td><?php echo number_format($r1['balance'], 6); ?></td>
                                            </tr>
                                        <?php
                                        }
                                        /*$all_total_gross = 0;
                                        while ($r2 = mysqli_fetch_assoc($ss)) {
                                            $all_total_gross += $r2['balance'];
                                        }*/
                                        ?>
                                        <!--<tr>
                            	<td colspan="5" align="right"><strong>Total (This Page)</strong></td>
                                <td><strong><?php echo number_format($tot_gross, 2); ?></strong></td>
                            </tr>
                            <tr>
                            	<td colspan="5" align="right"><strong>Total Gross (All)</strong></td>
                                <td><strong><?php echo number_format($all_total_gross, 2); ?></strong></td>
                            </tr>-->
                                    </tbody>
                                </table>


                                <ul class="pagination">
                                    <?php
                                    if ($cn != 1) {
                                        $prev = $cn - 1;
                                        $first = 1;
                                    ?>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $first; ?>&start_date=<?php echo base64_encode($start_date); ?>&end_date=<?php echo base64_encode($end_date); ?>" id="pagination" title="First Page"><?php echo "First"; ?></a></li>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $prev; ?>&start_date=<?php echo base64_encode($start_date); ?>&end_date=<?php echo base64_encode($end_date); ?>" id="pagination" title="Previous Page"><?php echo "Previous"; ?></a></li>
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
                                            <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $i; ?>&start_date=<?php echo base64_encode($start_date); ?>&end_date=<?php echo base64_encode($end_date); ?>" id="pagination" title="Page No. <?php echo "$i"; ?>"><?php
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
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $next; ?>&start_date=<?php echo base64_encode($start_date); ?>&end_date=<?php echo base64_encode($end_date); ?>" id="pagination" title="Next Page"><?php echo "Next"; ?></a></li>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $tot_page; ?>&start_date=<?php echo base64_encode($start_date); ?>&end_date=<?php echo base64_encode($end_date); ?>" id="pagination" title="Last Page"><?php echo "Last"; ?></a></li>
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