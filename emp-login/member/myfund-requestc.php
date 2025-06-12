<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['amount']) && isset($_POST['payment_type'])) {

    // extract($_POST);

    $con = $db->connect();
    foreach ($_POST as $key => $value) {
        //$data[$key] = prevent_injection($con,$value);
        $data[$key] = $value;
    }
    $db->dbDisconnet($con);

    $from_whom = '';
    $from_which_branch = '';
    $from_which_bank = '';
    $from_which_ifsc = '';
    $from_which_ac_no = '';
    $order_no = '';

    $to_whom = '';
    $to_which_bank = '';
    $to_which_branch = '';
    $to_which_ac_no = '';
    $to_which_ifsc = '';

    $cheque_number = '';
    $cheque_date = 'NULL';

    $transaction_id = '';
    $from_mobile = '';
    $from_email = '';
    $to_mobile = '';
    $from_upi = '';
    $to_upi = '';
    $cdate = 'NULL';
    $ctime = '';
    $transaction_date = 'NULL';
    $company_account_id = "NULL";
    $ifsc = "NULL";
    $which_bank = "NULL";
    $neft_reference = "NULL";
    $email = "NULL";
    $mobile = "NULL";
    $screenshots = "NULL";
    $utr_no = "NULL";
    $payment_mode = "NULL";


    if ($data['amount'] == '') {

        $_SESSION['e'] = "Please enter the amount.";
    } else if ($data['amount'] <= 0) {

        $_SESSION['e'] = "Please enter the valid amount.";
    } else if ($data['payment_type'] == '') {

        $_SESSION['e'] = "Please select the payment mode.";
    } else {

        if ($_FILES['screenshots']['size'] <= 0) {
            $_SESSION['e'] = "Please upload a valid screenshot image.";
        } else {
            $fnc_screenshots_photo_file = img_save('screenshots', 800, 500, '../assets/images/screenshots/');
            if ($fnc_screenshots_photo_file['msg'] == 'Success') {

                include('../ajax/payment-data-validation.php');

                $screenshots = $fnc_screenshots_photo_file['img_name'];

                $amount = abs($data['amount']);
                $payment_type = $data['payment_type'];
                $status = 'Request';
                $req_id = uniqid('FR');


                $cdate = "'" . $cdate . "'";

                $usd_amount = $data['amount'] / USD_RATE;

                $sql = "INSERT INTO `myfund_request`(`member_id`, `txn_id`, `amount`, `mf_amount`, `request_status`, `created_by`, `created_on`, `modified_by`, `modified_on`) VALUES('" . $user_id . "','" . $req_id . "', '" . $data['amount'] . "', '" . $usd_amount . "', '" . $status . "','" . $action_by . "','" . $now . "','" . $action_by . "','" . $now . "')";

                $sql2 = "INSERT INTO `myfund_request_details`(`member_id`, `ref_txn_id`, `amount`, `payment_type`, `to_whom`, `date`, `which_bank`, `ifsc`, `cheque_number`, `cheque_date`, `from_which_ac_no`, `from_whom`, `from_which_bank`, `from_which_branch`, `from_which_ifsc`, `to_which_bank`, `to_which_ac_no`, `to_which_branch`, `to_which_ifsc`, `neft_reference`, `transaction_id`, `transaction_date`, `time`, `from_mobile`, `to_mobile`, `email`, `from_email`, `mobile`, `from_upi`, `to_upi`,`order_no`, `screenshots`, `utr_no`, `payment_mode`, `created_by`, `created_on`, `updated_by`, `updated_on`)
        		VALUES('" . $user_id . "','" . $req_id . "', '" . $amount . "', '" . $payment_type . "', '" . $to_whom . "', " . $cdate . ", 
        		'" . $which_bank . "', '" . $ifsc . "', '" . $cheque_number . "', " . $cheque_date . ", '" . $from_which_ac_no . "', 
        		'" . $from_whom . "', '" . $from_which_bank . "', '" . $from_which_branch . "', '" . $from_which_ifsc . "', 
        		'" . $to_which_bank . "','" . $to_which_ac_no . "', '" . $to_which_branch . "', '" . $to_which_ifsc . "', 
        		'$neft_reference' , '" . $transaction_id . "', " . $transaction_date . ", '" . $ctime . "', 
        		'" . $from_mobile . "', '" . $to_mobile . "', '" . $email . "', '" . $from_email . "', '" . $mobile . "', '" . $from_upi . "', 
        		'" . $to_upi . "', '" . $order_no . "','" . $screenshots . "','" . $utr_no . "','" . $payment_mode . "','" . $action_by . "', '" . $now . "','" . $action_by . "', '" . $now . "')";

                // echo $sql . '<br><br>' . $sql2;
                // die;

                $success = 0;

                $con = $db->connect();
                mysqli_autocommit($con, FALSE);

                try {
                    $q = mysqli_query($con, $sql);
                    if ($con->errno) {
                        $con->rollback();
                        $_SESSION['e'] = $con->error;
                    } else {
                        $q2 = mysqli_query($con, $sql2);
                        if ($con->errno) {
                            $con->rollback();
                            $_SESSION['e'] = $con->error;
                        } else {
                            mysqli_commit($con);
                            $success = 1;
                        }
                    }
                } catch (Exception $e) {
                    mysqli_rollback($con);
                    $_SESSION['e'] = $sql . $e->getMessage();
                }

                $db->dbDisconnet($con);

                if ($success == 1) {
                    $_SESSION['s'] = "Your myfund request of USD " . number_format($usd_amount, 2) . " has successfully submitted. Wait for approval.";

                    /*$msg = "Congratulation, Fund_Request," . $data['amount'] . "/- has been successfully withdrawal from your account. if any query visit www.grmoney.net.in";
                    $con = $db->connect();
                    sms_mm($con, '', '', '9123691585', $msg, $action_by, '1207162633075182804');
                    $db->dbDisconnet($con);*/
                } else {
                    // $_SESSION['e'] = "Temporary Error...!";
                }
            } else {
                $_SESSION['e'] = $fnc_screenshots_photo_file['msg'];
            }
        }
    }
} else {
    //    $systemDenied = true;
    //    include('include/forced-logout.php');
    $_SESSION['e'] = 'INVALID METHOD';
}
header('location:' . $back);
die;

/*if(isset($_REQUEST['response']) && $_REQUEST['response'] == 'json') {
    header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    header("Pragma: no-cache"); // HTTP 1.0.
    header("Expires: 0"); // Proxies.
    header("Content-Type: application/json;charset=utf-8");

    $result['back'] = $back;
    $result['status'] = 1;
    $result['data'] = array();
    $result['message'] = 'Temporary Problem' . __LINE__;
    $result['w'] = '';

    if(isset($_SESSION['s']) && $_SESSION['s']) {
        $result['status'] = 0;
        $result['message'] = $_SESSION['s'];
        $result['refresh'] = 1;
        // $result['redirect'] = $back;
        unset($_SESSION['s']);
    }
    else if(isset($_SESSION['e']) && $_SESSION['e']) {
        $result['message'] = $_SESSION['e'];
        unset($_SESSION['e']);
    }
    else if(isset($_SESSION['w']) && $_SESSION['w']) {
        $result['message'] = "Test" . $_SESSION['w'];
        $result['w'] = $_SESSION['w'];
        unset($_SESSION['w']);
    }
    else {

    }
    echo json_encode($result, JSON_PRETTY_PRINT);
}
else {
    header('location:' . $back);
    die;
}*/
