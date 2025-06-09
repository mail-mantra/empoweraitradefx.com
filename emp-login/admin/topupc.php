<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
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


    $con = $db->connect();
    foreach($_POST as $key => $value) {
        $data[$key] = prevent_injection($con, $value);
    }
    $db->dbDisconnet($con);

    if($data['mem_code'] == '') {
        $_SESSION['e'] = "ID missing.";
    }
    elseif($data['amount'] == '' || $data['amount'] <= 0) {
        $_SESSION['e'] = "Invalid amount.";

    }
    else if($data['amount'] < 50) {
        $_SESSION['e'] = "Minimum amount 50";

    }
    else if($data['amount'] > 50 && ($data['amount'] % 50 > 0)) {
        $_SESSION['e'] = "Enter value in multiple of 50";
    }
    else {

        $proceed = 0;

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

        $member_id = $md['member_id'];

        $con = $db->connect();
        $qpk = mysqli_query($con, "select amount from member_package_update_log where member_id='" . $member_id . "' order by id desc limit 1");
        $npk = mysqli_num_rows($qpk);
        $db->dbDisconnet($con);
        if($npk == 0) {
            $proceed = 1;
        }
        else {
            $rpk = mysqli_fetch_assoc($qpk);
            $last_package_amount = $rpk['amount'];

            if($_amount > $last_package_amount) {
                $proceed = 1;

            }
            else {
                $proceed = 0;

                $_SESSION['e'] = "You must deposit with more than your last deposit amount.";
                header("Location: $back");
                die;
            }
        }


        if($proceed == 1) {

            $inv_date = $current_date;

            $sql = "CALL MEMBER_PACKAGE_TOPUP('" . $mem_code . "','" . $_amount . "','" . $action_by . "','" . $now . "', '', '" . $inv_date . "')";
            //	echo $sql; die;

            $con = $db->connect();
            $q1 = mysqli_query($con, $sql);
            $db->dbDisconnet($con);
            $r1 = mysqli_fetch_assoc($q1);
            $n = $r1['return_id'];

            if($n == 1) {
                $_SESSION['s'] = $_amount . " investment successfull.";
            }
            else {
                $_SESSION['e'] = $r1['var_message'];
            }

        }
        else {
            $_SESSION['e'] = "Temporary Error.";
        }

    }

    header("Location: $back");
    die;

}
else {
    $systemDenied = true;
    include('include/forced-logout.php');
}
