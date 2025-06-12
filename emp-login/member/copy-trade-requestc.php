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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $con = $db->connect();
    foreach ($_POST as $key => $value) {
        $data[$key] = $value;
    }
    $db->dbDisconnet($con);
    if (!isset($data['mobile']) || !isset($data['email']) || !isset($data['password']) || !isset($data['investment_amount']) || !isset($data['broker_name'])) {
        $_SESSION['e'] = "Required fields are missing.";
        header('location:' . $back);
        die;
    }
    $mobile = $data['mobile'];
    $email = $data['email'];
    $password = $data['password'];
    $investment_amount = abs($data['investment_amount']);
    $broker_name = $data['broker_name'];
    $mt5_id = $data['mt5_id'] ?? '';
    $mt5_password = $data['mt5_password'] ?? '';

    if (empty($mobile)) {
        $_SESSION['e'] = "Please enter your mobile number.";
    } elseif (empty($email)) {
        $_SESSION['e'] = "Please enter your email.";
    } elseif (empty($password)) {
        $_SESSION['e'] = "Please enter your password.";
    } elseif ($investment_amount <= 0) {
        $_SESSION['e'] = "Please enter a valid investment amount.";
    } elseif (empty($broker_name)) {
        $_SESSION['e'] = "Please enter the broker name.";
    } else {
        $req_id = uniqid('FR');
        $status = 'Request';

        $sql = "INSERT INTO `copy_trade_request`(`member_id`, `txn_id`, `mobile`, `email`, `password`, `amount`, `broker_name`, `mt5_id`, `mt5_password`, `request_status`, `created_by`, `created_on`, `modified_by`, `modified_on`) 
                VALUES('$user_id', '$req_id', '$mobile', '$email', '$password', '$investment_amount', '$broker_name', '$mt5_id', '$mt5_password', '$status', '$action_by', '$now', '$action_by', '$now')";

        $con = $db->connect();
        mysqli_autocommit($con, FALSE);

        $q = mysqli_query($con, $sql);
        if ($con->errno) {
            $_SESSION['e'] = $con->error;
            mysqli_rollback($con);
        } else {
            mysqli_commit($con);
            $_SESSION['s'] = "Your request for Rs." . $investment_amount . " has been successfully submitted. Wait for approval.";
        }

        $db->dbDisconnet($con);
    }
} else {
    $_SESSION['e'] = 'Invalid Call';
}
header('location:' . $back);
die;
