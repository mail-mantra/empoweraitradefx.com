<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
include('../lib/mail_sms.php');
include('../smtp/mail.php');
$db= new Database();
$now=now();



if(!isset($_SERVER['HTTP_REFERER'])){
	$systemDenied=true;
	include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];

if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['submit']=='Save'){

	$con=$db->connect();
	foreach($_POST as $key => $value){
	  $data[$key] = prevent_injection($con,$value);
	}
	$db->dbDisconnet($con);
	if($data['cur_pwd']=='' || $data['new_pwd']=='' || $data['conf_pwd']==''){
		$_SESSION['e']="Fill all the fields completely.";
	}else{
		$sql1="SELECT a.email, b.password from member a inner join member_login b on a.member_id=b.member_id where a.member_id='".$user_id."'";
		$con=$db->connect();
		$q1=mysqli_query($con,$sql1);
		$db->dbDisconnet($con);
		$r1 = mysqli_fetch_assoc($q1);	
		$password = $r1['password'];
		$email = $r1['email'];
		
		if($data['cur_pwd']!=$password){
			$_SESSION['e']="You have entered wrong current password.";
		}else if($data['new_pwd']!=$data['conf_pwd']){
			$_SESSION['e']="Confirm Password Mismatch.";
		}else{
			
			$con=$db->connect();
			$sql_update = "UPDATE member_login SET  
						   password =  '".$data['new_pwd']."'
						   WHERE member_id ='".$user_id."'";
						   
			if(mysqli_query($con,$sql_update)){
				$_SESSION['s']="Your password updated successfully.";
			}else{
				$_SESSION['e']="Temporary Error...!";
			}
			$db->dbDisconnet($con);
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