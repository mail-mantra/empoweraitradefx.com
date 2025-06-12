<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
include('../lib/mail_sms.php');
include('../lib/smtp_function.php');
$db= new Database();

$now=now();
$ip=get_ip();
$session_id=session_id();


if(!isset($_SERVER['HTTP_REFERER'])){
	$systemDenied=true;
	include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];

if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['submit']=='Join'){
	
	extract($_POST);
	$con=$db->connect();
	foreach($_POST as $key => $value){
	  $data[$key] = prevent_injection($con,$value);
	}
	$db->dbDisconnet($con);
	
	
	if($data['intro']=='' || $data['side']=='' || $data['name']=='' || $data['email']==''  || $data['mobile']=='' || $data['country']==''){
	    
		$_SESSION['e']="Please enter the mandatory fields";
		
	}else if(!is_numeric($data['mobile']) || strlen($data['mobile'])<10 || strlen($data['mobile'])>10){
		
		$_SESSION['e']="Please enter a valid mobile no.";
		
	}/*else if(!is_valid_pan_format($data['pan_no']) && $data['pan_no']!='') {
        $_SESSION['e'] = "Please enter a valid PAN.";
        header("Location: $back");
        die;
        
    }*/
    else{
        
        $data['pan_no'] = NULL;
        //$data['pan_no'] = strtoupper(trim($data['pan_no']));
        
        /*$con=$db->connect();
        //$is_valid_mobile_count = is_valid_mobile_count($con,$data['mobile'], 3);
        //$is_valid_email_count = is_valid_email_count($con,$data['email'], 1);
        $is_valid_pan_count = is_valid_pan_count($con,$data['pan_no'], 3);
        $db->dbDisconnet($con);
        if (!empty($data['pan_no'])) {
            if (!$is_valid_pan_count){
                $_SESSION['e'] = "More than 3 ID is NOT ALLOWED in 1 Pan Card.";
                header("Location: $back");
                die;
            }
        }*/
        
        //if ($data['pan_no'] != 'A/F'){
        /*if(!is_valid_pan_format($data['pan_no'])) {
            $_SESSION['e'] = "Please enter a valid PAN.";
            header("Location: $back");
            die;
        }*/ //        }
        //else {
        
        /*$mem_code = trim($data['mem_code']);
        $mem_code = strtoupper($mem_code);*/
        
        $data['intro'] = trim($data['intro']);
        $data['intro'] = strtoupper($data['intro']);
        
        
        
        $status = 1;
        $capping = 0;

        $con = $db->connect();
        $upliner = $data['upliner']; //spill_upliner($con,$data['intro'],$data['side']);
        $m_tree = m_tree_generate($con,$upliner);
        $intro_mtree = intro_mtree_generate($con,$data['intro']);
        $db->dbDisconnet($con);


        if($data['intro'] == 'ADMIN' || $data['intro'] == 'admin') {
            $intro_level = 1;
            $intro_id = 0;
            $valid_intro = 1;
        }else{
            $con = $db->connect();
            $function_member = member_code($con, $data['intro']);
            $db->dbDisconnet($con);
            $in_level = $function_member['intro_level'];
            $intro_level = $in_level + 1;
            $intro_id = $function_member['member_id'];
            $valid_intro = $function_member['valid_member'];
        }
        
        if($valid_intro==0)
        {
            $_SESSION['e']="Invalid Introducer.";
            header("Location: $back");
            die;
        }
        

        if($upliner == 'ADMIN' || $upliner == 'admin') {
            $level = 1;
            $upliner_id = 0;
            $valid_upliner = 1;
        } else {
            $con = $db->connect();
            $function_member = member_code($con, $upliner);
            $db->dbDisconnet($con);
            $up_level = $function_member['level'];
            $level = $up_level + 1;
            $upliner_id = $function_member['member_id'];
            $valid_upliner = $function_member['valid_member'];
        }
        
        if($valid_upliner==0)
        {
            $_SESSION['e']="Invalid Upliner.";
            header("Location: $back");
            die;
        }
        
        $con=$db->connect();
        $mem_code = new_code($con,'member','mem_code',PREFIX_MEMBER_CODE);
        $db->dbDisconnet($con);
        $password = generatenumber(4,'123456789');
            
            
        $con=$db->connect();
        $sql21 = mysqli_query($con,"select * from member where mem_code='".$mem_code."'");
        $db->dbDisconnet($con);
        if(mysqli_num_rows($sql21)==1)
        {
            $_SESSION['e']=$mem_code." already exists. Try with another ID";
            header("Location: $back");
            die;
        }
        
        $data['state']='';
        $data['address'] = '';
        
        $sql1 = "CALL ADD_MEMBER('" . $mem_code . "','" . $password . "','" . $intro_id . "','" . $data['intro'] . "','" . $upliner_id . "','" . $upliner . "','" . $now . "','" . $level . "','" . $intro_level . "','" . $data['side'] . "','" . $m_tree . "','" . $intro_mtree . "','" . $data['name'] . "','" . $data['mobile'] . "','" . $action_by . "','" . $now . "','" . $status . "','" . $session_id . "','" . $ip . "','" . $capping . "','','" . $data['state'] . "','" . $data['country'] . "','','" . strtoupper($data['pan_no']) . "','','" . $data['email'] . "','" . $data['address'] . "')";

        
        //echo $sql1; die;
        
        $con = $db->connect();
        $q1 = mysqli_query($con, $sql1);
        $db->dbDisconnet($con);
        $r1 = mysqli_fetch_assoc($q1);
        $n = $r1['return_id'];

        if($n >= 1) {
            
            $mobile = $data['mobile'];
            $mem_name=$data['name'];
            
            $_SESSION['s'] = "Successfully added. Your Login ID is  <strong>$mem_code</strong> and password is <strong>$password</strong>";
            
            //$_SESSION['smsg'] = "Dear $mem_name,<br>Your account has been created successfully. Your User Member ID/Login ID: $mem_code and password is $password and login details has been sent at your registered email address";

            /* mail*/
                /*$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                    <head style="background:#EB7100;">
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                        <title>My Unique Trade</title>
                    </head>
                    <body>
                    <div style="width:500px; height:430px; margin:0 auto; padding:20px 5px; background:#EB7100">
                        <div style="padding:0px 25px 15px 25px;">
                            <img src="https://myuniquetrade.com/trade-software/web-assets/images/logo.png" style="width: 150px; display: block; margin: 0 auto;">
                        </div>
                        <div style="width:400px; height:250px; margin:0 auto; padding:25px; background:#EB7100; color:#FFF; text-align: center">
                            <h5 style="font-family: Arial, Helvetica, sans-serif; font-size:25px; font-weight:bold; line-height:35px;">Welcome to My Unique Trade</h5>
                            
                            <p style="font-family: Arial, Helvetica, sans-serif; font-size:22px; color:#FFF;  line-height:20px;">User ID : '.$mem_code.' </p>
                            <p style="font-family: Arial, Helvetica, sans-serif; font-size:22px; color:#FFF;  line-height:20px; margin-bottom:20px;">Password : '.$password.' </p>
                            <p style="font-family: Arial, Helvetica, sans-serif; font-size:14px; color:#FFF;  line-height:20px; padding-top:20px;">Please do not share with anyone.</p>
                        </div>
                        <p  style="font-family: Arial, Helvetica, sans-serif; font-size:14px; text-align:center; color:#3d3c41;  line-height:28px;">© 2022 '. PROJECT_NAME.'. All Rights Reserved </p>
                    </div>
                    </body>
                    </html>';
                $mail_to = $data['email'];
                $mail_subject = "Welcome To My Unique Trade";
                $mail_message = $message;
                mm_smtp($mail_to, $mail_subject, $mail_message);*/
            /* mail*/
            
            header("Location: dashboard");
	        die;

        } else {
            $_SESSION['e'] = "Temporary Error...!";
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