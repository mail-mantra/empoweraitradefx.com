<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$now = now();

if (!isset($_SERVER['HTTP_REFERER'])) {
    $systemDenied = true;
    include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['amount']) && isset($_POST['payment_type'])) {

    $con = $db->connect();
    foreach ($_POST as $key => $value) {
        //$data[$key] = prevent_injection($con,$value);
        $data[$key] = $value;
    }
    $db->dbDisconnet($con);

    $transaction_id = $data['transaction_id'] ?? '';
    $from_whom = $data['from_whom'] ?? '';
    $which_bank = $data['which_bank'] ?? 'NULL';
    $ifsc = $data['ifsc'] ?? 'NULL';
    $cdate = $data['cdate'] ?? 'NULL';
    $transaction_hash = $data['transaction_hash'] ?? '';
    $from_address = $data['from_address'] ?? '';
    $mobile = $data['mobile'] ?? '';
    $broker_name = $data['broker_name'] ?? '';
    $broker_server_name = $data['broker_server_name'] ?? '';
    $user_name = $data['user_name'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($data['amount'])) {
        $_SESSION['e'] = "Please enter the amount.";
    } elseif ($data['amount'] <= 0) {
        $_SESSION['e'] = "Please enter a valid amount.";
    } elseif (empty($data['payment_type'])) {
        $_SESSION['e'] = "Please select the payment mode.";
    } elseif (empty($mobile)) {
        $_SESSION['e'] = "Please enter your mobile number.";
    } else {
        include('../ajax/live-account-payment-data-validation.php');

        if (!empty($_SESSION['e'])) {
            header('location:' . $back);
            die;
        }

        if ($_FILES['screenshots']['size'] <= 0) {
            $_SESSION['e'] = "Please upload a valid screenshot image.";
        } else {
            $fnc_screenshots_photo_file = img_save('screenshots', 800, 500, '../assets/images/screenshots/');
            if ($fnc_screenshots_photo_file['msg'] == 'Success') {
                $screenshots = $fnc_screenshots_photo_file['img_name'];
                $amount = abs($data['amount']);
                $payment_type = $data['payment_type'];
                $status = 'Request';
                $req_id = uniqid('FR');
                $cdate = "'" . $cdate . "'";

                $sql = "INSERT INTO `live_account_request`(`member_id`, `txn_id`, `amount`, `request_status`, `created_by`, `created_on`, `modified_by`, `modified_on`, `mobile`, `broker_name`, `broker_server_name`, `user_name`, `password`) 
                        VALUES('$user_id', '$req_id', '$amount', '$status', '$action_by', '$now', '$action_by', '$now', '$mobile', '$broker_name', '$broker_server_name', '$user_name', '$password')";

                $sql2 = "INSERT INTO `live_account_request_details`(`member_id`, `ref_txn_id`, `amount`, `payment_type`, `transaction_id`,  `from_whom`, `date`, `which_bank`, `ifsc`, `transaction_hash`, `from_address`, `screenshots`, `created_by`, `created_on`, `updated_by`, `updated_on`)
                         VALUES('$user_id', '$req_id', '$amount', '$payment_type', '$transaction_id', '$from_whom', $cdate, '$which_bank', '$ifsc', '$transaction_hash', '$from_address', '$screenshots', '$action_by', '$now', '$action_by', '$now')";

                $con = $db->connect();
                mysqli_autocommit($con, FALSE);

                $q = mysqli_query($con, $sql);
                if ($con->errno) {
                    $_SESSION['e'] = $con->error;
                    mysqli_rollback($con);
                } else {
                    $q2 = mysqli_query($con, $sql2);
                    if ($con->errno) {
                        $_SESSION['e'] = $con->error;
                        mysqli_rollback($con);
                    } else {
                        mysqli_commit($con);
                        $_SESSION['s'] = "Your request for Rs." . $data['amount'] . " has been successfully submitted. Wait for approval.";
                    }
                }

                $db->dbDisconnet($con);
            } else {
                $_SESSION['e'] = $fnc_screenshots_photo_file['msg'];
            }
        }
    }
} else {
    $_SESSION['e'] = 'Invalid Call';
}
header('location:' . $back);
die;
