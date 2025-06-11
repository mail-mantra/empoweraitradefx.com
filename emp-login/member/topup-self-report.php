<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'topup-self-report';

/* 
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

$period = dmy($start_date) . " To " . dmy($end_date); */

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
                            <h2>Community Trade Investment Report</h2>
                            <p>Investment Report</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="table-panel">
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
            </div> -->

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

                    /* if (isset($_REQUEST['start_date']) && ($_REQUEST['end_date'])) {
                        $search_with_date_range = " AND (`txn_date` BETWEEN '" . $start_date . "' AND '" . $end_date . "') ";
                    } else {
                        $search_with_date_range = "";
                    } */

                    $query = "SELECT * FROM `member_package_update_log` WHERE `member_id`='" . $user_id . "' and package_id=1 order by id desc";


                    $con = $db->connect();
                    $res_view = $con->query($query);
                    $db->dbDisconnet($con);
                    $result["sql"] = $res_view;
                    if ($res_view->num_rows) {
                    ?>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-dark">
                                    <thead>
                                        <tr>
                                            <th>SL.</th>
                                            <th>Date</th>
                                            <th>Amount (<?php echo CURRENCY_ICON; ?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $page_tot = 0;
                                        while ($r1 = $res_view->fetch_assoc()) {
                                            $page_tot += $r1['actual_amount'];
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?>.</td>
                                                <td><?php echo dmy($r1['created_on']); ?></td>
                                                <td><?php echo $r1['actual_amount']; ?></td>
                                            </tr>
                                        <?php
                                            $no++;
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