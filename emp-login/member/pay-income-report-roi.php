<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();
$now = now();

$page_name = 'pay-income-report-roi';

if (isset($_REQUEST['status'])) {
    $status = trim($_REQUEST['status']);
} else {
    $status = '';
}

if (isset($_REQUEST['ac_number'])) {
    $ac_number = trim($_REQUEST['ac_number']);
} else {
    $ac_number = '';
}

if (isset($_REQUEST['start_date'])) {
    $start_date = ymd(trim($_REQUEST['start_date']));
} else {
    $start_date = $first_day_this_month;
}

if (isset($_REQUEST['end_date'])) {
    $end_date = ymd(trim($_REQUEST['end_date']));
} else {
    $end_date = today();
}

$period = "From " . dmy($start_date) . " To " . dmy($end_date);


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
    <div class="page-loader">
        <div class="loader"></div>
    </div>
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
                            <h2>View Pay Trading Profit Voucher</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="col-lg-12">
                    <?php include('include/alert.php'); ?>
                    <form id="frmAdd" action="<?php $_SERVER['PHP_SELF'] ?>" method="get">
                        <div class="row">

                            <div class="form-group col-md-3 col-sm-6">
                                <label>AC Number</label>
                                <input type="text" name="ac_number" value="<?php if (isset($_REQUEST['ac_number'])) {
                                                                                echo $ac_number;
                                                                            } ?>" class="form-control" />
                            </div>
                            <div class="form-group col-md-2 col-sm-6">
                                <label>From Date</label>
                                <input type="date" name="start_date" value="<?= $start_date ?>" class="form-control" />
                            </div>
                            <div class="form-group col-md-2 col-sm-6">
                                <label>To Date</label>
                                <input type="date" name="end_date" value="<?= $end_date ?>" class="form-control" />
                            </div>
                            <div class="form-group col-md-2 col-sm-6">
                                <label>Paid Stats</label>
                                <Select class="form-control" name="status">
                                    <option value="ALL">All</option>
                                    <option value="PENDING" <?php echo ($status == "PENDING") ? "selected" : ""; ?>>Pending</option>
                                    <option value="PAID" <?php echo ($status == "PAID") ? "selected" : ""; ?>>Paid</option>
                                </Select>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" name="submit" id="submit" class="btn btn-danger col-md-2 col-sm-3">Search</button>
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

                    if (isset($_REQUEST['mem_code']) && isset($_REQUEST['mem_code']) && ($_REQUEST['mem_code'] != '')) {
                        $mem_code = trim($_REQUEST['mem_code']);
                        $query_member = " AND (m1.mem_code='" . $mem_code . "') ";
                    } else {
                        $query_member = "";
                    }
                    $query_status = "";
                    if ($status != "ALL") {
                        if ($status == "PAID") {
                            $query_status = " AND v.paid_status = '1'";
                        }
                        if ($status == "PENDING") {
                            $query_status = " AND v.paid_status = '0'";
                        }
                    }

                    $search_with_date_range = " AND (DATE(v.paid_date) BETWEEN '" . $start_date . "' AND '" . $end_date . "') ";

                    if (!empty($ac_number)) {
                        $search_ac_number = " AND m2.acc_no = '" . $ac_number . "' ";
                    } else {
                        $search_ac_number = '';
                    }

                    $query = "select v.*, v.id as voucher_id, m1.mem_code, m1.name, m2.bnk_nm, m2.brnch_nm, m2.acc_nm, m2.acc_no, m2.pan_no, m2.ifsc, m2.crypto_address_bep20 from
                    pay_roi_voucher v 
                    inner join member m1 on v.m_id=m1.member_id
                    inner join member_details m2 on v.m_id=m2.member_id
                    where m1.member_id='" . $user_id . "' " . $query_member . $search_with_date_range . $query_status . $search_ac_number . "  order by v.id desc";


                    $con = $db->connect();
                    $ss = mysqli_query($con, $query);
                    $db->dbDisconnet($con);
                    $t = mysqli_num_rows($ss);
                    $tot_page = ceil($t / $uplim);
                    $con = $db->connect();
                    $sql = mysqli_query($con, $query . " limit $lowlim,$uplim");
                    $db->dbDisconnet($con);
                    $nsql = mysqli_num_rows($sql);
                    if ($nsql > 0) {
                    ?>
                        <div class="col-lg-12">
                            <div class="title-2 mb-4">
                                Voucher <?php echo $period; ?>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-dark">
                                    <thead>
                                        <tr>
                                            <th>SL.</th>
                                            <th>Payout Details</th>
                                            <th>Crypto Address</th>
                                            <th class="text-center">Payable amt.</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //$i=0;
                                        $tot_gross = 0;
                                        while ($r1 = mysqli_fetch_assoc($sql)) {
                                            $no++;
                                            $net_amount = $r1['amount'];
                                            $tot_gross = $tot_gross + $net_amount;

                                            $bank_details = $r1['crypto_address_bep20']; //'Bank : ' . $r1['bnk_nm'] . "<br>" . 'Branch : ' . $r1['brnch_nm'] . "<br>" . 'A/c No. : ' . $r1['acc_no'] . "<br>" . 'IFSC : ' . $r1['ifsc'] . "<br>" . 'PAN : ' . $r1['pan_no'];
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?>.</td>
                                                <td>
                                                    Dt - <strong><?php echo dmy($r1['paid_date']); ?></strong><br />
                                                    Voucher No- <span class="label label-primary"><?php echo $r1['voucher_no']; ?></span>

                                                </td>
                                                <td><?php echo $bank_details; ?></td>
                                                <td class="text-center">
                                                    <?php echo CURRENCY_ICON . number_format($net_amount, 2); ?>
                                                </td>
                                                <td>
                                                    <?php if (!$r1['withwraw_status']) { ?>
                                                        <a href="roi-withdraw-bep20?rdm=<?php echo hash_encode($r1['id']) ?>&action=pay" class="btn btn-primary btn-sm">Withdraw</a>
                                                    <?php } else {
                                                        echo "Withdraw Done.";
                                                    } ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        while ($dt = mysqli_fetch_array($ss)) {
                                            $income = $dt['amount'];
                                            $income_arr[] = $income;
                                        }
                                        $all_total_gross = array_sum($income_arr);
                                        unset($income_arr);
                                        ?>
                                        <tr>
                                            <td colspan="3" align="right"><strong>Total (This Page)</strong></td>
                                            <td colspan="2"><strong><?php echo CURRENCY_ICON . number_format($tot_gross, 2); ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="right"><strong>Total Gross (All)</strong></td>
                                            <td colspan="2"><strong><?php echo CURRENCY_ICON . number_format($all_total_gross, 2); ?></strong></td>
                                        </tr>

                                    </tbody>
                                </table>

                                <ul class="pagination">
                                    <?php
                                    if ($cn != 1) {
                                        $prev = $cn - 1;
                                        $first = 1;
                                    ?>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $first; ?>&search_by=<?php echo $search_by; ?>&mem_name_id=<?php echo $mem_name_id; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>&status=<?php echo ($status); ?>&ac_number=<?php echo ($ac_number); ?>" id="pagination" title="First Page"><?php echo "First"; ?></a></li>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $prev; ?>&search_by=<?php echo $search_by; ?>&mem_name_id=<?php echo $mem_name_id; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>&status=<?php echo ($status); ?>&ac_number=<?php echo ($ac_number); ?>" id="pagination" title="Previous Page"><?php echo "Previous"; ?></a></li>
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
                                            <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $i; ?>&search_by=<?php echo $search_by; ?>&mem_name_id=<?php echo $mem_name_id; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>&status=<?php echo ($status); ?>&ac_number=<?php echo ($ac_number); ?>" id="pagination" title="Page No. <?php echo "$i"; ?>"><?php
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
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $next; ?>&search_by=<?php echo $search_by; ?>&mem_name_id=<?php echo $mem_name_id; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>&status=<?php echo ($status); ?>&ac_number=<?php echo ($ac_number); ?>" id="pagination" title="Next Page"><?php echo "Next"; ?></a></li>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $tot_page; ?>&search_by=<?php echo $search_by; ?>&mem_name_id=<?php echo $mem_name_id; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>&status=<?php echo ($status); ?>&ac_number=<?php echo ($ac_number); ?>" id="pagination" title="Last Page"><?php echo "Last"; ?></a></li>
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

    <script>
        $(".btnPay").click(function() {
            if (confirm('Confirm ?')) {
                $(".btnPay").hide();
            } else {
                return false;
            }
        });
    </script>

    <!-- particles -->
    <script src="../web-assets/js/particles.min.js"></script>
    <script src="../web-assets/js/app.js"></script>

</body>

</html>