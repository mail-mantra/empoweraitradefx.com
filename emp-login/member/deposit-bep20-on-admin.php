<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();
$page_name = 'deposit-bep20';
$now = now();
$member_id = $user_id;
$con = $db->connect();
$sql = "SELECT admin_bep20_address FROM admin_crypto_details WHERE id = 1 AND status= 1 ";
$result = $con->query($sql);

if ($result->num_rows) {
    $row = $result->fetch_assoc();
    $admin_address =  $row["admin_bep20_address"];
} else {
    $admin_address = '';
}
$con->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $con = $db->connect();
    foreach ($_POST as $key => $value) {
        $data[$key] = prevent_injection($con, $value);
    }
    $con->close();

    $user_from_address = $data['user_form_address'];
    $user_amount = $data['user_amount'];

    if (!empty($user_from_address) && !empty($user_amount)) {
        // call bsc scan to get all transaction
        $url = 'https://api.bscscan.com/api?module=account&action=tokentx&contractaddress=' . BEP_20_CONTRACT_ADDRESS . '&address=' . $admin_address . '&offset&startblock=0&endblock=999999999&sort=asc&apikey=' . API_TOKEN;
        $response = curlGet($url);
        // echo "<pre>";
        // print_r($response);
        // die;

        // check for particular from address and amount

        if (!empty($response)) {
            $response_json = json_encode($response);

            $status = $response->status;
            $message = $response->message;

            if (!empty($response->result) && $status == 1 && $message == 'OK') {
                $results = $response->result;

                $con = $db->connect();
                mysqli_autocommit($con, FALSE);

                $balanceError = false;
                foreach ($results as $result) {

                    $blockNumber = $result->blockNumber;
                    $hash = $result->hash;
                    $blockHash = $result->blockHash;

                    $from = $result->from;
                    $to = $result->to;
                    $contractAddress = $result->contractAddress;
                    $timeStamp = $result->timeStamp;
                    $apu_id = '';

                    $value = $result->value;
                    $actual_value = ($value / 1000000000000000000);
                    $txnid = uniqid('TXN') . rand();
                    if (strtoupper($to) == strtoupper($admin_address) && strtoupper($hash) == strtoupper($user_from_address) && $actual_value == $user_amount) {
                        // on match insert ignore into the transaction table.

                        $sql_2 = "INSERT IGNORE INTO  api_transaction_on_admin (member_id, block_number, hash, block_hash, from_address, to_address, coin_address, amount, actual_amount, txn_date, txnid, api_id, created_on) values  ('" . $member_id . "','" . $blockNumber . "','" . $hash . "','" . $blockHash . "','" . $from . "','" . $to . "','" . $contractAddress . "','" . $value . "','" . $actual_value . "', '" . $timeStamp . "', '" . $txnid . "', '" . $api_id . "', '" . $now . "')";
                        $tq2 = mysqli_query($con, $sql_2);
                        $aff_rows = mysqli_affected_rows($con);


                        $balance = 0;
                        $amount = $actual_value;
                        $action_by = 'AUTO';
                        if ($tq2 && $aff_rows) {
                            // balance cr

                            $sql_3 = "SELECT balance FROM `myfund_wallet_balance` where member_id='" . $member_id . "'";
                            $_tq1 = mysqli_query($con, $sql_3);
                            $n1 = mysqli_num_rows($_tq1);
                            if ($n1 == 0) {
                                $balance = 0;
                                $new_balance = $balance + $amount;
                                $sql_balance = "INSERT into myfund_wallet_balance (member_id, balance, type, amount) values ('" . $member_id . "','" . $new_balance . "','Cr','" . $amount . "')";
                                $_tq3 = mysqli_query($con, $sql_balance);
                            } else {
                                $_tr1 = mysqli_fetch_array($_tq1);
                                $balance = $_tr1['balance'];
                                $new_balance = $balance + $amount;

                                $sql_balance = "UPDATE myfund_wallet_balance set balance='" . $new_balance . "', type='Cr', amount='" . $amount . "' where member_id='" . $member_id . "'";
                                $_tq3 = mysqli_query($con, $sql_balance);
                            }
                            $sql_transaction = "INSERT into myfund_wallet_transaction (member_id, particulars, txn_date, transaction_type, credit, balance, txn_date_time, txid, created_by) values ('" . $member_id . "','ADD_BALANCE_BY_QR_SCAN_ADMIN_QR','" . $now . "','Cr','" . $amount . "','" . $new_balance . "','" . $now . "','" . $txnid . "','" . $action_by . "')";
                            $_tq2 = mysqli_query($con, $sql_transaction);
                            if (!$_tq2 || !$_tq3) {
                                $balanceError = true;
                            } else {
                                $balanceError = false;
                            }
                        } else {
                            $balanceError = true;
                        }
                    }
                }

                if ($tq2 && $balanceError == false) {
                    mysqli_commit($con);
                    $_SESSION['s'] = "Deposit Successfully done.";
                } else {
                    mysqli_rollback($con);
                    $_SESSION['e'] = "Sorry! No data deposit data found.";
                }
                $con->close();
            } else {
                $_SESSION['e'] = $message;
            }
        }
    } else {
        $_SESSION['e'] = "Please fill form address and amount properly.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php include('include/title.php'); ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php include('include/header-common-file.php'); ?>
    <style>
        @media only screen and (max-width: 600px) {
            #memberAddress {
                font-size: 12px;
            }
        }
    </style>
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
                            <h2>Balance Deposit</h2>
                            <p>Balance Deposit</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel">

                <div class="formbox text-center">
                    <?php include('include/alert.php'); ?>
                    <p>Scan & Pay</p>
                    <p style="font-size: 22px; color : red;">Please Choose BEP20 / BEP20 network after scan. Otherwise your payment will be cancelled</p>

                    <img alt="Loading...." title="Scan and Pay" src="https://image-charts.com/chart?chs=100x100&amp;cht=qr&amp;chl=<?php echo $admin_address ?>" class="img-fluid">

                    <br><br>
                    <span id="memberAddress" style="color:#000"><?php echo $admin_address ?></span>
                    <br><br>
                    <p class="text-secondary">
                        <a style="color:#15b921;" class="mr-2" href="whatsapp://send?text=<?php echo $admin_address; ?>"><i class="fa fa-whatsapp"></i></a>

                        <a style="color:#008ef3;" class="mr-2" href="tg://msg_url?url=<?php echo PROJECT_URL; ?>&amp;text=<?php echo $admin_address; ?>"><i class="fa fa-telegram"></i></a>

                        <a onclick="copyToClipboard();" title="Copy to clipboard" style="color:#f10ca0;">
                            <i class="fa fa-files-o" aria-hidden="true" style="cursor: copy;"></i>
                        </a>
                    </p>
                    <p class="text-warning">Please validate your payment after the transaction</p>
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-md-4 offset-md-4">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <input type="text" name="user_form_address" class="form-control" placeholder="Transaction Hash" required />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" name="user_amount" class="form-control" placeholder="Amount" required />
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" name="submit" id="submit" value="Save" class="btn btn-primary">Validate Payment</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="transactionsDiv" style="padding-top: 20px;">
                        Loading...
                    </div>

                </div><!--formbox-->

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

    <script>
        function copyToClipboard() {
            //console.time('time1');
            //$('#copyBtn_'+id).text('Copied');

            var temp = $("<input>");
            $("body").append(temp);
            temp.val($('#memberAddress').text()).select();
            document.execCommand("copy");
            temp.remove();
            alert('Copied');
        }
    </script>

    <script type="text/javascript">
        $.ajax({
            type: "get",
            url: "get-add-fund-transactios-on-admin.php",
            datatype: "html",
            success: function(data) {
                $('#transactionsDiv').html(data);
                //console.log('div updated');
            }
        });


        function copyToClipboard2(id) {

            var temp = $("<input>");
            $("body").append(temp);
            temp.val($('#address_' + id).text()).select();
            document.execCommand("copy");
            temp.remove();
            alert('Copied');
        }
    </script>

</body>

</html>