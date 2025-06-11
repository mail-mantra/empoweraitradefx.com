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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'Submit') {

	extract($_POST);
	//print_r($_POST); die;

	$con = $db->connect();
	foreach ($_POST as $key => $value) {
		$data[$key] = prevent_injection($con, $value);
	}
	$db->dbDisconnet($con);

	if ($data['package_id'] == '' || $data['package_id'] <= 0) {
		$_SESSION['e'] = "Invalid package.";
		
	} else if ($data['tg_link'] == '') {
		$_SESSION['e'] = "Invalid Telegram ID.";
		
	} else {
        
        $_telegram_link = $data['tg_link'];
		$_package_id = $data['package_id'];
		
		$sql_pack = "SELECT * FROM join_package_signal WHERE id = '".$_package_id."'";
		$con = $db->connect();
		$q_p = mysqli_query($con, $sql_pack);
		$db->dbDisconnet($con);
		if (mysqli_num_rows($q_p) == 0) {
			$_SESSION['e'] = "Invalid package.";
			header("Location: $back");
			die;
			
		} 

		$proceed = 0;
		
		/*$con = $db->connect();
        $ba_wallet_balance = get_wallet_balance_of_member($con, $user_id, 'ba_wallet_balance');
        $db->dbDisconnet($con);
        if($ba_wallet_balance>0)
        {
            $proceed = 0;
            $_SESSION['e']="Wait for package expiry.";
            header("Location: $back");
            die;
        }
        else
        {*/
    		$con=$db->connect();
            $qpk = mysqli_query($con,"select package_id from member_signal_package_update_log where member_id='".$user_id."' order by id desc limit 1");
            $npk=mysqli_num_rows($qpk);
            $db->dbDisconnet($con);
            if($npk==0)
            {
                $proceed=1;
            }
            else
            {
                $rpk=mysqli_fetch_assoc($qpk);
                $last_package_id = $rpk['package_id'];
                
                if($_package_id >= $last_package_id){
                    $proceed=1;
                    
                }else{
                    $proceed=0;
                    $_SESSION['e']="You must deposit with more than your last deposit amount.";
                    header("Location: $back");
                    die;
                }
            }
       // }
	
	    
		
		
		if ($proceed == 1) {
		   
			$wallet_used = 'fund';

			$con = $db->connect();
			$wallet_balance = get_wallet_balance_of_member($con, $user_id, 'myfund_wallet_balance');
			$db->dbDisconnet($con);


			if ($wallet_balance < $_amount || $wallet_balance <= 0) {
				$_SESSION['e'] = "Your wallet balance is low to topup.";
			} else {

				$inv_date = $now;

				$sql = "CALL MEMBER_SIGNAL_PLAN_TOPUP('" . $user_code . "','" . $_package_id . "','" . $action_by . "','" . $now . "', '" . $wallet_used . "', '" . $inv_date . "', '" . $_telegram_link . "')";
				//echo $sql; die;

				$con = $db->connect();
				$q1 = mysqli_query($con, $sql);
				$db->dbDisconnet($con);
				$r1 = mysqli_fetch_assoc($q1);
				$n = $r1['return_id'];

				if ($n == 1) {
					$_SESSION['s'] = $_amount . " investment successfull.";
				} else {
					$_SESSION['e'] = $r1['var_message'];
				}
			}
			
		} else {
			$_SESSION['e'] = "Temporary Error.";
		}
	}

	header("Location: $back");
	die;
} else {
	$systemDenied = true;
	include('include/forced-logout.php');
}
