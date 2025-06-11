<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'topup-deleted-report';


if (isset($_REQUEST['mem_code'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $mem_code = trim($_REQUEST['mem_code']);
    } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $mem_code = (trim($_REQUEST['mem_code']));
    }
} else {

    $mem_code = '';
}

if (isset($_REQUEST['start_date']) && isset($_REQUEST['end_date'])  && ($_REQUEST['start_date'] != '')  && ($_REQUEST['end_date'] != '')) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $start_date = ymd($_REQUEST['start_date']);
        $end_date = ymd($_REQUEST['end_date']);
    } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $start_date = ymd(($_REQUEST['start_date']));
        $end_date = ymd(($_REQUEST['end_date']));
    }

    $period = "From " . dmy($start_date) . " To " . dmy($end_date);
} else {
    $start_date = '';
    $end_date = '';
    $period = 'All';
}


$search_joining_date = '';

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
                            <h2>Principle Withdraw Investment Report</h2>
                            <p>Investment List who have done Principle Withdraw</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="col-lg-12">

                    <?php include('include/alert.php'); ?>

                    <form id="frmAdd" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                        <div class="row">
                            <div class="form-group col-md-4 col-sm-6">
                                <label>Member Code</label>
                                <input type="text" name="mem_code" value="<?php if (isset($_REQUEST['mem_code'])) {
                                                                                echo $mem_code;
                                                                            } ?>" class="form-control" />
                            </div>
                            <div class="form-group col-md-4 col-sm-6">
                                <label>From Withdraw Date</label>
                                <input type="date" name="start_date" value="<?php if (isset($_REQUEST['start_date'])) {
                                                                                echo $start_date;
                                                                            } ?>" class="form-control" />
                            </div>
                            <div class="form-group col-md-4 col-sm-6">
                                <label>To Withdraw Date</label>
                                <input type="date" name="end_date" value="<?php if (isset($_REQUEST['end_date'])) {
                                                                                echo $end_date;
                                                                            } ?>" class="form-control" />
                            </div>

                            <div class="col-md-12">
                                <button type="submit" name="submit" id="submit" class="btn btn-primary col-md-2 col-sm-3">Search</button>
                            </div>
                        </div>
                    </form>
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

                    if (!empty($mem_code)) {
                        $sql_member = " AND m2.mem_code='" . $mem_code . "'";
                    } else {
                        $sql_member = '';
                    }

                    if (!empty($start_date) && !empty($end_date)) {
                        $search_with_date_range = " AND (DATE(`m1`.`created_on`) BETWEEN '" . $start_date . "' AND '" . $end_date . "') ";
                    } else {
                        $search_with_date_range = "";
                    }

                    $query = "SELECT m2.mem_code, m2.name, m1.*, m3.package_name FROM deleted_member_package_update_log m1 
                    INNER JOIN member m2 ON m1.member_id=m2.member_id
                    INNER JOIN join_package m3 ON m1.package_id=m3.id
			        WHERE 1 " . $sql_member . $search_with_date_range;

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
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-dark">
                                    <thead>
                                        <tr>
                                            <th>SL.</th>
                                            <th>Withdraw Date</th>
                                            <th>Member Details</th>
                                            <th>Package</th>
                                            <th>Amount (<?php echo CURRENCY_ICON; ?>)</th>
                                            <th>Topup Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //$i=0;
                                        $page_tot = 0;
                                        while ($r1 = mysqli_fetch_assoc($sql)) {
                                            $no++;
                                            $page_tot += $r1['actual_amount'];

                                            $topup_id = $r1['id'];
                                            $topup_date = $r1['created_on'];

                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?>.</td>
                                                <td><?php echo dmy($r1['deleted_on']); ?><br />By : <?php echo $r1['deleted_by']; ?></td>
                                                <td>Code : <?php echo $r1['mem_code']; ?><br />Name : <?php echo $r1['name']; ?></td>
                                                <td>
                                                    <?php echo $r1['package_name']; ?><br />
                                                    <?php echo ($r1['roi_status']==1 ? '<span class="badge badge-primary">ROI</span>' : '<span class="badge badge-danger">Non-ROI</span>'); ?>
                                                </td>
                                                <td><?php echo $r1['actual_amount']; ?></td>
                                                <td><?php echo dmy($r1['created_on']); ?><br />By : <?php echo $r1['created_by']; ?></td>
                                            </tr>
                                        <?php
                                        }
                                        $all_tot = 0;
                                        while ($r2 = mysqli_fetch_assoc($ss)) {
                                            $all_tot += $r2['actual_amount'];
                                        }
                                        ?>
                                        <tr style="font-weight: bold">
                                            <td colspan="4" class="text-right">Page Total</td>
                                            <td colspan="2"><?php echo CURRENCY_ICON . number_format($page_tot); ?></td>
                                        </tr>
                                        <tr style="font-weight: bold">
                                            <td colspan="4" class="text-right">All Total</td>
                                            <td colspan="2"><?php echo CURRENCY_ICON . number_format($all_tot); ?></td>
                                        </tr>
                                    </tbody>
                                </table>


                                <ul class="pagination">
                                    <?php
                                    if ($cn != 1) {
                                        $prev = $cn - 1;
                                        $first = 1;
                                    ?>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $first; ?>&mem_code=<?php echo ($mem_code); ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>&pay_type=<?php echo ($pay_type); ?>" id="pagination" title="First Page"><?php echo "First"; ?></a></li>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $prev; ?>&mem_code=<?php echo ($mem_code); ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>&pay_type=<?php echo ($pay_type); ?>" id="pagination" title="Previous Page"><?php echo "Previous"; ?></a></li>
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
                                            <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $i; ?>&mem_code=<?php echo ($mem_code); ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>&pay_type=<?php echo ($pay_type); ?>" id="pagination" title="Page No. <?php echo "$i"; ?>"><?php
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
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $next; ?>&mem_code=<?php echo ($mem_code); ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo $end_date; ?>&pay_type=<?php echo ($pay_type); ?>" id="pagination" title="Next Page"><?php echo "Next"; ?></a></li>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $tot_page; ?>&mem_code=<?php echo ($mem_code); ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo $end_date; ?>&pay_type=<?php echo ($pay_type); ?>" id="pagination" title="Last Page"><?php echo "Last"; ?></a></li>
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