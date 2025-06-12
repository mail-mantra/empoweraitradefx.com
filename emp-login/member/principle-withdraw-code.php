<?php
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


if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_REQUEST['id'] != '' && $_REQUEST['action'] != '') {
	$con = $db->connect();
	foreach ($_REQUEST as $key => $value) {
		$data[$key] = prevent_injection($con, $value);
	}
	$db->dbDisconnet($con);


	if ($data['id'] == '') {
		$_SESSION['e'] = "Cannot proceed further.";
	} else if ($data['action'] == 'pay') {

		$con = $db->connect();
		$query = "SELECT `value` FROM `settings` WHERE `key` = 'principle_withdraw'";
		$result = mysqli_query($con, $query);
		$db->dbDisconnet($con);

		$open = ($result && ($row = mysqli_fetch_assoc($result)) && $row['value']);

		if (!$open) {
			$_SESSION['e'] = "This feature is currently disabled.";
			header("Location: $back");
			die;
		}


		$id = hash_decode($data['id']);


		$con = $db->connect();
		$q1 = mysqli_query($con, "select * from member_package_update_log where id='" . $id . "' and amount>0");
		$db->dbDisconnet($con);

		if (mysqli_num_rows($q1) > 0) {

			$r1 = mysqli_fetch_assoc($q1);
			$member_id = $r1['member_id'];

			$sql = "CALL PRINCIPLE_WITHDRAW('" . $member_id . "','" . $id . "','" . $action_by . "','" . $now . "')";
			//echo $sql; die;

			$con = $db->connect();
			$q = mysqli_query($con, $sql);
			$db->dbDisconnet($con);

			$res = mysqli_fetch_array($q);
			$n = $res['return_id'];

			if ($n == 1) {
				$_SESSION['s'] = "Withdrawal Successful from your account.";
			} else {
				$_SESSION['e'] = $res['var_message'];
			}

			header("Location: $back");
			die;
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
