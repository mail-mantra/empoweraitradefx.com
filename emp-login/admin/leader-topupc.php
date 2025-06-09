<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');

$db= new Database();
$now=now();
$ip=get_ip();
$session_id=session_id();


if(!isset($_SERVER['HTTP_REFERER'])){
	$systemDenied=true;
	include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];

if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['submit']=='Submit'){
	
	extract($_POST);
	//print_r($_POST); die;
	
	$con=$db->connect();
	foreach($_POST as $key => $value){
	  $data[$key] = prevent_injection($con,$value);
	}
	$db->dbDisconnet($con);
	
	if($data['mem_code']==''){
		$_SESSION['e']="ID missing.";
	}
	else if($data['pdt_id']==''){
		$_SESSION['e']="Product missing.";
	}
	else if($data['package_id']==''){
		$_SESSION['e']="Package missing.";
	}
	else{
	    
	    $pdt_id = $data['pdt_id'];
	    $package_id = $data['package_id'];
    	
    	$con=$db->connect();
		$mc = member_code($con,$data['mem_code']);
		$db->dbDisconnet($con);
		if($mc['valid_member']==0){
			$_SESSION['e']="Invalid member.";
			
		}else{
			
			$member_id = $mc['member_id'];
			$intro_id = $mc['intro_id'];
			
			if($pdt_id==2){
    		    $sql = "select COUNT(1) AS is_exists from member_package_update where member_id='".$member_id."'";
			}
			
			if($pdt_id==3){
    		    $sql = "select COUNT(1) AS is_exists from member_liquidity_package_update where member_id='".$member_id."'";
			}
			
			$con=$db->connect();
			$q1 = mysqli_query($con,$sql);
    		$db->dbDisconnet($con);
    		$r1 = mysqli_fetch_assoc($q1);
    		$is_exists = $r1['is_exists'];
    		
    		if($is_exists >= 1){
				$_SESSION['e']="Already exists. Enter in-active ID.";

			}else{
			    
			    if($pdt_id==2){
    			    $sql_1 = "INSERT INTO `member_package_update`(`member_id`, `package_id`, `intro_id`, `amount`, `actual_amount`, `created_by`, `created_on`, `topup_created_on`) VALUES('".$member_id."', '".$package_id."', '".$intro_id."', '0', '0', '".$action_by."', '".$now."', '".$now."')";
    			    $sql_2 = "INSERT INTO `member_package_update_log`(`member_id`, `package_id`, `intro_id`, `amount`, `actual_amount`, `narration`, `created_by`, `created_on`, `topup_created_on`) VALUES('".$member_id."', '".$package_id."', '".$intro_id."', '0', '0', 'LEADER_TOPUP', '".$action_by."', '".$now."', '".$now."')";
			    }
			    
			    if($pdt_id==3){
    			    $sql_1 = "INSERT INTO `member_liquidity_package_update`(`member_id`, `pdt_id`, `package_id`, `intro_id`, `amount`, `actual_amount`, `created_by`, `created_on`, `topup_created_on`) VALUES('".$member_id."', '".$pdt_id."', '".$package_id."', '".$intro_id."', '0', '0', '".$action_by."', '".$now."', '".$now."')";
    			    $sql_2 = "INSERT INTO `member_liquidity_package_update_log`(`member_id`, `pdt_id`, `package_id`, `intro_id`, `amount`, `actual_amount`, `narration`, `created_by`, `created_on`, `topup_created_on`) VALUES('".$member_id."', '".$pdt_id."', '".$package_id."', '".$intro_id."', '0', '0', 'LEADER_TOPUP', '".$action_by."', '".$now."', '".$now."')";
			    }
			    //echo $sql_1; die;
			    
			    $con=$db->connect();
        		$q2 = mysqli_query($con,$sql_1);
        		$q3 = mysqli_query($con,$sql_2);
        		$db->dbDisconnet($con);
        		
        		if($q2 && $q3){
				    $_SESSION['s']="Leader Topup successfull..";
        		}else{
        		    $_SESSION['e']="Temporary Error...!";
        		}
			}
	    
    	}
	}
	
	header("Location: $back");
	die;
}
else{
	$systemDenied = true;
	include('include/forced-logout.php');
}
?>