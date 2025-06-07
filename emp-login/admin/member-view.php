<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$con = $db->connect();
$direct_downlineCount = direct_downlineCount($con, $user_code);
$total_downlineCount = total_downlineCount($con, $user_code);
$db->dbDisconnet($con);

if (isset($_POST['mem_code'])) {
    $mem_code = trim($_POST['mem_code']);
} else {
    $mem_code = '';
}

if (isset($_POST['mem_name'])) {
    $mem_name = $_POST['mem_name'];
} else {
    $mem_name = '';
}

if (isset($_REQUEST['mobile'])) {
    $mobile = $_REQUEST['mobile'];
} else {
    $mobile = '';
}

/*if (isset($_REQUEST['pan'])) {
    $pan = $_REQUEST['pan'];
} else {
    $pan = '';
}*/

if (isset($_REQUEST['start_date']) && isset($_REQUEST['end_date'])  && ($_REQUEST['start_date'] != '')  && ($_REQUEST['end_date'] != '')) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $start_date = ymd($_REQUEST['start_date']);
        $end_date = ymd($_REQUEST['end_date']);
    } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $start_date = ymd($_REQUEST['start_date']);
        $end_date = ymd($_REQUEST['end_date']);
    }

    $period = "From " . dmy($start_date) . " To " . dmy($end_date);
} else {
    $start_date = '';
    $end_date = '';
    $period = 'All';
}

