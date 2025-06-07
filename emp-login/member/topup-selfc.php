<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
include('../lib/smtp_function.php');
$db = new Database();

$now = now();
$ip = get_ip();
$session_id = session_id();


if(!isset($_SERVER['HTTP_REFERER'])) {
    $systemDenied = true;
    include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];

if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'Submit') {

    //print_r($_POST); die;

    $con = $db->connect();
    foreach($_POST as $key => $value) {
        $data[$key] = prevent_injection($con, $value);
    }
    $db->dbDisconnet($con);

    if($data['amount'] == '' || $data['amount'] <= 0) {
        $_SESSION['e'] = "Invalid amount.";

    }
    else if($data['amount'] < 50) {
        $_SESSION['e'] = "Minimum amount 50";

    }
    else if($data['amount'] > 50 && ($data['amount'] % 50 > 0)) {
        $_SESSION['e'] = "Enter value in multiple of 50";

    }
    else {

        $package_id = 1;
        $_amount = $data['amount'];
        $roi_type = 1;
        $wallet_used = 'fund';

        $con = $db->connect();
        $wallet_balance = get_wallet_balance_of_member($con, $user_id, 'myfund_wallet_balance');
        $db->dbDisconnet($con);

        if($wallet_balance < $_amount || $wallet_balance <= 0) {
            $_SESSION['e'] = "Your wallet balance is low to topup.";

        }
        else {

            $sql = "CALL MEMBER_PACKAGE_TOPUP('" . $user_code . "','" . $package_id . "','" . $_amount . "','" . $action_by . "','" . $now . "', '" . $wallet_used . "', '" . $roi_type . "')";
            //echo $sql; die;

            $con = $db->connect();
            $q1 = mysqli_query($con, $sql);
            $db->dbDisconnet($con);
            $r1 = mysqli_fetch_assoc($q1);
            $n = $r1['return_id'];

            if($n == 1) {
                // --------------- Start : email ------------------
                $con = $db->connect();
                $md = member_code($con, $user_code);
                $db->dbDisconnet($con);

                $arr4email = [
                    'name' => $md['name'],
                    'amount' => $_amount,
                ];
                $mail_to = $md['email'];
                $mail_subject = "Community Trade Investment Confirmation";
                $mail_message = getInvestmentEmailHtml($arr4email);
                mm_smtp($mail_to, $mail_subject, $mail_message);
                // --------------- End : email ------------------

                $_SESSION['s'] = $_amount . " investment successfull.";
            }
            else {
                $_SESSION['e'] = $r1['var_message'];
            }
        }
    }

    header("Location: $back");
    die;
}
else {
    $systemDenied = true;
    include('include/forced-logout.php');
}
