<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'live_account_link_request';

if (!function_exists('in_page_request_status_text')) {
    function in_page_request_status_text($input)
    {
        if ($input === '0') {
            return '<span class="badge badge-info">Requested</span>';
        } elseif ($input === '1') {
            return '<span class="badge badge-success">Accepted</span>';
        } elseif ($input === '2') {
            return '<span class="badge badge-warning">Rejected</span>';
        } else {
            return $input;
        }
    }
}
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
    <!-- <div class="page-loader">
<div class="loader"></div>
</div> -->
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
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </div>
                        <div class="caption">
                            <h2>View Live Account Link Request</h2>
                            <p>View all Live Account Link Request</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="row">

                    <!-- show -->
                    <!-- compain list -->
                    <?php
                    if (isset($_GET['cp'])) {
                        $cn = $_GET['cp'];
                    } else {
                        $cn = 1;
                    }
                    $uplim = 50;
                    $lowlim = $uplim * ($cn - 1);
                    $no = $lowlim;

                    $query = "SELECT * FROM `live_account_link_request` WHERE `member_id`= '$user_id' ORDER BY id DESC";

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
                                <table class="table table-bordered table-custom table-hover">
                                    <tr>
                                        <th>SL.</th>
                                        <th>Created</th>
                                        <th>Mail Id</th>
                                        <th>Password</th>
                                        <th>Investment</th>
                                        <th>Mobile Number</th>
                                        <th>Broker Name</th>
                                        <th>Status</th>
                                    </tr>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        while ($r1 = mysqli_fetch_assoc($sql)) {
                                            $no++;
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?>.</td>
                                                <td><?php echo dmy(($r1['created_at'])); ?></td>
                                                <td><?php echo $r1['octa_mail_id']; ?></td>
                                                <td><?php echo $r1['octa_password']; ?></td>
                                                <td><?php echo $r1['investment']; ?></td>
                                                <td><?php echo $r1['mobile_no']; ?></td>
                                                <td><?php echo $r1['broker_name']; ?></td>
                                                <td>
                                                    <?= in_page_request_status_text($r1['request_status']); ?>
                                                    <?= ($r1['request_status'] === '2') ? ("<p>" . $r1['update_comment'] . "</p>") : ''; ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="col-md-12 text-xs-center">

                            <ul class="pagination m-0 justify-content-center">
                                <?php

                                $get = $_GET;
                                if (isset($get['cp'])) unset($get['cp']);

                                if ($cn != 1) {
                                    $prev = $cn - 1;
                                    $first = 1;
                                ?>
                                    <li class="page-item ">
                                        <a class="page-link"
                                            href="<?= $page_name; ?>?cp=<?php echo $first; ?><?php echo "&" . http_build_query($get); ?>"
                                            title="First Page"><?php echo "<<"; ?></a>
                                    </li>
                                    <li class="page-item ">
                                        <a class="page-link"
                                            href="<?= $page_name; ?>?cp=<?php echo $prev; ?><?php echo "&" . http_build_query($get); ?>"
                                            title="Previous Page"><?php echo "<"; ?></a>
                                    </li>
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
                                        <li class="page-item ">
                                            <a class="page-link"
                                                href="<?= $page_name; ?>?cp=<?php echo $i; ?><?php echo "&" . http_build_query($get); ?>"
                                                title="Page No. <?php echo $i; ?>"><?php echo "$i"; ?></a>
                                        </li>
                                    <?php
                                    } else {
                                    ?>
                                        <li class="page-item active"><span class="page-link"><?php echo $i; ?><span
                                                    class="sr-only">(current)</span></span></li>
                                    <?php
                                    }
                                }
                                $next = $cn + 1;
                                if ($tot_page >= $next) {
                                    ?>
                                    <li class="page-item ">
                                        <a class="page-link"
                                            href="<?= $page_name; ?>?cp=<?php echo $next; ?><?php echo "&" . http_build_query($get); ?>"
                                            title="Next Page"><?php echo ">"; ?></a>
                                    </li>
                                    <li class="page-item ">
                                        <a class="page-link"
                                            href="<?= $page_name; ?>?cp=<?php echo $tot_page; ?><?php echo "&" . http_build_query($get); ?>"
                                            title="Last Page"><?php echo ">>"; ?></a>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
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