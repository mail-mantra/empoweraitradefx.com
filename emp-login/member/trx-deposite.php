<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();
$page_name = 'trx-deposite';
$crypto_deposit = true;
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
                            <h2>TRX Deposit</h2>
                            <p>TRX Deposit</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-panel">
                <div class="row" style="padding-top: 40px;">
                    <div class="col-lg-12 text-center">
                        <?php
                        include('include/alert.php');
                        if ($crypto_deposit) {
                            $con = $db->connect();
                            $_q1 = mysqli_query($con, "SELECT trx_address,trx_hex FROM `member` where member_id='" . $user_id . "'");
                            $con->close();
                            if (mysqli_num_rows($_q1) == 1) {
                                $_r1 = mysqli_fetch_assoc($_q1);
                                $trx_address = $_r1['trx_address'];
                                $trx_hex = $_r1['trx_hex'];

                                if ($trx_address == NULL || $trx_address == '') {
                        ?>
                                    <!-- create address -->
                                    <h4>You don't have TRX address. Please create one.</h4>
                                    <a href="create-address" class="btn btn-primary">Create</a>
                                <?php
                                } else {
                                ?>
                                    <!-- QR -->
                                    <h4>Scan & Pay</h4>
                                    <img alt="Loading...." title="Scan and Pay" src="https://image-charts.com/chart?chs=100x100&amp;cht=qr&amp;chl=<?php echo $trx_address ?>">
                                    <!-- .QR -->
                                    <p id="memberAddress" style="color:#000; padding-top: 20px;"><?php echo $trx_address ?></p>

                                    <p class="text-secondary">
                                        <a style="color:#13ed65; font-size: 16px;" href="whatsapp://send?text=<?php echo $trx_address; ?>"><i class="fa fa-whatsapp"></i></a>
                                        <a style="color:#5af4fb; font-size: 16px;" href="tg://msg_url?url=<?php echo PROJECT_URL; ?>&amp;text=<?php echo $trx_address; ?>"><i class="fa fa-telegram"></i></a>
                                        <a onclick="copyToClipboard();" title="Copy to clipboard" style="color:#c3c600; font-size: 16px;">
                                            <i class="fa fa-files-o" aria-hidden="true" style="cursor: copy;"></i>
                                        </a>
                                    </p>
                                    <p style="font-size: 22px;" class="text-danger">*** Please refresh after sending fund and check your transactions ***</p>
                                    <a href="trx-deposite" class="btn btn-primary">Refresh</a>
                                    <br />
                                    <br />
                            <?php

                                    /* get all transactions */
                                    // get USD value 
                                    //$response = curlGet('https://api.coingecko.com/api/v3/simple/price?ids=tron&vs_currencies=usd');
                                    $response = curlGet('https://min-api.cryptocompare.com/data/price?fsym=TRX&tsyms=USDT&api_key=' . CRYPTOCOMPARE_API_KEY . '');
                                    if (isset($response->USDT)) {
                                        $usd_value = $response->USDT;

                                        /* $con = $db->connect();
                                        $buy_rate = get_buy_rate($con);
                                        $con->close(); */
                                        // get transactions
                                        $url = "https://api.trongrid.io/v1/accounts/" . $trx_address . "/transactions";
                                        $response = curlGet($url);
                                        /* print_r($response); */
                                        foreach ($response->data as $data) {

                                            $contractRet = $data->ret[0]->contractRet;
                                            $txID = $data->txID;
                                            $blockNumber = $data->blockNumber;
                                            $block_timestamp = $data->block_timestamp;
                                            $amount = $data->raw_data->contract[0]->parameter->value->amount;
                                            $to_address = $data->raw_data->contract[0]->parameter->value->to_address;
                                            $type = $data->raw_data->contract[0]->type;  # addred on 10may2021
                                            $now = now();


                                            if (strtoupper($contractRet) == 'SUCCESS' && $to_address == $trx_hex && $type == 'TransferContract') { # updated on 10may2021
                                                $con = $db->connect();
                                                mysqli_autocommit($con, FALSE);
                                                $sql1 = "INSERT INTO trongrid_payment_data(member_id, coinpayment_address, contract_ret, tx_id, block_number, block_timestamp, amount, created_on) values ('" . $user_id . "','" . $trx_address . "','" . $contractRet . "','" . $txID . "','" . $blockNumber . "','" . $block_timestamp . "','" . $amount . "','" . $now . "')";
                                                $q2 = mysqli_query($con, $sql1);

                                                if ($q2) {
                                                    $sql2 = "INSERT INTO `payment_transaction`(`member_id`, `for_member_id`,`income_type`, `amount`, `txnid`, `created_on`) VALUES ('" . $user_id . "','" . $txID . "','ONLINE_TRX_PAYMENT','" . (($amount / 1000000) * $usd_value) . "','" . $txID . "','" . $now . "')";
                                                    $q3 = mysqli_query($con, $sql2);

                                                    if ($q3) {
                                                        mysqli_commit($con);
                                                    } else {
                                                        mysqli_rollback($con);
                                                    }
                                                } else {
                                                    mysqli_rollback($con);
                                                }
                                                $db->dbDisconnet($con);
                                            }
                                        }
                                    } else {
                                        echo "error";
                                    }

                                    /* .get all transactions */
                                }
                            }
                        } else {
                            ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="col-lg-12">
                                        <div class="alert alert-danger" role="alert">Members from outside india can access this feature.</div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <?php
                if ($trx_address != NULL || $trx_address != '') {
                ?>
                    <div class="table-panel">
                        <div class="row">
                            <?php
                            $sql_view = "SELECT a.*, b.name, c.amount AS trx_amount FROM payment_transaction a 
                        INNER JOIN member b ON a.member_id=b.member_id 
                        INNER JOIN trongrid_payment_data AS c ON a.for_member_id = c.tx_id
                        WHERE a.`member_id`='$user_id' ORDER BY id DESC LIMIT 5;";

                            $con = $db->connect();
                            $res_view = $con->query($sql_view);
                            $db->dbDisconnet($con);
                            $result["sql"] = $sql_view;
                            if ($res_view->num_rows) {
                            ?>
                                <div class="col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="table-responsive">
                                            <table class="table table-bordered  table-dark">
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
                                                                    <?php echo "USD " . number_format($arr_view["amount"], 8); ?>
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
                                </div>
                                <?php if ($i > 5) {
                                ?>
                                    <div class="col-lg-12 text-center">
                                        <a href="trx-deposite-report" class="btn btn-primary">Load More</a>
                                    </div>
                                <?php
                                }
                                ?>
                            <?php
                            } else {
                            ?>
                                <div class="col-lg-12" style="padding-top: 40px;">
                                    <div class="text-danger text-center">
                                        No data found...!
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>

        </div>
    </div>
    <!--end-body-content-->

    <!--start-mm-footer-->
    <?php include('include/mm-footer.php'); ?>
    <!--end-mm-footer-->

    <script type="text/javascript">
        $(document).ready(function() {
            $(document).ready(function() {
                $("#frmAdd").submit(function(event) {
                    if (!confirm('Are you sure that you want to submit the form'))
                        event.preventDefault();
                });
            });

        });
    </script>
    <script>
        function copyToClipboard() {
            var temp = $("<input>");
            $("body").append(temp);
            temp.val($('#memberAddress').text()).select();
            document.execCommand("copy");
            temp.remove();
            alert('Copied');
        }

        function copyToClipboard2(id) {
            var temp = $("<input>");
            $("body").append(temp);
            temp.val($('#address_' + id).text()).select();
            document.execCommand("copy");
            temp.remove();
            alert('Copied');
        }
    </script>
    <!-- particles -->
    <script src="../web-assets/js/particles.min.js"></script>
    <script src="../web-assets/js/app.js"></script>

</body>

</html>