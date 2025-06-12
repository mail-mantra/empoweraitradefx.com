<?php
//die;
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
include('../lib/mail_sms.php');
$db = new Database();

$now = now();
$ip = get_ip();
$session_id = session_id();


if (!isset($_SERVER['HTTP_REFERER'])) {
    $systemDenied = true;
    include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) && $_POST['submit'] == 'Proceed') {

    $con = $db->connect();
    foreach ($_POST as $key => $value) {
        $data[$key] = prevent_injection($con, $value);
    }
    //$gm = member_id($con,$user_id);
    $db->dbDisconnet($con);

    if ($data['amount'] == '') {
        $_SESSION['e'] = "Please enter the amount.";
    } else if ($data['amount'] < 10) {
        $_SESSION['e'] = "Please enter the valid amount. Minimum amount is 10.";
    } else if ($data['wallet_name'] == '' || $data['crypto_address'] == '') {
        $_SESSION['e'] = "Please fill all the fileds completely.";
    } else {

        $withdraw_min_limit = 10;
        $wallet_name = $data['wallet_name'];
        $usd_amount = $data['amount'];

        /* $con = $db->connect();
        $member_data = member_id_details($con, $user_id);
        $db->dbDisconnet($con);
        if (empty($member_data['crypto_address_trc'])) {
            $_SESSION['e'] = "Please update your crypto address.";
            header("Location: $back");
            die;
        }
        $crypto_address = $member_data['crypto_address_trc']; */
        $crypto_address = $data['crypto_address'];

        if ($data['wallet_name'] == 'working') {
            $con = $db->connect();
            $balance_wallet = get_wallet_balance_of_member($con, $user_id, 'working_wallet_balance', $domainId);
            $balance_ref_id = get_last_transaction_wallet_id_of_member($con, $user_id, 'working_wallet_transaction', $domainId);
            $db->dbDisconnet($con);
        } else if ($data['wallet_name'] == 'roi') {
            $con = $db->connect();
            $balance_wallet = get_wallet_balance_of_member($con, $user_id, 'roi_wallet_balance', $domainId);
            $balance_ref_id = get_last_transaction_wallet_id_of_member($con, $user_id, 'roi_wallet_transaction', $domainId);
            $db->dbDisconnet($con);
        } else {
            $_SESSION['e'] = "Wrong wallet..!";
            header("Location: $back");
            die;
        }

        if ($usd_amount < $withdraw_min_limit) {
            $_SESSION['e'] = "Amount must be greater than or equal to $withdraw_min_limit.";
            header("Location: $back");
            die;
        } else if ($balance_wallet < $usd_amount || $balance_wallet == 0) {
            $_SESSION['e'] = "Your wallet balance is low for withdrawal.";
            header("Location: $back");
            die;
        } else {


            /* get trx */
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.coingecko.com/api/v3/simple/price?ids=tether&vs_currencies=usd',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Cookie: __cf_bm=CeZ.4sdLgEIybIOXgZ9mwH0GYXf2rrv_fED5d8exqms-1722617513-1.0.1.1-zlIxD6AOkps7RAJ4aNl7PC1N9ajam1NG04.NGg.SuXENPmQJqtw9pdwG.gEL2CqQn1wSzQmJirgxuEObMK_iyw'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $usd_value = json_decode($response, true)['tether']['usd'];
            if (empty($usd_value)) {
                $_SESSION['e'] = "Error in getting TRC rate. Please try after sometime!";
                header("Location: $back");
                die;
            }
            //$usdt_amount = (1 / $usd_value) * $usd_amount;

            /* $con = $db->connect();
            $sell_rate = get_sale_rate($con);
            $con->close(); */

            //$usdt_amount = (($usd_amount * 75) / $sell_rate);
            /* .get trx */


            $sql = "";
            if ($data['wallet_name'] == 'working') {
                $sql = "CALL REDEEM_FROM_WORKING_WALLET_BEP20('" . $user_id . "', '" . $usd_amount . "', '" . $usd_value . "', '" . $crypto_address . "', '" . $now . "')";
            }
            if ($data['wallet_name'] == 'roi') {
                $sql = "CALL REDEEM_FROM_ROI_WALLET_BEP20('" . $user_id . "', '" . $usd_amount . "', '" . $usd_value . "', '" . $crypto_address . "', '" . $now . "')";
            }

            /* if ($data['wallet_name'] == 'roi') {
                $sql = "CALL REDEEM_FROM_ROI_WALLET_TRC('" . $user_id . "', '" . $usd_amount . "', '" . $usdt_amount . "', '" . $crypto_address . "', '" . $now . "')";
            } */
            //echo $sql; die;

            $con = $db->connect();
            $q = mysqli_query($con, $sql);
            $db->dbDisconnet($con);
            $res = mysqli_fetch_array($q);
            $n = $res['return_id'];
            if ($n >= 1) {
                $_SESSION['s'] = "USDT " . $usd_amount . " has been successfully withdrawal from your account.";
            } else {
                $_SESSION['e'] = "Temporary Error...!";
            }
        }
    }
    header("Location: $back");
    die;
} else {
    $systemDenied = true;
    include('include/forced-logout.php');
}
