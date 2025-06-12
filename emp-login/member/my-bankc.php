<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');

$db = new Database();
$now = now();
$mem_code = $user_code;
$member_id = $user_id;

if (!isset($_SERVER['HTTP_REFERER'])) {
	$systemDenied = true;
	include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//	extract($_POST);


	$con = $db->connect();
	$q1 = mysqli_query($con, "select * from member_details where member_id='" . $user_id . "' and bank_update='1'");
	$db->dbDisconnet($con);
	if (mysqli_num_rows($q1) == 1) {
		$systemDenied = true;
		include('include/forced-logout.php');
		die;
	}


	$con = $db->connect();
	foreach ($_POST as $key => $value) {
		$data[$key] = prevent_injection($con, $value);
	}
	$db->dbDisconnet($con);
	$data['id'] = hash_decode($data['id']);
	if (is_numeric($data['id']) && $data['id'] > 0) {
		$edit_id = $data['id'];
	} else {
		$systemDenied = true;
		include('include/forced-logout.php');
	}


	if ($data['bnk_nm'] == '' || $data['branch'] == '' || $data['acc_nm'] == '' || $data['acc_no'] == '' || /*$data['pan_no']=='' ||*/ $data['acc_type'] == '' || $data['ifsc'] == '') {

		$_SESSION['e'] = "Please enter the mandatory fields";
	} else {

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
		/*$data['pan_no'] = strtoupper(trim($data['pan_no']));
			if (!is_valid_pan_format($data['pan_no'])) {
				$_SESSION['e']="Please Enter a valid PAN Card ...!";
				header("Location: $back");
				die;
			}*/
		/*$con=$db->connect();
            $no_of_account = no_of_account_by_pan($con, $data['pan_no']);
            $db->dbDisconnet($con);
            if ($no_of_account >= 3) {
                $_SESSION['e']="Sorry.You Can't use this PAN <b>[ ".$data['pan_no']." ]</b>, because it is already used in <b>".$no_of_account."</b> other accounts...!";
                header("Location: $back");
                die;
            }*/



		$con = $db->connect();
		$q1 = mysqli_query($con, "select mobile,email from member where member_id='" . $user_id . "'");
		$db->dbDisconnet($con);
		$r1 = mysqli_fetch_assoc($q1);
		$member_email = $r1['email'];
		$member_phone = $r1['mobile'];

		if (!filter_var($member_email, FILTER_VALIDATE_EMAIL)) {
			$_SESSION['e'] = "Invalid or Empty Email ID. Please update your email";
			header("Location: $back");
			die;
		}




		$con = $db->connect();
		mysqli_autocommit($con, FALSE);
		$sql_update = "UPDATE  member_details SET  
            								   bank_update =  '1',
            								   bnk_nm =  '" . $data['bnk_nm'] . "',
            								   brnch_nm =  '" . $data['branch'] . "',
            								   acc_nm =  '" . $data['acc_nm'] . "',
            								   acc_no =  '" . $data['acc_no'] . "',
            								   acc_type =  '" . $data['acc_type'] . "',
            								   ifsc =  '" . $data['ifsc'] . "',
            								   pan_no =  '" . $pan_no . "',
            								   updated_by =  '" . $action_by . "',
            								   updated_on =  '" . $now . "',
            								   bank_account_date =  '" . $now . "'
            								   WHERE member_id ='" . $edit_id . "'";

		$tq1 = mysqli_query($con, $sql_update);
		//$tq2=mysqli_query($con,$sql_insert);

		if ($tq1) {
			mysqli_commit($con);
			$success = 1;
		} else {
			$success = 0;
			mysqli_rollback($con);
		}
		$db->dbDisconnet($con);

		if ($success == 1) {
			$_SESSION['s'] = "Your bank details updated successfully.";
		} else {
			$_SESSION['e'] = "Temporary Error...!1";
		}
		/*  }else{
                $_SESSION['e']="Bank A/C not validating...! Please try again later";
            } */
	}
	/*}else{
	    $_SESSION['e']="You cannot update anymore.";
	}*/
	header("Location: $back");
	die;
} else {
	$_SESSION['e'] = "Invalid Call.";
	header("Location: $back");
	die;
}
