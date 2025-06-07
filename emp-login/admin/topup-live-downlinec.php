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

    if($data['mem_code'] == '') {
        $_SESSION['e'] = "ID missing.";
    }
    else if($data['amount'] == '' || $data['amount'] <= 0) {
        $_SESSION['e'] = "Invalid amount.";

    }
    else if($data['amount'] < 1000) {
        $_SESSION['e'] = "Minimum investment 1000";

    }
    else {

        $roi_type = 1;

        $mem_code = $data['mem_code'];
        $_amount = $data['amount'];

        $con = $db->connect();
        $md = member_code($con, $mem_code);
        $db->dbDisconnet($con);
        $valid_member = $md['valid_member'];
        if($valid_member == 0) {
            $_SESSION['e'] = "Invalid member. Enter member ID correctly.";
            header("Location: $back");
            die;
        }
        else {

            $member_id = $md['member_id'];

            $package_id = 2;
            $wallet_used = '';

            $sql = "CALL MEMBER_PACKAGE_TOPUP('" . $mem_code . "','" . $package_id . "','" . $_amount . "','" . $action_by . "','" . $now . "', '" . $wallet_used . "', '" . $roi_type . "')";
            //echo $sql; die;

            $con = $db->connect();
            $q1 = mysqli_query($con, $sql);
            $db->dbDisconnet($con);
            $r1 = mysqli_fetch_assoc($q1);
            $n = $r1['return_id'];

            if($n == 1) {
                // --------------- Start : email ------------------

                $arr4email = [
                    'name' => $md['name'],
                    'amount' => $_amount,
                    'investment_type' => 'Live Trade Investment',
                ];
                $mail_to = $md['email'];
                $mail_subject = "Live Trade Investment Confirmation";
                $mail_message = getInvestmentEmailHtml($arr4email);
                mm_smtp($mail_to, $mail_subject, $mail_message);
                // --------------- End : email ------------------


                $_SESSION['s'] = "Investment successfull.";
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
