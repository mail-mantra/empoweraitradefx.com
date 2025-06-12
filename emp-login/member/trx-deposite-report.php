<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
include('../lib/mail_sms.php');
$db = new Database();

$page_name = 'trx-deposite-report';
?>
<?php include('include/header.php'); ?>
<?php include('include/sidebar.php'); ?>

<div class="main">
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="heading">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="col-sm-6">
                            <h2>TRX Deposit Report</h2>

                        </div>
                        <div class="col-sm-6 text-right">
                            <div class="form-group tc" style="padding:15px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container-fluid">
            <div class="row">
                <?php
                $sql_view = "SELECT a.*, b.name, c.amount AS trx_amount FROM payment_transaction a 
                INNER JOIN member b ON a.member_id=b.member_id 
                INNER JOIN trongrid_payment_data AS c ON a.for_member_id = c.tx_id
                WHERE a.`member_id`='$user_id' ORDER BY id DESC;";

                $con = $db->connect();
                $res_view = $con->query($sql_view);
                $db->dbDisconnet($con);
                $result["sql"] = $sql_view;
                if ($res_view->num_rows) {
                ?>
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-dark">
                                <thead>
                                    <tr>
                                        <th>SL.</th>
                                        <th>Name</th>
                                        <th>Transaction Details</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    while ($arr_view = $res_view->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $i; ?>.</td>
                                            <td><?php echo $arr_view['name']; ?></td>
                                            <td>
                                                <p class="small text-info" style="padding-bottom: 0px; margin-bottom: 0px;">
                                                    <a href="https://tronscan.org/#/transaction/<?php echo $arr_view['for_member_id'] ?>" target="_BLANK">
                                                        <?php echo substr($arr_view['for_member_id'], 0, 8) . "..." . substr($arr_view['for_member_id'], -8) ?>
                                                    </a>

                                                    <span id="address_<?php echo $i ?>" style="display: none;">https://tronscan.org/#/transaction/<?php echo $arr_view['for_member_id'] ?></span>
                                                    <a onclick="copyToClipboard2(<?php echo $i ?>);"><i class="fa fa-files-o" aria-hidden="true" style="cursor: copy;"></i></a>
                                                </p>
                                            </td>
                                            <td><?php echo date('d-m-Y h:i A', strtotime($arr_view['created_on'])); ?></td>
                                            <td>
                                                <h6>
                                                    <?php echo "$" . number_format($arr_view["amount"], 8); ?>
                                                </h6>
                                                <span style="font-size: 12px;"><?php echo 'TRX ' . number_format($arr_view["trx_amount"] / 1000000, 6); ?></span>
                                            </td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php
                } else {
                ?>
                    <div class="col-lg-12 text-center">
                        <div class="text-danger">
                            No data found...!
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>

    </div><!-- END MAIN CONTENT -->
    <div class="clearfix"></div>
    <?php include('include/footer.php'); ?>