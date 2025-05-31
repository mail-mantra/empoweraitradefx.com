<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'pay-income';

if (isset($_REQUEST['mem_code'])) {
    $mem_code = trim($_REQUEST['mem_code']);
} else {

    $mem_code = '';
}

if (isset($_REQUEST['amount'])) {
    $amount = trim($_REQUEST['amount']);
} else {
    $amount = '';
}

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

if (isset($_REQUEST['search_joining_date']) && ($_REQUEST['search_joining_date'] != '') && ($_REQUEST['search_joining_date'] == 'yes')) {
    $search_joining_date = 'yes';
} else {
    $search_joining_date = '';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php include('include/title.php'); ?></title>

    <?php include('include/header-common-file.php'); ?>
    <script>
        checked = false;

        function checkedAll() {
            if (checked == false) {
                checked = true;
            } else {
                checked = false;
            }
            for (var i = 0; i < document.getElementById('myform').elements.length; i++) {
                document.getElementById('myform').elements[i].checked = checked;
            }
        }
    </script>
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
                            <h2>Pay Working Income</h2>
                        </div>
                    </div>
                </div>
            </div>


            <div class="table-panel">
                <div class="row">
                    <div class="col-md-12">
                        <?php include('include/alert.php'); ?>
                        <form id="frmAdd" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                            <div class="row">
                                <div class="form-group col-md-3 col-sm-6">
                                    <label>Member Code</label>
                                    <input type="text" name="mem_code" value="<?php if (isset($_REQUEST['mem_code'])) {
                                                                                    echo $mem_code;
                                                                                } ?>" class="form-control" />
                                </div>
                                <div class="form-group col-md-3 col-sm-6">
                                    <label>**<input type="checkbox" <?php if ((isset($_REQUEST['search_joining_date'])) && ($_REQUEST['search_joining_date'] == 'yes')) { ?>checked="checked" <?php } ?> name="search_joining_date" value="yes" /></label>

                                    <label>From Date</label>
                                    <input type="date" name="start_date" value="<?php if (isset($_REQUEST['start_date'])) {
                                                                                    echo $start_date;
                                                                                } ?>" class="form-control" />
                                </div>
                                <div class="form-group col-md-3 col-sm-6">
                                    <label>To Date</label>
                                    <input type="date" name="end_date" value="<?php if (isset($_REQUEST['end_date'])) {
                                                                                    echo $end_date;
                                                                                } ?>" class="form-control" />
                                </div>
                                <div class="form-group col-md-3 col-sm-6">
                                    <label>Amount</label>
                                    <input type="text" name="amount" value="<?php if (isset($_REQUEST['amount'])) {
                                                                                echo $amount;
                                                                            } ?>" class="form-control" />
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" name="submit" id="submit" class="btn btn-primary col-md-2 col-sm-3">Search</button>
                                    **Check the box for searching with date.
                                </div>
                            </div>
                        </form>
                        <br><br />
                        <?php
                        if (isset($_GET['cp'])) {
                            $cn = $_GET['cp'];
                        } else {
                            $cn = 1;
                        }
                        $uplim = 500;
                        $lowlim = $uplim * ($cn - 1);
                        $no = $lowlim;

                        if (isset($_REQUEST['mem_code']) && isset($_REQUEST['mem_code']) && ($_REQUEST['mem_code'] != '')) {
                            $mem_code = trim($_REQUEST['mem_code']);
                            $query_member = " AND (a.mem_code='" . $mem_code . "') ";
                        } else {
                            $query_member = "";
                        }
                        if (!empty($amount)) {
                            $amount = trim($_REQUEST['amount']);
                            $query_amount = " AND (b.balance LIKE'" . $amount . ".%') ";
                        } else {
                            $query_amount = "";
                        }

                        if (isset($_REQUEST['search_joining_date']) && ($_REQUEST['search_joining_date'] != '') && ($_REQUEST['search_joining_date'] == 'yes')) {
                            $search_joining_date = 'yes';
                            $search_with_date_range = " AND (DATE(b.`created_on`) BETWEEN '" . $start_date . "' AND '" . $end_date . "') ";
                        } else {
                            $search_joining_date = '';
                            $search_with_date_range = "";
                        }

                        $query = "select a.* , b.balance,d.`crypto_address_bep20`, d.`bnk_nm`, d.`brnch_nm`, d.`acc_nm`, d.`acc_no`, d.`acc_type`, d.`ifsc`
                            from member a 
                            INNER JOIN working_wallet_transaction b ON (a.member_id=b.member_id)
                            INNER JOIN member_details d ON (a.member_id=d.member_id)
                            INNER JOIN (SELECT MAX(id) AS mid FROM working_wallet_transaction group by member_id ) AS c ON (c.mid=b.id)
                            WHERE (b.balance>=5) AND 
                            d.crypto_address_bep20 IS NOT NULL AND d.crypto_address_bep20 != ''" . $query_member . $search_with_date_range . $query_amount . "";

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

                            /*$con=$db->connect();
    					$qtd = mysqli_query($con,"SELECT admin_charge, tds_with_pan, tds_without_pan FROM admin_tds_charge;");
    					$db->dbDisconnet($con);
    					$rtd = mysqli_fetch_assoc($qtd);
    					$admin_charge = $rtd['admin_charge'];*/
                            //$tds_with_pan = $rtd['tds_with_pan'];
                            //$tds_without_pan = $rtd['tds_without_pan'];

                        ?>
                            <form id="myform" name="myform" action="pay-income-code" method="post">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-dark">
                                        <thead>
                                            <tr>
                                                <td colspan="5"><input type="submit" name="submit" id="submit" class="btn btn-primary" value="Click To Pay Income" /></td>
                                            </tr>
                                            <tr>
                                                <th><input type='checkbox' name='checkall' onclick='checkedAll();' title="Select all" /> </th>
                                                <th>Sl.</th>
                                                <th>Member Details</th>
                                                <th>Crypto Address</th>
                                                <th>Payable amt.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $page_total_net = 0;
                                            while ($a = mysqli_fetch_assoc($sql)) {
                                                $no++;
                                                $member_id = $a['member_id'];
                                                $gross_amount = $a['balance'];

                                                $page_total_net += $gross_amount;
                                            ?>
                                                <tr>
                                                    <td><input type="checkbox" name="foo[]" value="<?php echo $member_id; ?>" /></td>
                                                    <td><?php echo $no; ?>.</td>
                                                    <td>
                                                        <strong>Code :</strong> <?php echo $a['mem_code']; ?><br>
                                                        <strong>Name :</strong> <?php echo $a['name']; ?><br>
                                                        <strong>Mob. :</strong> <?php echo $a['mobile']; ?><br>
                                                    </td>
                                                    <td>
                                                        <?php echo $a['crypto_address_bep20']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo CURRENCY_ICON . number_format($gross_amount, 2); ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            }

                                            $all_total_net = 0;
                                            while ($b = mysqli_fetch_assoc($ss)) {
                                                $gross_amount = $b['balance'];
                                                $all_total_net += $gross_amount;
                                            }
                                            ?>


                                            <tr>
                                                <td colspan="4">Page Total</td>
                                                <td><?php echo CURRENCY_ICON . number_format($page_total_net, 2); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">All Total</td>
                                                <td><?php echo CURRENCY_ICON . number_format($all_total_net, 2); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </form>

                            <ul class="pagination">
                                <?php
                                if ($cn != 1) {
                                    $prev = $cn - 1;
                                    $first = 1;
                                ?>
                                    <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $first; ?>&mem_code=<?php echo $mem_code; ?>&amount=<?php echo $amount; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>" id="pagination" title="First Page"><?php echo "First"; ?></a></li>
                                    <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $prev; ?>&mem_code=<?php echo $mem_code; ?>&amount=<?php echo $amount; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>" id="pagination" title="Previous Page"><?php echo "Previous"; ?></a></li>
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
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $i; ?>&mem_code=<?php echo $mem_code; ?>&amount=<?php echo $amount; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>" id="pagination" title="Page No. <?php echo "$i"; ?>"><?php
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
                                    <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $next; ?>&mem_code=<?php echo $mem_code; ?>&amount=<?php echo $amount; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>" id="pagination" title="Next Page"><?php echo "Next"; ?></a></li>
                                    <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $tot_page; ?>&mem_code=<?php echo $mem_code; ?>&amount=<?php echo $amount; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>" id="pagination" title="Last Page"><?php echo "Last"; ?></a></li>
                                <?php
                                }
                                ?>
                            </ul>
                        <?php
                        } else {
                            echo '<br><br><div class="alert alert-info">No records found...!</div>';
                        }
                        ?>
                    </div>
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