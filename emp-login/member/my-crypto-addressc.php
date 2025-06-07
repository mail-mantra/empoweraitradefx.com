<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
include_once '../lib/smtp_function.php';

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
		$data[$key] = prevent_injection($con, $value);
	}
	$db->dbDisconnet($con);


	if (!empty($_SESSION['crypto_address_bep20_update'])) {

		if ($data['otp'] != $_SESSION['crypto_address_bep20_update']['otp']) {
			$_SESSION['e'] = "Please enter a valid OTP..";
		} else {

			$con = $db->connect();
			$sql_update = "UPDATE member_details SET  
    							  crypto_address_bep20 =  '" . $_SESSION['crypto_address_bep20_update']['crypto_address_bep20'] . "'
    							  WHERE member_id ='" . $user_id . "'";

			if (mysqli_query($con, $sql_update)) {
				$_SESSION['s'] = "Your crypto address updated successfully.";
			} else {
				$_SESSION['e'] = "Temporary Error...!";
			}
			$db->dbDisconnet($con);

			unset($_SESSION['crypto_address_bep20_update']);
		}
	} else {

		if ($data['crypto_address_bep20'] == '') {
			$_SESSION['e'] = "Please enter the mandatory fields";
		} else {
			$sql1 = "select email from member where member_id='" . $user_id . "'";
			$con = $db->connect();
			$q1 = mysqli_query($con, $sql1);
			$db->dbDisconnet($con);
			$r1 = mysqli_fetch_assoc($q1);
			$email = $r1['email'];


			$mail_to = $email;
			$mail_subject = 'OTP From Perfex Coin';

			$otp = rand(1111, 9999);

			$_SESSION['crypto_address_bep20_update']['otp'] = $otp;
			$_SESSION['crypto_address_bep20_update']['crypto_address_bep20'] = $data['crypto_address_bep20'];

			$mail_message = '<div style="width:600px;  border:solid 1px #d9d9d9; margin:20px auto; background:#FFF;  overflow:hidden;">
			<div style="background:#FFF; margin:5px auto; padding:10px; overflow:hidden; text-align:center">
			<p style="font:bold 20px/30px Arial, Helvetica, sans-serif; color:#C90; margin:0;">your OTP for ' . PROJECT_NAME . ' Address Update is ' . $otp . '. Do not share it with anybody.</p>
			<br><br>
			<p style="font:bold 20px/30px Arial, Helvetica, sans-serif; color:#333; margin:0;">Thank You For Join With Us. Visit ' . PROJECT_URL . '</p>
			<br><br>
			</div>
			</div>';
			$res = mm_smtp($mail_to, $mail_subject, $mail_message);
			$_SESSION['s'] = 'OTP has been sent to your email address.';
		}
	}

	header("Location: $back");
	die;
} else {
	$systemDenied = true;
	include('include/forced-logout.php');
}
