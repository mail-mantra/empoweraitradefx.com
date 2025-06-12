<?php
session_start();
if(isset($_SESSION['user_data']) && $_SESSION['user_data']['logged_in']==TRUE && $_SESSION['user_data']['user_type']=='member')
{
	$action_by=$_SESSION['user_data']['user_code'];
	$user_id=$_SESSION['user_data']['user_id'];
	$user_code=$_SESSION['user_data']['user_code'];
	$user_rank=$_SESSION['user_data']['user_rank'];
	$user_type=$_SESSION['user_data']['user_type'];
	$user_full_name=$_SESSION['user_data']['full_name'];
	$user_mobile=$_SESSION['user_data']['mobile'];
	$user_email=$_SESSION['user_data']['full_name'];
	$user_last_login_time=$_SESSION['user_data']['last_login_time'];
	$user_dp=$_SESSION['user_data']['dp'];

	$editData=false;
	$systemDenied=false;
	
	
	/*------------------------------------------------*/
}
else
{
	header('location:./');
	die;
}
