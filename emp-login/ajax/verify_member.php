<?php
session_start();
include('../class/DbClass.php');
include('../lib/my_function.php');
$db= new Database();

$result=array();
$result["status"] = 0;
$result["name"] = '';


if($_SERVER['REQUEST_METHOD']=='POST')
{
	$mem_code=$_POST['mem_code'];
	//$user_id=$_SESSION['user_data']['user_id'];
	
	$con=$db->connect();
	$member = member_code($con,$mem_code);
	$db->dbDisconnet($con);
	$valid_member = $member['valid_member'];
	//$payment_status = $member['payment_status'];
	
	if($valid_member == 0){
		$result["status"]=0;
		$result["name"] = '';
	}
	else{
		$result["status"]=1;
		$result["name"]=$member['name'];
	}

	echo json_encode($result);		
}
