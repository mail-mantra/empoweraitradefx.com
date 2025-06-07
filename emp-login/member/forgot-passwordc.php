<?php
session_start();
include('../class/DbClass.php');
include('../lib/my_function.php');
include('../lib/mail_sms.php');
include('../lib/smtp_function.php');
//include('../smtp/mail.php');
$db = new Database();

$now = now();
$back = $_SERVER['HTTP_REFERER'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['un'])) {
  // extract($_POST);
  $con = $db->connect();
  foreach ($_POST as $key => $value) {
    $data[$key] = prevent_injection($con, $value);
  }
  $db->dbDisconnet($con);


  $username = trim($data['un']);

  $sql1 = "SELECT a.mobile, a.email, a.name, a.mem_code, b.username, b.password FROM member a, member_login b where a.member_id=b.member_id and b.username='" . $username . "' ";
  $conn1 = $db->connect();
  $res1 = $conn1->query($sql1);
  $conn1->close();

  if ($res1->num_rows) {
    $arr1 = $res1->fetch_assoc();
    $username = $arr1['username'];
    $mem_code = $arr1['mem_code'];
    $name = $arr1['name'];
    $mobile = $arr1['mobile'];
    $email = $arr1['email'];
    $password = $arr1['password'];

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
                            <h5 style="font-family: Arial, Helvetica, sans-serif; font-size:30px; font-weight:bold; line-height:20px;">Password recovery from ' . PROJECT_NAME . '</h5>
                            
                            <p style="font-family: Arial, Helvetica, sans-serif; font-size:22px; color:#3d3c41;  line-height:20px;">User ID : ' . $mem_code . ' </p>
                            <p style="font-family: Arial, Helvetica, sans-serif; font-size:22px; color:#3d3c41;  line-height:20px; margin-bottom:20px;">Password : ' . $password . ' </p>
                            <p style="font-family: Arial, Helvetica, sans-serif; font-size:14px; color:#3d3c41;  line-height:20px; padding-top:60px;">Please do not share with anyone.</p>
                        </div>
                        <p  style="font-family: Arial, Helvetica, sans-serif; font-size:14px; text-align:center; color:#FFFFFF;  line-height:28px;">© 2025 ' . PROJECT_NAME . '. All Rights Reserved </p>
                    </div>
                    </body>
                    </html>';

    $mail_to = $email;
    $mail_subject = "Password recovery from " . PROJECT_NAME;
    $mail_message = $message;
    mm_smtp($mail_to, $mail_subject, $mail_message);
    /* mail*/

    /*$sms_message = "Dear " . $name . ", your Password is: " . $password . ".Thank You.\nTeam " . PROJECT_URL . " -ECOSOL";
    $con = $db->connect();
    $sms_result = sms_mm($con, '', '', $mobile, $sms_message, '', '1207162918337920965');
    $db->dbDisconnet($con);*/

    if ($sms_result) {
      $_SESSION['s'] = "Password Sent to your registered Email ID.";
      header("Location:index.php");
      die;
    } else {
      $_SESSION['e'] = 'Temporary Problem, Try again';
    }
  } else {
    $_SESSION['e'] = 'Invalid Userid or Mobile Number';
  }


  header("Location:" . $back);
  die;
} else {
  header("Location:./");
}
