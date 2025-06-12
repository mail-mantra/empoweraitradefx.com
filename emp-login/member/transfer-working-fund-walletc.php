<?php
//die('under maintenance ');
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$now = now();
$ip = get_ip();
$session_id = session_id();

if (!isset($_SERVER['HTTP_REFERER'])) {
	$systemDenied = true;
	include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'Proceed') {

	// $_SESSION['POST']=$_POST;
	// $_SESSION['POST']['back'] = $back;

	// $mem_code = abs(trim($_POST['mem_code']));


	// $user_otp = ($_POST['user_otp']) ? trim($_POST['user_otp']) : '';
	// $system_otp = $_SESSION['transfer_rf_wallet_otp'] ?: '';

	// if (empty($user_otp)) {
	// 	$_SESSION['e'] = "Invalid OTP.";
	// } elseif (empty($system_otp)) {
	// 	$_SESSION['e'] = "Invalid OTP.";
	// } elseif ($user_otp != $system_otp) {
	// 	$_SESSION['e'] = "Invalid OTP.";
	// } else {

	/*$system_otp = $_SESSION['transfer_working_wallet_otp'];
    	$system_to_mem_code = $_SESSION['transfer_working_wallet_to_mem_code'];
    	$system_amount = $_SESSION['transfer_working_wallet_amount'];
    	$system_mem_code = $_SESSION['transfer_working_wallet_mem_code'];*/

	$con = $db->connect();
	$working_wallet_balance = get_wallet_balance_of_member($con, $user_id, 'working_wallet_balance', $domainId);
	// $ref_id = get_last_transaction_wallet_id_of_member($con,$user_id,'income_wallet_transaction');
	foreach ($_POST as $key => $value) {
		$data[$key] = prevent_injection($con, $value);
	}
	$db->dbDisconnet($con);

	$amount = abs(trim($data['amount']));

	if ($amount == '') {
		$_SESSION['e'] = "Please enter the amount.";
	} else if ($amount < 1) {
		$_SESSION['e'] = "Minimum transfer amount is 1";
	} else if ($data['amount'] != $amount) {
		$_SESSION['e'] = "Invalid amount.";
	}/*else if($data['amount']%100!=0){
    		$_SESSION['e']="You are allowed to transfer money in multiple of Rs.100/- only.";
    		
    	}*/ else if ($working_wallet_balance < $amount || $working_wallet_balance <= 0) {
		$_SESSION['e'] = "Your wallet balance is low to transfer";
	}/*  elseif ($to_mem_code == $user_code) {
    		$_SESSION['e'] = "Self Transfer not Possible";
    		
    	} elseif ($user_id == $to_member_id) {
    		$_SESSION['e'] = "Self Transfer not Possible";
    		
    	} elseif ($function_member_code['valid_member'] != 1) {
    		$_SESSION['e'] = "Invalid Member Code";
    		
    	} */
	/*else if($user_otp == '')
    	{
    		$e = "OTP can't be Blank ...!";
    		$_SESSION['e'] = $e;
    	}
    	else if($user_otp != $system_otp)
    	{
    		$e = "Input Correct OTP....!";
    		$_SESSION['e'] = $e;
    	}
    	else if($system_mem_code != $user_code)
    	{
    		$e = "Invalid System Member....!";
    		$_SESSION['e'] = $e;
    	}
    	else if($system_amount != $amount)
    	{
    		$e = "Invalid System Amount....!";
    		$_SESSION['e'] = $e;
    	}
    	else if($system_to_mem_code != $to_mem_code)
    	{
    		$e = "Invalid System to Member....!";
    		$_SESSION['e'] = $e;
    	}*/ else {
		/*downline ck*/
		/*$con=$db->connect();
            $is_downline = is_downline($con, $user_code, $to_mem_code);
            $db->dbDisconnet($con);
            if (!$is_downline){
                $_SESSION['e']="Sorry!! The member is not your Downline...";
                header("Location:$back");
                die;
            }*/
		/*downline ck*/

		// if (isset($_SESSION['transfer_rf_wallet_otp'])) {
		// 	unset($_SESSION['transfer_rf_wallet_otp']);
		// }
		// if (isset($_SESSION['transfer_rf_wallet_amount'])) {
		// 	unset($_SESSION['transfer_rf_wallet_amount']);
		// }
		// if (isset($_SESSION['transfer_rf_wallet_mem_code'])) {
		// 	unset($_SESSION['transfer_rf_wallet_mem_code']);
		// }
		// if (isset($_SESSION['transfer_rf_wallet_to_mem_code'])) {
		// 	unset($_SESSION['transfer_rf_wallet_to_mem_code']);
		// }


		$from_particulars = 'SELF_FUND_TRANSFER_TO_FUND_WALLET';
		$to_particulars = 'SELF_FUND_TRANSFER_FROM_WORKING_WALLET';

		$to_wallet = 'myfund_wallet';
		$from_wallet = 'working_wallet';

		//$sql = "CALL DYNAMIC_OTHER_MEMBER_MONEY_TRANSFER_WITH_ADMIN_TDS('".$from_wallet."','".$to_wallet."','".$user_id."', '".$to_member_id."','".$amount."','".$from_particulars."','".$to_particulars."','".$action_by."','".$now."')";

		$sql = "INSERT INTO `transfer_working_wallet_balance`(`from_member_id`, `to_member_id`, `amount`, `from_particulars`, `to_particulars`, `created_by`, `created_on`, `created_session_id`, `created_ip`) VALUES ('" . $user_id . "', '" . $user_id . "', '" . $amount . "', '" . $from_particulars . "', '" . $to_particulars . "', '" . $user_code . "', '" . now() . "', '" . session_id() . "', '" . get_ip() . "')";
		$con = $db->connect();
		$q = mysqli_query($con, $sql);
		$db->dbDisconnet($con);

		/*$res = mysqli_fetch_array($q);
    		$n = $res['return_id'];*/

		if ($q) {
			$_SESSION['s'] = $amount . " has successfully transferred .";

			$back .= "?reset_data=1";
			header("Location: $back");
			die;
		} else {

			$_SESSION['e'] = "Temporary Error...! ";
		}
	}
	//}
	header("Location: $back");
	die;
} else {
	$systemDenied = true;
	include('include/forced-logout.php');
}
