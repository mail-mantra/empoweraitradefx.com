<?php
header('Content-Type: application/json');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();
$test_api_key = '420c8166803c9ee85629ad505bb0be38';

$response = array('status'=>0, 'msg'=>'Invalid Request');

if($test_api_key==$_POST['api_key']){
  
    if(isset($_POST['username']) && isset($_POST['password']) && $_POST['username']!='' && $_POST['password']!=''){
        $un=$_POST['username'];
        $pw=$_POST['password'];
        //////////////
        $con=$db->connect();
		$q1=mysqli_query($con,"select m1.*,m2.* from member m1 inner join member_login m2 on m1.member_id=m2.member_id where binary(m1.mem_code)='".$un."'");
		$db->dbDisconnet($con);	
		if(mysqli_num_rows($q1)==1){
			$r1 = mysqli_fetch_assoc($q1);
			if($r1['password']==$pw || hash_decode($master_password)==$pw){
				if($r1['status']==1) {
					$user_details = array(
										 'logged_in'=>TRUE,
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
                    $response = array('status'=>1, 'msg'=>'Login Success','user_details'=>$user_details);
				}else{
					$response = array('status'=>0, 'msg'=>'Inactive user');
				}
			}
			else{
			    $response = array('status'=>0, 'msg'=>'Wrong username or password');
			}
		}else{
			$response = array('status'=>0, 'msg'=>'Wrong username or password');
		}
        //////////////
        
    }else{
        $response = array('status'=>0, 'msg'=>'Empty username or password');
    }
}else{
    $response = array('status'=>0, 'msg'=>'Invalid API Key');
}
echo json_encode($response,JSON_PRETTY_PRINT); 
?>


