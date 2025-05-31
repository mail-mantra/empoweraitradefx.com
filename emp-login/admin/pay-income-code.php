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


if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit']) {

	extract($_POST);
	/*$con=$db->connect();
	$pin_type= prevent_injection($con,$_POST['pin_type']);
	$db->dbDisconnet($con);*/

	if (!isset($_POST['foo'])) {

		$_SESSION['e'] = "Please select at least one to pay.";;
	} else {

		if (isset($_POST['checkall']) && $_POST['checkall'] == 'on' && isset($_POST['foo'])) {
			$m_id_arr = $_POST['foo'];
		}

		if (isset($_POST['foo']) && !isset($_POST['checkall'])) {
			$m_id_arr = $_POST['foo'];
		}


		$con = $db->connect();
		mysqli_autocommit($con, FALSE);
		$arr_count = count($m_id_arr);
		$no = 0;
		$x = 0;
		$sms = [];

		foreach ($m_id_arr as $member_id) {

			$query = "select a.member_id,b.balance from member a 
				INNER JOIN working_wallet_transaction b ON (a.member_id=b.member_id)
				INNER JOIN (SELECT MAX(id) AS mid FROM working_wallet_transaction group by member_id ) AS c ON (c.mid=b.id)
				WHERE (a.member_id='" . trim($member_id) . "')";

			$sql = mysqli_query($con, $query);
			$a = mysqli_fetch_array($sql);
			$gross_amount = $a['balance'];
			$member_id = $a['member_id'];

			if ($gross_amount <= 0) {
				mysqli_rollback($con);
				$db->dbDisconnet($con);
				$_SESSION['e'] = "Something is wrong please try again.....";
			} else {

				/*$tax = 0.1*$gross_amount;
				$tds = 0.05*$gross_amount;
				$voucher_amount = $gross_amount - ($tax+$tds);*/
				$tax = 0;
				$tds = 0;
				$voucher_amount = $gross_amount;
				$voucher_no = get_voucher_no($con, 'pay_working_voucher', 'EMPWV');

				$q_insert = "insert into pay_working_voucher(m_id, wallet_type, gross_amount, admin_charge, tds, amount, voucher_no, payment_status, comments, created_by, created_on, paid_status, paid_date) 
				values('$member_id', 'working_wallet', '$gross_amount', '$tax', '$tds', '$voucher_amount', '$voucher_no', 'Paid','Voucher', '$action_by', '$now', '1', '$now')";

				$sql1 = mysqli_query($con, $q_insert);
				if ($sql1) {
					$x++;
					/* SMS*/
					/*$fn_res = member_id($con,$member_id);
                    $mobile = $fn_res['mobile'];
                    if($mobile>5000000000 && $mobile < 9999999999) {
                        $member_code = $fn_res['mem_code'];
                        $member_name = $fn_res['name'];
						$voucher_amount = number_format($voucher_amount, 2);
                        $msg = "Congratulation, $member_code,$member_name $voucher_amount/- has been successfully withdraw from your account. if any query Visit https://tdmintl.net -ECOSOL";
                        $sms[] = array(
                            "message" => $msg,
                            "to" => array($mobile)
                        );
                    }*/
					/* /SMS*/
				}
			}
		}

		if ($arr_count == $x) {
			//            mysqli_rollback($con);
			mysqli_commit($con);
			$success = 1;
		} else {
			mysqli_rollback($con);
			$success = 0;
		}
		$db->dbDisconnet($con);

		if ($success == 1) {
			$_SESSION['s'] = $x . " Voucher generated successfully.";
		} else {
			$_SESSION['e'] = "Temporary Problem. Please try again.";
		}

		if (count($sms)) {
			//$res_sms = sms_v2_min($sms, '1207164681845130708');
			//            print_r($res_sms);
			//            die;

			//            print_r($sms);
			/*
            Array (
                [0] => Array (
                    [message] => Congratulation, UT61382597,TESTING 50.00/- has been successfully withdraw from your account. if any query Visit https://myuniquetrade.com -ECOSOL
                [to] => Array (
                            [0] => 7003220260
                        )
                )
            )
            */
			//            print_r($res_sms);
			/*
            Array (
                [status] => 1
                [message] => 2 SMS send Successfully..
                [code] => 643d0784d6fc050b545da996
            )
            */
			//            die;
			$_SESSION['i'] = $res_sms['message'];
		}
	}

	header("Location:$back");
	die;
} else {
	$systemDenied = true;
	include('include/forced-logout.php');
}
