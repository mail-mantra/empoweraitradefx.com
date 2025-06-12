<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');

$db = new Database();
$page_name = $_SERVER['PHP_SELF'];

$user_id = $_SESSION['user_data']['user_id'];
$user_code = $_SESSION['user_data']['user_code'];

$query = "SELECT a.* FROM `refunds` a WHERE a.`member_id` = '$user_id' order by a.id desc ";

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
                    <div class="dashboard-title-2" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="caption-2">
                            <h2>List of Refund Entries</h2>
                            <p>Refund to Company</p>
                        </div>
                        <div>
                            <a href="refund-entry.php" class="btn btn-warning">New Refund Entry</a>
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
                    $uplim = 50;
                    $lowlim = $uplim * ($cn - 1);
                    $no = $lowlim;

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
                                            <th class="text-center">SL.</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Reference Id</th>
                                            <th class="text-center">UTR Number</th>
                                            <th class="text-center">Screenshot</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Status Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $con = $db->connect();
                                        while ($r1 = mysqli_fetch_assoc($sql)) {
                                            $no++;
                                        ?>
                                            <tr>
                                                <td class="text-center"><?= $no; ?>.</td>
                                                <td class="text-center">
                                                    Refund Date : <?= dmy($r1['input_date']); ?> <br />
                                                    Uploaded Date : <?= dmy($r1['created_at']); ?>
                                                </td>
                                                <td class="text-center">
                                                    INR <?= $r1['amount']; ?><br />
                                                    USD <?= $r1['usd_amount']; ?>
                                                </td>
                                                <td class="text-center"><?= $r1['reference_id']; ?></td>
                                                <td class="text-center"><?= $r1['transaction_number']; ?></td>
                                                <td class="text-center">
                                                    <a data-fancybox data-src="../assets/images/screenshot/<?php echo $r1['screenshot']; ?>" href="javascript:;" class="text-white">
                                                        <i class="fa fa-search"></i> VIEW
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    if ((int)$r1['current_status'] === 1) {
                                                        echo "Accepted";
                                                    } elseif ((int)$r1['current_status'] === 0) {
                                                        echo 'Pending';
                                                    } elseif ((int)$r1['current_status'] === 2) {
                                                        echo '<code>';
                                                        echo 'Rejected';
                                                        echo '</code>';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center"><?= $r1['reject_comment']; ?></td>
                                            </tr>
                                        <?php
                                        }
                                        $db->dbDisconnet($con);
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12">

                            <br />
                            <div class="container text-center">
                                <ul class="pagination">
                                    <?php

                                    $get = $_GET;
                                    if (isset($get['cp'])) unset($get['cp']);

                                    if ($cn != 1) {
                                        $prev = $cn - 1;
                                        $first = 1;
                                    ?>
                                        <li>
                                            <a href="<?php echo $page_name; ?>?cp=<?php echo $first; ?><?php echo "&" . http_build_query($get); ?>"
                                                id="pagination" title="First Page"><?php echo "<<"; ?></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo $page_name; ?>?cp=<?php echo $prev; ?><?php echo "&" . http_build_query($get); ?>"
                                                id="pagination" title="Previous Page"><?php echo "<"; ?></a>
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
                                            <li>
                                                <a href="<?php echo $page_name; ?>?cp=<?php echo $i; ?><?php echo "&" . http_build_query($get); ?>"
                                                    id="pagination"
                                                    title="Page No. <?php echo $i; ?>"><?php echo "$i"; ?></a>
                                            </li>
                                        <?php
                                        } else {
                                        ?>
                                            <li class="active"><span id="pagination_selected"><?php echo $i; ?></span>
                                            </li>
                                        <?php
                                        }
                                    }
                                    $next = $cn + 1;
                                    if ($tot_page >= $next) {
                                        ?>
                                        <li>
                                            <a href="<?php echo $page_name; ?>?cp=<?php echo $next; ?><?php echo "&" . http_build_query($get); ?>"
                                                id="pagination" title="Next Page"><?php echo ">"; ?></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo $page_name; ?>?cp=<?php echo $tot_page; ?><?php echo "&" . http_build_query($get); ?>"
                                                id="pagination" title="Last Page"><?php echo ">>"; ?></a>
                                        </li>
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