$search_joining_date = '';
$search_by = '';
$mem_name_id = '';
$downline = '';
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
                            <h2>List of Members</h2>
                            <p>Member List</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title-2 mb-4">
                            <button type="button" class="btn btn-info">
                                My Direct : <span class="badge badge-light"><?php echo $direct_downlineCount; ?></span>
                            </button>
                            <button type="button" class="btn btn-warning">
                                My Team : <span class="badge badge-light"><?php echo $total_downlineCount; ?></span>
                            </button>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <?php include('include/alert.php'); ?>
                        <form id="frmAdd" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                            <div class="row">
                                <div class="form-group col-md-4 col-sm-6">
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" required <?php if (!((isset($_REQUEST['search_by'])) && (($_REQUEST['search_by'] == 'mem_name') || ($_REQUEST['search_by'] == 'mem_mobile')))) { ?>checked="checked" <?php } ?> name="search_by" value="mem_code" class="form-check-input" />
                                            Member Code
                                        </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" required <?php if (!((isset($_REQUEST['search_by'])) && (($_REQUEST['search_by'] == 'mem_name') || ($_REQUEST['search_by'] == 'mem_mobile')))) { ?>checked="checked" <?php } ?> name="search_by" value="mem_name" class="form-check-input" />
                                            Member Name
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-sm-6">
                                    <label>Member Code/Name</label>
                                    <input type="text" name="mem_name_id" class="form-control" value="<?php if ((isset($_REQUEST['mem_name_id'])) && ($_REQUEST['mem_name_id'] != '')) {
                                                                                                            echo $_REQUEST['mem_name_id'];
                                                                                                        } ?>" />
                                </div>

                                <div class="form-group col-md-4 col-sm-6">
                                    <label>Mobile</label>
                                    <input type="text" name="mobile" value="<?php if (isset($_REQUEST['mobile'])) {
                                                                                echo $mobile;
                                                                            } ?>" class="form-control" placeholder="mobile Number" />
                                </div>
                            </div>
                            <div class="row">
                                <?php /* ?><div class="form-group col-md-4 col-sm-6">
                                    <label>PAN</label>
                                    <input type="text" name="pan" value="<?php if (isset($_REQUEST['pan'])) {
                                                                                echo $pan;
                                                                            } ?>" class="form-control" placeholder="PAN No" />
                                </div><?php */ ?>

                                <div class="form-group col-md-4 col-sm-6">
                                    <label><span style="color:#000;">**</span><input type="checkbox" <?php if ((isset($_REQUEST['search_joining_date'])) && ($_REQUEST['search_joining_date'] == 'yes')) { ?>checked="checked" <?php } ?> name="search_joining_date" value="yes" /></label>

                                    <label>Joining Date</label>
                                    <input type="date" name="start_date" value="<?php if (isset($_REQUEST['start_date'])) {
                                                                                    echo $start_date;
                                                                                } ?>" class="dp-1 datepicker form-control" placeholder="From Date" />
                                </div>

                                <div class="form-group col-md-4 col-sm-6">
                                    <label>&nbsp;</label>
                                    <input type="date" name="end_date" value="<?php if (isset($_REQUEST['end_date'])) {
                                                                                    echo $end_date;
                                                                                } ?>" class="dp-1 datepicker form-control" placeholder="To Date" />
                                </div>

                                <div class="form-group col-md-12 col-sm-12">
                                    <input type="checkbox" name="downline" value="1" <?php if ((isset($_REQUEST['downline'])) && ($_REQUEST['downline'] == 1)) { ?>checked="checked" <?php } ?>><span style="color:#000;"> Include downline</span>
                                </div>

                                <div class="form-group col-md-12 col-sm-12">
                                    <button type="submit" name="submit" id="submit" class="btn btn-danger col-md-2 col-sm-3" value="Search">Search</button>
                                    <span style="color:#000;">&nbsp;&nbsp; **Check the box for searching with date.</span>
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
                    $uplim = 50;
                    $lowlim = $uplim * ($cn - 1);
                    $no = $lowlim;

                    if (isset($_REQUEST['mem_name_id']) && ($_REQUEST['mem_name_id'] != '') && isset($_REQUEST['search_by']) && ($_REQUEST['search_by'] != '')) {
                        $mem_name_id = trim($_REQUEST['mem_name_id']);
                        $search_by = trim($_REQUEST['search_by']);

                        if (($_REQUEST['search_by'] == 'mem_code')) {

                            $con = $db->connect();
                            $mc = member_code($con, $mem_name_id);
                            $db->dbDisconnet($con);

                            $query_search_by = " AND (`m1`.`mem_code` like '" . $mem_name_id . "') ";

                            if (isset($_REQUEST['downline']) && ($_REQUEST['downline'] != '') && ($_REQUEST['downline'] == 1)) {
                                $downline = 1;
                                $query_search_by = " AND (m1.intro_mtree LIKE '%$mem_name_id%' OR  (`m1`.`mem_code` like '" . $mem_name_id . "') )";
                            }
                        } elseif (($_REQUEST['search_by'] == 'mem_name')) {
                            $query_search_by = " AND (`m1`.`name` like '%" . $mem_name_id . "%') ";
                        } else {
                            $query_search_by = '';
                        }
                    } else {
                        $query_search_by = '';
                    }


                    if (!empty($mobile)) {
                        $sql_mobile = " AND m1.mobile = '" . $mobile . "'";
                    } else {
                        $sql_mobile = "";
                    }

                    /*if (!empty($pan)) {
                        $sql_pan = " AND m3.pan_no = '" . $pan . "'";
                    } else {
                        $sql_pan = "";
                    }*/
                    
                    $sql_pan = "";

                    if (isset($_REQUEST['search_joining_date']) && ($_REQUEST['search_joining_date'] != '') && ($_REQUEST['search_joining_date'] == 'yes')) {
                        $search_joining_date = 'yes';
                        $search_with_date_range = " AND (`m1`.`doj` BETWEEN '" . $start_date . "' AND '" . $end_date . "') ";
                    } else {
                        $search_joining_date = '';
                        $search_with_date_range = "";
                    }

                    $query = "select m1.*, m2.*, m3.state, m3.city, m3.pin, m3.pan_no from member m1 
                    inner join member_login m2 on m1.member_id=m2.member_id
    				inner join member_details m3 on m1.member_id=m3.member_id where 1 " . $query_search_by . $search_with_date_range . $sql_mobile . $sql_pan;

                    $con = $db->connect();
                    $ss = mysqli_query($con, "$query order by m1.member_id desc");
                    $db->dbDisconnet($con);
                    $t = mysqli_num_rows($ss);
                    $tot_page = ceil($t / $uplim);
                    $con = $db->connect();
                    $sql = mysqli_query($con, "$query order by m1.member_id desc limit $lowlim,$uplim");
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
                                            <th>Member Info</th>
                                            <th>Password</th>
                                            <th>Intro</th>
                                            <th>DOJ</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $con = $db->connect();
                                        while ($r1 = mysqli_fetch_assoc($sql)) {
                                            $no++;

                                            $member_id = $r1['member_id'];
                                            $mem_code = $r1['mem_code'];

                                            $qc = mysqli_query($con, "select * from member where intro_mtree like '%$mem_code%'");
                                            $nc = mysqli_num_rows($qc);

                                            $is_active = member_is_active($con, $member_id);

                                            $active_date = current_package_date_of_member($con, $member_id);

                                            /*if($mem_code=='MF53649281'){
                                            $intro_code = 'ADMIN';
                                        }else{
                                            $intro_code = $r1['intro_code'];
                                        }*/

                                            $intro_code = $r1['intro_code'];
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?>.</td>
                                                <td>
                                                    <b>Code :</b> <?php echo $mem_code; ?><br>
                                                    <b>Name :</b> <?php echo $r1['name']; ?><br>
                                                    <b>Mobile :</b> <?php echo $r1['mobile']; ?><br>
                                                    <b>Email :</b> <?php echo $r1['email']; ?>

                                                    <!--<a href="change-member-prefix?id=<?php //echo hash_encode($member_id); 
                                                                                            ?>" class="btn btn-sm btn-info">Change ID</a>-->
                                                </td>
                                                <td><?php echo $r1['password']; ?></td>
                                                <td><?php echo $intro_code; ?></td>
                                                <td><?php echo dmy($r1['doj']); ?></td>
                                                <td>
                                                    <?php
                                                    if ($is_active >= 1) {
                                                        echo "<span class='badge badge-success'>Active</span><br>";
                                                        //echo "<span class='badge badge-warning'>" . CURRENCY_ICON . $total_topup_amount . "</span><br>";
                                                        echo dmy($active_date);
                                                    } else {
                                                        echo "<span class='badge badge-danger'>Inactive</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if ($r1['status'] == 1) { ?>
                                                        <i class="text-success fa fa-check"></i>
                                                    <?php } else { ?>
                                                        <i class="text-danger fa fa-ban"></i>
                                                    <?php } ?>
                                                    <a href="change-member-status?id=<?php echo hash_encode($member_id); ?>">Change Status</a>
                                                    <br />
                                                    <a href="my-profile?action=edit&id=<?php echo hash_encode($member_id); ?>" class="btn btn-sm btn-warning" role="button"><i class="fa fa-pencil" title="Edit"></i></a>
                                                    <!--<a href="bank-details?action=bankedit&id=<?php echo hash_encode($member_id); ?>" class="btn btn-sm btn-danger" role="button"><i class="fa fa-bank" title="Edit Bank Details"></i></a>-->
                                                    <a class="btn btn-info btn-sm"
                                                        href="login_as_member.php?mem_code=<?= $mem_code ?>"
                                                        title="login as member">
                                                        <i class="fa fa-sign-in" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                                <!--<td>
                                                <a href="my-profilec?action=sms&id=<?php echo hash_encode($member_id); ?>" class="btn btn-sm btn-primary" role="button">Resend SMS</a>

                                            </td>-->


                                            </tr>
                                        <?php
                                        }
                                        $db->dbDisconnet($con);
                                        ?>
                                    </tbody>
                                </table>


                                <ul class="pagination">
                                    <?php
                                    if ($cn != 1) {
                                        $prev = $cn - 1;
                                        $first = 1;
                                    ?>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $first; ?>&search_by=<?php echo $search_by; ?>&mem_name_id=<?php echo $mem_name_id; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>" id="pagination" title="First Page"><?php echo "First"; ?></a></li>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $prev; ?>&search_by=<?php echo $search_by; ?>&mem_name_id=<?php echo $mem_name_id; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>" id="pagination" title="Previous Page"><?php echo "Previous"; ?></a></li>
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
                                            <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $i; ?>&search_by=<?php echo $search_by; ?>&mem_name_id=<?php echo $mem_name_id; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>" id="pagination" title="Page No. <?php echo "$i"; ?>"><?php
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
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $next; ?>&search_by=<?php echo $search_by; ?>&mem_name_id=<?php echo $mem_name_id; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>" id="pagination" title="Next Page"><?php echo "Next"; ?></a></li>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $tot_page; ?>&search_by=<?php echo $search_by; ?>&mem_name_id=<?php echo $mem_name_id; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>" id="pagination" title="Last Page"><?php echo "Last"; ?></a></li>
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