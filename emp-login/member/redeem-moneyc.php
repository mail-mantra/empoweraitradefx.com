<?php
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



/* $withdraw_status=true;
$con = $db->connect();
$_q = mysqli_query($con, "SELECT * FROM `redeem_money` WHERE member_id='".$user_id."' and paid_status='0'");
$db->dbDisconnet($con);
if(mysqli_num_rows($_q)>0){
    $_SESSION['e'] = "You Cant Proceed Further ! Your Last Withdraw Request is Pending Now";
    header("Location: $back");
    die;
} */



/*$pan_verified = false;
$sql_pan_verified = "SELECT pan_verified FROM `member_details` WHERE `member_id`='".$user_id."'";
$mysql = $db->connect();
$res_pan_verified = $mysql->query($sql_pan_verified);
$mysql->close();
if($res_pan_verified->num_rows)
{
    $arr_pan_verified = $res_pan_verified->fetch_assoc();
    $pan_verified = $arr_pan_verified['pan_verified'];
}

if($pan_verified!=1)
{
    $_SESSION['e'] = "PAN Not verified yet..!";
    header("Location: ".$back);
    die;

}
else{

}*/
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'Proceed') {

	$con = $db->connect();
	foreach ($_POST as $key => $value) {
		$data[$key] = prevent_injection($con, $value);
	}

	$gm = member_id($con, $user_id);

	$amount = $data['amount'];



	if ($amount == '') {
		$_SESSION['e'] = "Please enter the amount.";
	} else if ($amount <= 0) {
		$_SESSION['e'] = "Please enter the valid amount.";
	} /* else if ($amount % 10 != 0) {
		$_SESSION['e'] = "Please enter the amount which is multiple of 10";
	} */ else  if ($data['bank_name'] == '' || $data['acc_name'] == '' || $data['acc_no'] == '' || $data['ifsc'] == ''  || $data['pan_no'] == '') {
		$_SESSION['e'] = "Please fill all the bank details completely.";
	} else {

		/* $con = $db->connect();
		$qrd = mysqli_query($con, "SELECT * from redeem_money where member_id='" . $user_id . "' and (paid_status=0 or paid_status=1) and DATE(created_on)='$current_date'");
		$db->dbDisconnet($con);
		if (mysqli_num_rows($qrd) >= 1) {
			$_SESSION['e'] = "Per day only one withdrawal is allowed.";
			header("Location: $back");
			die;
		} */

		/* get pan */
		$con = $db->connect();
		$q_pan = mysqli_query($con, "select pan_no from member_details where member_id='" . $user_id . "'");
		$db->dbDisconnet($con);
		$r_pan = mysqli_fetch_assoc($q_pan);
		$pan_no = $r_pan['pan_no'];
		if (empty($pan_no)) {
			if (!empty($data['pan_no'])) {
				$pan_no = strtoupper(trim($data['pan_no']));
				if (!is_valid_pan_format($pan_no)) {
					$_SESSION['e'] = "Entered PAN Card is not valid...!";
					header("Location: $back");
					die;
				}
			} else {
				$_SESSION['e'] = "Please Enter a valid PAN Card ...!";
				header("Location: $back");
				die;
			}
		}
		/* get pan */

		/*$con=$db->connect();
		$qr = mysqli_query($con,"SELECT created_on from redeem_money where member_id='".$user_id."' ORDER BY id DESC LIMIT 1");
		$db->dbDisconnet($con);
		if(mysqli_num_rows($qr) > 0){
			$qd = mysqli_fetch_assoc($qr);
			$last_redeem_date = explode(' ', $qd['created_on'])[0];
			$today = explode(' ', $now)[0];
			$date1 = new DateTime($last_redeem_date);
			$date2 = new DateTime($today);
			$interval = $date1->diff($date2);
			if ($interval->days  <= 7) {
				$days = (7-$interval->days != 0) ? 'after '.(7-$interval->days).' days' : 'Tommorow';
				$_SESSION['e']="Sorry.You can withdraw only one time in a week. Come back <b>".$days."</b> for withdrawl..";
				header("Location: $back");
				die;
			}
		}*/

		/* PAN card */
		/*$con=$db->connect();
		$no_of_account = no_of_account_by_pan($con,$user_id,$data['pan_no']);
		$db->dbDisconnet($con);
		if ($no_of_account >= 2) {
			$_SESSION['e']="Sorry.You Can't use this PAN <b>[ ".$data['pan_no']." ]</b>, because it is already used in <b>".$no_of_account."</b> other accounts...!";
			header("Location: $back");
			die;
		}*/
		/* /PAN card */


		/* amount check based on package*/

		$withdraw_min_limit = 10;
		$withdraw_max_limit = 40000;

		if ($data['wallet_name'] == 'working') {

			// if (date('d') == "1" || date('d') == "2" || date('d') == "3" || date('d') == "4" || date('d') == "16" || date('d') == "17" || date('d') == "18") {
			// 	$working = true;
			// } else {
			// 	$_SESSION['e'] = "Wrong wallet..!";
			// 	header("Location: $back");
			// 	die;
			// }


			$con = $db->connect();
			$balance_wallet = get_wallet_balance_of_member($con, $user_id, 'working_wallet_balance');
			$balance_ref_id = get_last_transaction_wallet_id_of_member($con, $user_id, 'working_wallet_transaction');
			$db->dbDisconnet($con);
		} else if ($data['wallet_name'] == 'roi') {
			$roi = true;
			/* if (date('d') == "3" || date('d') == "7") {
				$roi = true;
			} else {
				$_SESSION['e'] = "Wrong wallet..!";
				header("Location: $back");
				die;
			} */



			$con = $db->connect();
			$balance_wallet = get_wallet_balance_of_member($con, $user_id, 'roi_wallet_balance');
			$balance_ref_id = get_last_transaction_wallet_id_of_member($con, $user_id, 'roi_wallet_transaction');
			$db->dbDisconnet($con);
		} else {
			$_SESSION['e'] = "Wrong wallet..!";
			header("Location: $back");
			die;
		}


		if ($amount < $withdraw_min_limit) {
			$_SESSION['e'] = "Amount must be greater than or equal to $withdraw_min_limit.";
			header("Location: $back");
			die;
		} else if ($amount > $withdraw_max_limit) {
			$_SESSION['e'] = "Amount must be less than or equal to $withdraw_max_limit.";
			header("Location: $back");
			die;
		} else if ($balance_wallet < $amount || $balance_wallet == 0) {
			$_SESSION['e'] = "Your wallet balance is low for withdrawal.";
			header("Location: $back");
			die;
		} else {

			/*bank details*/
			$con = $db->connect();
			$fn_res = member_id_details($con, $user_id);
			$db->dbDisconnet($con);

			$data['redeem_with'] = '';

			if ($data['wallet_name'] == 'roi') {
				if ($fn_res['bank_update']) {
					$sql = "CALL REDEEM_FROM_ROI_WALLET('" . $user_id . "','" . $amount . "','" . $data['redeem_with'] . "','" . $data['pay_through'] . "','" . $fn_res['bnk_nm'] . "','" . $fn_res['acc_nm'] . "','" . $fn_res['acc_no'] . "','" . $fn_res['ifsc'] . "','" . $current_date . "','" . $now . "','" . $balance_ref_id . "','" . $action_by . "','" . $pan_no . "')";
				} else {
					$sql = "CALL REDEEM_FROM_ROI_WALLET('" . $user_id . "','" . $amount . "','" . $data['redeem_with'] . "','" . $data['pay_through'] . "','" . $data['bank_name'] . "','" . $data['acc_name'] . "','" . $data['acc_no'] . "','" . $data['ifsc'] . "','" . $current_date . "','" . $now . "','" . $balance_ref_id . "','" . $action_by . "','" . $pan_no . "')";
				}
			}

			if ($data['wallet_name'] == 'working') {
				if ($fn_res['bank_update']) {
					$sql = "CALL REDEEM_FROM_WORKING_WALLET('" . $user_id . "','" . $amount . "','" . $data['redeem_with'] . "','" . $data['pay_through'] . "','" . $fn_res['bnk_nm'] . "','" . $fn_res['acc_nm'] . "','" . $fn_res['acc_no'] . "','" . $fn_res['ifsc'] . "','" . $current_date . "','" . $now . "','" . $balance_ref_id . "','" . $action_by . "','" . $pan_no . "')";
				} else {
					$sql = "CALL REDEEM_FROM_WORKING_WALLET('" . $user_id . "','" . $amount . "','" . $data['redeem_with'] . "','" . $data['pay_through'] . "','" . $data['bank_name'] . "','" . $data['acc_name'] . "','" . $data['acc_no'] . "','" . $data['ifsc'] . "','" . $current_date . "','" . $now . "','" . $balance_ref_id . "','" . $action_by . "','" . $pan_no . "')";
				}
			}

			//echo $sql; die;
			$con = $db->connect();
			$q = mysqli_query($con, $sql);
			$db->dbDisconnet($con);

			$res = mysqli_fetch_array($q);
			$n = $res['return_id'];

			if ($n >= 1) {

				$gross_amount = $amount;
				/*$tax = 0.05*$gross_amount;
				$tds = 0.05*$gross_amount;
				$net_amount = $gross_amount - ($tax+$tds);*/


				/*$_name = $fn_res['name'];
    			$msg = "Congratulation, $_name,$gross_amount/- has been successfully withdrawal from your account. if any query visit www.myuniquetrade.com -ECOSOL";
    			$con=$db->connect();
                sms_mm($con,'','',$fn_res['mobile'],$msg,$action_by, '1207162918314423408');
    			$db->dbDisconnet($con);*/

				$_SESSION['s'] = $gross_amount . "/- has been successfully withdrawal from your account.";
			} else {
				$_SESSION['e'] = "Temporary Error...!";
			}
		}
	}
	header("Location: $back");
	die;
} else {
	$systemDenied = true;
	include('include/forced-logout.php');
}
