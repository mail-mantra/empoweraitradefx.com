<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'member-monthly-rank-report';

if (isset($_REQUEST['start_date']) && isset($_REQUEST['end_date'])  && ($_REQUEST['start_date'] != '')  && ($_REQUEST['end_date'] != '')) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $start_date = ymd($_REQUEST['start_date']);
        $end_date = ymd($_REQUEST['end_date']);
    } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $start_date = ymd(($_REQUEST['start_date']));
        $end_date = ymd(($_REQUEST['end_date']));
    }

    $period = "From " . dmy($start_date) . " To " . dmy($end_date);

    $search_with_date_range = " AND (m1.`rank_date` BETWEEN '" . $start_date . "' AND '" . $end_date . "') ";
} else {
    $start_date = '';
    $end_date = '';
    $period = 'All';
    $search_with_date_range = '';
}
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
                            <h2>Monthly Rank Report</h2>
                            <p>Rank Report</p>
                        </div>
                    </div>
                </div>
            </div>
            

            <div class="table-panel">
                <div class="col-lg-12">

                    <form id="frmAdd" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                        <div class="row">
                            <div class="form-group col-md-4 col-sm-6">
                                <label>From Date</label>
                                <input type="date" name="start_date" value="<?php if (isset($_REQUEST['start_date'])) {
                                                                                echo $start_date;
                                                                            } ?>" class="form-control" />
                            </div>
                            <div class="form-group col-md-4 col-sm-6">
                                <label>To Date</label>
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

                    $query = "SELECT m1.* FROM salary_eligibility_monthly m1 inner join member_login m2 on m1.member_id = m2.member_id WHERE m1.member_id = '" . $user_id . "' " . $search_with_date_range;

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
                                            <th>Date</th>
                                            <th>Rank</th>
                                            <th>Amount</th>
                                            <!--<th>capping</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($r1 = mysqli_fetch_assoc($sql)) {
                                            $no++;
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?>.</td>
                                                <td><?php echo dmy($r1['rank_date']); ?></td>
                                                <td><?php echo str_replace('_', ' ', $r1['rank']); ?></td>
                                                <td><?php echo number_format($r1['income_amount'], 2); ?></td>
                                                <?php /* ?><td><?php echo $r1['capping']; ?></td><?php */ ?>
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
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $first; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>" id="pagination" title="First Page"><?php echo "First"; ?></a></li>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $prev; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>" id="pagination" title="Previous Page"><?php echo "Previous"; ?></a></li>
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
                                            <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $i; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>" id="pagination" title="Page No. <?php echo "$i"; ?>"><?php
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
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $next; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo base64_encode(); ?>" id="pagination" title="Next Page"><?php echo "Next"; ?></a></li>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $tot_page; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo base64_encode(); ?>" id="pagination" title="Last Page"><?php echo "Last"; ?></a></li>
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
<script src="web-assets/js/particles.min.js"></script>
<script src="web-assets/js/app.js"></script>

</body>
</html>