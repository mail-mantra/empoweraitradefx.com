<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
include('../lib/mail_sms.php');
$db = new Database();

$today = today();
$now = now();


if (!isset($_SERVER['HTTP_REFERER'])) {
    $systemDenied = true;
    include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_REQUEST['rdm'] != '' && $_REQUEST['action'] != '') {

    $con = $db->connect();
    foreach ($_REQUEST as $key => $value) {
        $data[$key] = prevent_injection($con, $value);
    }
    $db->dbDisconnet($con);

    if ($data['rdm'] == '') {

        $_SESSION['e'] = "Cannot proceed further.";
    } else if ($data['action'] == 'pay') {

        $id = hash_decode($data['rdm']);

        $con = $db->connect();
        $q = mysqli_query($con, "select v.*, m.crypto_address_bep20 from pay_roi_voucher v join member_details m on v.m_id = m.member_id where v.id='" . $id . "' and v.withdraw_status=0");
        //$voucher_no = new_voucher_no($con);
        $db->dbDisconnet($con);
        $r = mysqli_fetch_assoc($q);

        if (!empty($r)) {

            $member_id = $r['m_id'];
            $request_no = $r['id'];
            $amount = round($r['amount']);
            $to_address = $r['crypto_address_bep20'];

            $mmArray = array(
                '=80MWFmMud',
                'kNq1DMw10N',
                'r1jNzEWNiV',
                'zYhJDZjVDM',
                'lVzY1IGM4A',
                'jZhV2MhJWY',
                '4UWM4MjMyI',
                'mY4EmZ5EGZ',
                '2MmMlhjNlN',
                'TOjFTZ3cjY',
                '2EWO4UTN4B',
                'zahFzT1M2d',
                'zgFbzBTUnh',
                'Dc',
            );
            $mmKey = '';
            foreach ($mmArray as $arr) {
                $mmKey = $mmKey . $arr;
            }
            $admin_private_key = hash_decode(strrev($mmKey));
            ///////////////////////////////


            $url = "http://143.110.247.255/api/sendToken";
            $post = '{
                    "privateKey": "' . $admin_private_key . '",
                    "toAddress": "' . $to_address . '",
                    "tokenContractAddress": "' . BEP_20_CONTRACT_ADDRESS . '",
                    "amount": ' . $amount . '
                    }';

            $response = curlPost($url, $post);

            $response_json = json_encode($response);
            $message = $response->message;
            @$hash = $response->hash;

            if (strpos($message, "error") || $hash == '') {
                $con = $db->connect();
                $tq1 = mysqli_query($con, "insert into api_send_response_bep20_roi(request_no,member_id, amount, response,created_on) values('" . $request_no . "','" . $member_id . "', '" . $amount . "', '" . $response_json . "','" . $now . "')");
                $db->dbDisconnet($con);
                $_SESSION['e'] = "Something went wrong. Please try again.";
            } else {

                $con = $db->connect();
                mysqli_autocommit($con, FALSE);

                $tq1 = mysqli_query($con, "insert into api_send_response_bep20_roi(request_no, member_id, to_address, amount, hash, response, created_on) values ('" . $request_no . "','" . $member_id . "','" . $to_address . "', '" . $amount . "', '" . $hash . "','" . $response_json . "','" . $now . "')");
                $tq2 = mysqli_query($con, "update pay_roi_voucher set withdraw_status=1, withdraw_date='" . $now . "', updated_by='" . $action_by . "' where id='" . $id . "' and withdraw_status=0");

                if ($tq1 && $tq2) {
                    mysqli_commit($con);
                    $success = 1;
                } else {
                    mysqli_rollback($con);
                    $success = 0;
                }
                $db->dbDisconnet($con);

                if ($success == 1) {

                    $_SESSION['s'] = "Amount successfully paid.";


                    /*sms*/
                    //$sms_message = "Dear ".$name."\n, your withdrawal request of Rs.".$voucher_amount."/- accepted on ".dmy($now).".It will be credited in your account in next 48 hrs.";
                    //$con=$db->connect();
                    //sms_mm($con,'','',$mobile,$sms_message,$action_by);
                    //$db->dbDisconnet($con);
                    /* /sms*/
                } else {
                    $_SESSION['e'] = "Temporary Error...!";
                }
            }
        } else {
            $_SESSION['e'] = "Error with id." . $id;
            header("Location: $back");
            die;
        }
    } else if ($data['action'] == 'reject') {
        die('Access denied');
        $id = hash_decode($data['rdm']);

        $con = $db->connect();
        $q = mysqli_query($con, "select * from redeem_money_bep20 where id='" . $id . "'");
        //$voucher_no = new_voucher_no($con);
        $db->dbDisconnet($con);
        $r = mysqli_fetch_assoc($q);

        $request_no = $r['id'];
        $member_id = $r['member_id'];
        $amount = $r['usd_amount'];
        $wallet_type = $r['wallet_type'];

        $request_no = 'REJ-' .  $request_no;



        $con = $db->connect();
        mysqli_autocommit($con, FALSE);

        if ($wallet_type == 'roi_wallet') {
            $wallet_balance = get_wallet_balance_of_member($con, $member_id, 'roi_wallet_balance');
            $transaction_table = 'roi_wallet_transaction';
            $balance_table = 'roi_wallet_balance';
        } else if ($wallet_type == 'roi_wallet') {
            $wallet_balance = get_wallet_balance_of_member($con, $member_id, 'roi_wallet_balance');
            $transaction_table = 'roi_wallet_transaction';
            $balance_table = 'roi_wallet_balance';
        } else if ($wallet_type == 'liquidity_wallet') {
            $wallet_balance = get_wallet_balance_of_member($con, $member_id, 'liquidity_wallet_balance');
            $transaction_table = 'liquidity_wallet_transaction';
            $balance_table = 'liquidity_wallet_balance';
        } else {
            $_SESSION['s'] = "Wrong Wallet Deteceted.";
            header("Location: $back");
            die;
        }

        $new_balance = $wallet_balance + $amount;

        $sql_txn = "insert into $transaction_table (member_id, particulars, txn_date, transaction_type, credit, balance, ref_id, created_by)
	                values('" . $member_id . "','REDEEM_MONEY_USDT_BEP20_REJECTED','" . $today . "','Cr','" . $amount . "','" . $new_balance . "','" . $request_no . "','" . $action_by . "')";

        $sql_bal = "update $balance_table set balance='" . $new_balance . "', type='Cr', amount='" . $amount . "' where member_id='" . $member_id . "'";

        $tq1 = mysqli_query($con, $sql_txn);
        $aff_rows1 = mysqli_affected_rows($con);

        $tq2 = mysqli_query($con, $sql_bal);
        $aff_rows2 = mysqli_affected_rows($con);

        $tq3 = mysqli_query($con, "update redeem_money_bep20 set paid_status=2, paid_date='" . $now . "', updated_by='" . $action_by . "' where id='" . $id . "' and paid_status=0");
        $aff_rows3 = mysqli_affected_rows($con);
        if ($tq1 && $tq2 && $tq3  && $aff_rows1 > 0 && $aff_rows2 > 0 && $aff_rows3 > 0) {
            mysqli_commit($con);
            $success = true;
        } else {
            mysqli_rollback($con);
            $success = false;
        }
        $db->dbDisconnet($con);
        if ($success) {
            $_SESSION['s'] = "Redeem successfully rejected.";
        } else {
            $_SESSION['e'] = "Temporary Error...!";
        }
    }
} else {
    $_SESSION['e'] = "Invalid call";
}
header("Location: $back");
die;
