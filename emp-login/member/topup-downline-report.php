<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'topup-downline-report';


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
                            <h2>Downline Community Trade Investment Report</h2>
                            <p>Downline Investment List</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="row">
                    <?php
                    $query = "SELECT m1.*, m2.mem_code, m2.name FROM member_package_update_log m1 inner join member m2 on m1.member_id=m2.member_id
                    WHERE m1.package_id=1 and m1.created_by='" . $user_code . "' and m1.member_id!='" . $user_id . "' order by m1.id desc";
                    
                    $con = $db->connect();
                    $sql = mysqli_query($con, $query);
                    $db->dbDisconnet($con);
                    $nsql = mysqli_num_rows($sql);
                    if ($nsql > 0) {
                    ?>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th>SL.</th>
                                            <th>Member Code</th>
                                            <th>Name</th>
                                            <th>Date / Narration</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($r1 = mysqli_fetch_assoc($sql)) {
                                            $no++;
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?>.</td>
                                                <td><?php echo $r1['mem_code']; ?></td>
                                                <td><?php echo $r1['name']; ?></td>
                                                <td>
                                                    <?php echo dmy($r1['created_on']); ?><br />
                                                    Narration : <br />
                                                    <?php echo $r1['narration']; ?>
                                                </td>
                                                <td><?php echo CURRENCY_ICON . number_format($r1['actual_amount'], 2); ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
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