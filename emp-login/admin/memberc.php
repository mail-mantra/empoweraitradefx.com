<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
include('../lib/mail_sms.php');
include('../lib/smtp_function.php');
$db = new Database();

$now = now();
$ip = get_ip();
$session_id = session_id();


if (!isset($_SERVER['HTTP_REFERER'])) {
    $systemDenied = true;
    include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'Submit') {

    $con = $db->connect();
    foreach ($_POST as $key => $value) {
        $data[$key] = prevent_injection($con, $value);
    }
    $db->dbDisconnet($con);


    if ($data['intro'] == '' || $data['name'] == '' /*|| $data['pan_no'] == ''*/ || $data['email'] == '' || $data['mobile'] == '') {
        $_SESSION['e'] = "Please enter the mandatory fields";
    } else if (!is_numeric($data['mobile']) || strlen($data['mobile']) < 10 || strlen($data['mobile']) > 10) {
        $_SESSION['e'] = "Please enter a valid mobile no.";
    } else {

        // validate email
        if ($data['email'] != $_SESSION['joining_email'] || $data['user_otp'] != $_SESSION['joining_otp']) {
            $_SESSION['e'] = "Invalid OTP or Email.";
            header("Location: $back");
            die;
        }
        $data['pan_no'] = 'N/A';

        // $con = $db->connect();
        // $sql_em = mysqli_query($con, "select email from member where email='" . $data['email'] . "'");
        // $db->dbDisconnet($con);
        // if (mysqli_num_rows($sql_em) >= 1) {
        //     $_SESSION['e'] = "More than 1 ID is NOT ALLOWED in 1 Email ID.";
        //     header("Location: $back");
        //     die;
        // }

        // $con = $db->connect();
        // $sql_mob = mysqli_query($con, "SELECT member_id from member where mobile='" . $data['mobile'] . "'");
        // $db->dbDisconnet($con);
        // if (mysqli_num_rows($sql_mob) >= 1) {
        //     $_SESSION['e'] = "More than 1 ID is NOT ALLOWED in 1 Mobile.";
        //     header("Location: $back");
        //     die;
        // }

        // $con = $db->connect();
        // $sql_pan = mysqli_query($con, "SELECT member_id from member_details where pan_no='" . $data['pan_no'] . "'");
        // $db->dbDisconnet($con);
        // if (mysqli_num_rows($sql_pan) >= 3) {
        //     $_SESSION['e'] = "More than 3 ID is NOT ALLOWED in 1 PAN No.";
        //     header("Location: $back");
        //     die;
        // }

        /*
        $package_id = $data['package_id'];
        $con = $db->connect();
        $q1 = mysqli_query($con, "select * from join_package where id='" . $package_id . "'");
        $db->dbDisconnet($con);
        if (mysqli_num_rows($q1) == 1) {
            $r1 = mysqli_fetch_assoc($q1);
            $package_amount = $r1['package_amount'];
            $package_name = $r1['package_name'];
        } else {
            $_SESSION['e'] = "Invalid Package.";
            header("Location: $back");
            die;
        }*/

        $data['intro'] = trim($data['intro']);
        $data['intro'] = strtoupper($data['intro']);


        $status = 1;
        $capping = 0;

        $con = $db->connect();
        $intro_mtree = intro_mtree_generate($con, $data['intro']);
        $db->dbDisconnet($con);


        if ($data['intro'] == 'ADMIN' || $data['intro'] == 'admin') {
            $intro_level = 1;
            $intro_id = 0;
            $valid_intro = 1;
        } else {
            $con = $db->connect();
            $function_member = member_code($con, $data['intro']);
            $db->dbDisconnet($con);
            $in_level = $function_member['intro_level'];
            $intro_level = $in_level + 1;
            $intro_id = $function_member['member_id'];
            $valid_intro = $function_member['valid_member'];
        }

        if ($valid_intro == 0) {
            $_SESSION['e'] = "Invalid Introducer.";
            header("Location: $back");
            die;
        }


        $con = $db->connect();
        $mem_code = new_code($con, 'member', 'mem_code', PREFIX_MEMBER_CODE);
        $token = get_new_token($con);
        $db->dbDisconnet($con);
        $password = generatenumber(4, '123456789');


        $con = $db->connect();
        $sql21 = mysqli_query($con, "select * from member where mem_code='" . $mem_code . "'");
        $db->dbDisconnet($con);
        if (mysqli_num_rows($sql21) == 1) {
            $_SESSION['e'] = $mem_code . " already exists. Try with another ID";
            header("Location: $back");
            die;
        }

        $data['state'] = '';
        $data['country'] = '';
        $data['address'] = '';
        $data['adhar_no'] = '';

        $sql1 = "CALL ADD_MEMBER('" . $mem_code . "','" . $password . "','" . $intro_id . "','" . $data['intro'] . "','" . $now . "','" . $intro_level . "','" . $intro_mtree . "','" . $data['name'] . "','" . $data['mobile'] . "','" . $action_by . "','" . $now . "','" . $status . "','" . $session_id . "','" . $ip . "','','" . $data['state'] . "','" . $data['country'] . "','','" . strtoupper($data['pan_no']) . "','','" . $data['email'] . "','" . $data['address'] . "','" . $data['adhar_no'] . "','" . $token . "')";

        //echo $sql1; die;    

        $con = $db->connect();
        $q1 = mysqli_query($con, $sql1);
        $db->dbDisconnet($con);
        $r1 = mysqli_fetch_assoc($q1);
        $n = $r1['return_id'];

        if ($n >= 1) {
            /*$mobile = $data['mobile'];
            $mem_name = $data['name'];

            $msg = "Dear $mem_name Your Login ID is $mem_code and password is $password. Visit " . PROJECT_URL . " -ECOSOL";
            $con = $db->connect();
            sms_mm($con, '', '', $mobile, $msg, $action_by, '1207162918235447126');
            $db->dbDisconnet($con);*/

            /* mail*/
            $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                            <title>Mail</title>
                        </head>
                        <body>
                        <div style="width:500px; height:430px; margin:0 auto; padding:20px 5px; background:#292d35">
                            <div style="padding:0px 25px 15px 25px;">
                                <img src="https://empoweraitradefx.com/emp-login/web-assets/images/logo.png" style="width: 100px; display: block; margin: 0 auto;">
                            </div>
                            <div style="width:400px; height:300px; margin:0 auto; padding:25px; background:#fff; text-align: center">
                                <h5 style="font-family: Arial, Helvetica, sans-serif; font-size:15px; font-weight:bold; line-height:20px;">Welcome to ' . PROJECT_NAME . '</h5>
                                
                                <p style="font-family: Arial, Helvetica, sans-serif; font-size:15px; color:#3d3c41;  line-height:20px;">User ID : ' . $mem_code . ' </p>
                                <p style="font-family: Arial, Helvetica, sans-serif; font-size:15px; color:#3d3c41;  line-height:20px; margin-bottom:20px;">Password : ' . $password . ' </p>
                                <p style="font-family: Arial, Helvetica, sans-serif; font-size:14px; color:#3d3c41;  line-height:20px; padding-top:60px;">Please do not share with anyone.</p>
                            </div>
                            <p  style="font-family: Arial, Helvetica, sans-serif; font-size:14px; text-align:center; color:#FFFFFF;  line-height:28px;">© 2025 ' . PROJECT_NAME . '. All Rights Reserved </p>
                        </div>
                        </body>
                        </html>';

            $mail_to = $data['email'];
            $mail_subject = "Welcome To " . PROJECT_NAME;
            $mail_message = $message;
            mm_smtp($mail_to, $mail_subject, $mail_message);
            /* mail*/

            $_SESSION['s'] = "Joining Successful. Your Login ID is <strong>$mem_code</strong> and password is <strong>$password</strong>";
        } else {
            $_SESSION['e'] = "Temporary Error...!";
        }
    }

    header("Location: $back");
    die;
} else {
    $systemDenied = true;
    include('include/forced-logout.php');
}
