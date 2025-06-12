<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
include('../lib/bharatPaysPayout.php');
$db= new Database();
$now=now();


$db= new Database();
$now=now();
$bharatPaysPayout = new bharatPaysPayout();
$mem_code = $user_code;
$member_id = $user_id;


if(!isset($_SERVER['HTTP_REFERER'])){
	$systemDenied=true;
	include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];

if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['submit']=='Update'){
	$con=$db->connect();
	foreach($_POST as $key => $value){
	  $data[$key] = prevent_injection($con,$value);
	}
	$db->dbDisconnet($con);
	$data['id']=hash_decode($data['id']);
	if(is_numeric($data['id']) && $data['id']>0){	
		$edit_id=$data['id'];
	}else{
		$systemDenied=true;
		include('include/forced-logout.php');	
	}

	if($data['bank']=='' || $data['branch']=='' || $data['acc_nm']=='' || $data['acc_no']=='' || $data['pan_no']=='' || $data['acc_type']=='' || $data['ifsc']=='' /*|| $data['nom_name']=='' || $data['nom_relation']==''*/){
		
		$_SESSION['e']="Please enter the mandatory fields";
		
	}else{
	    
	        $con=$db->connect();
			$q1 = mysqli_query($con,"select mobile,email from member where member_id='".$user_id."'");
			$db->dbDisconnet($con);	
			$r1=mysqli_fetch_assoc($q1);
			$member_email = $r1['email'];
			$member_phone = $r1['mobile'];
			
            if(!filter_var($member_email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['e']="Invalid or Empty Email ID. Please update your email";
                header("Location: $back");
                die;       
            }
            $con=$db->connect();
            $q_acc=mysqli_query($con,"SELECT member_id from member_details where acc_no = '".$data['acc_no']."'");
            $db->dbDisconnet($con); 
            if(mysqli_num_rows($q_acc) >= 4){
                $_SESSION['e'] = "The AC Number is already registerd with us.";
                header("Location: $back");
                die;
            }
            $beneficiary = array(
                'bene_account_number' => $data['acc_no'],
                'ifsc_code' => $data['ifsc'],
                'recepient_name' => $data['acc_nm'],
                'email_id' => $member_email,
                'mobile_number' => $member_phone,
            );
            
           /*echo "<pre>";
           print_r($beneficiary);
           die;*/

           $api_request_json = json_encode($beneficiary);
            
           $bharatPaysPayout->setBeneficiary($beneficiary);
           $createPayoutAccount = $bharatPaysPayout->createPayoutAccount();
           $api_response_json = json_encode($createPayoutAccount);
           /*echo "<pre>";
           print_r($createPayoutAccount);
           die;*/
           
             /*Array 
                    ( [success] => 1 
                     [message] => Payout Bank Account Created Successfully 
                     [data] => Array ( 
                         [bank_account_id] => 434 
                         [bene_account_number] => 00240100009305 
                         [ifsc_code] => BARB0UPPERC 
                         [recepient_name] => TESTING 
                         [email_id] => arup@mailmantra.in 
                         [mobile_number] => 7003220260 
                         [status] => PENDING 
                         [remark] => ) 
                    )*/
            /*Array
                (
                    [success] => 1
                    [message] => Payout Bank Account Created Successfully
                    [data] => Array
                        (
                            [bank_account_id] => 435
                            [bene_account_number] => 00240100009305
                            [ifsc_code] => BARB0UPPERC
                            [recepient_name] => TESTING
                            [email_id] => arup@mailmantra.in
                            [mobile_number] => 7003220260
                            [status] => PENDING
                            [remark] => 
                        )
                
                )*/


    if(is_array($createPayoutAccount) && isset($createPayoutAccount['success']) && ($createPayoutAccount['success'] == '1') && isset($createPayoutAccount['data']['bank_account_id']) && is_numeric($createPayoutAccount['data']['bank_account_id'])) {

                $bank_account_id = $createPayoutAccount['data']['bank_account_id'];
                $nameAtBank = $createPayoutAccount['data']['recepient_name'];
                $status = $createPayoutAccount['data']['status'];
        
                $sql_insert = "INSERT INTO `beneficiary_ac_list`(member_id, bank_name, branch_name, acc_name, acc_no, acc_type, ifsc_code, pan_no, email, mobile, bank_account_id, api_name, api_request, api_response, created_by, created_on  ) VALUES 
                ('$member_id', '".$data['bank']."', '".$data['branch']."', '".$data['acc_nm']."', '".$data['acc_no']."', '".$data['acc_type']."', '".$data['ifsc']."', '".$pan_no."', '".$member_email."','".$member_phone."', '".$bank_account_id."','BH_PAYS','".$api_request_json."','".$api_response_json."',  '".$action_by."','".$now."' )";
                
                $sql_update = "UPDATE  member_details SET  
        							   bank_update =  '1',
        							   bnk_nm =  '".$data['bank']."',
        							   brnch_nm =  '".$data['branch']."',
        							   acc_nm =  '".$data['acc_nm']."',
        							   acc_no =  '".$data['acc_no']."',
        							   pan_no =  '".$data['pan_no']."',
        							   acc_type =  '".$data['acc_type']."',
        							   ifsc =  '".$data['ifsc']."',
        							   updated_by =  '".$action_by."',
        							   updated_on =  '".$now."',
        							   bank_account_id='".$bank_account_id."',
            						   bank_account_date =  '".$now."'
        							   WHERE member_id ='".$edit_id."'";
                
        		$con=$db->connect();
        		mysqli_autocommit($con,FALSE);	
        		$tq1=mysqli_query($con,$sql_update);
            	$tq2=mysqli_query($con,$sql_insert);
        		
        		if($tq1 && $tq2){
        			mysqli_commit($con);
        			$success = 1;
        		}else{
        			$success = 0;
        			mysqli_rollback($con);
        		}
        		$db->dbDisconnet($con);
        		
        		if($success == 1){
        			$_SESSION['s']="Your bank details updated successfully.";
        		}else{
        			$_SESSION['e']="Temporary Error...!";
        		}
        }else{
                $_SESSION['e']="Bank A/C not validating...! Please try again later";
        }
	
	}
	header("Location: $back");
	die;
}
if($_SERVER['REQUEST_METHOD']=='GET' && isset($_REQUEST['bank_change_request'])) {
    $user_id=$_SESSION['user_data']['user_id'];
    $user_code=$_SESSION['user_data']['user_code'];
    $sql_update = "INSERT INTO `bank_change_request`(`member_id`, `request_status`, `created_at`, `created_by`) 
                    VALUES ('$user_id','0', NOW(), '$user_code') 
                    ON DUPLICATE KEY
                    UPDATE
                        `request_status`='0',
                        `request_status`=VALUES(`request_status`),
                        `created_at`= NOW(),
                        `created_by`= VALUES(`created_by`)
                            ";

    $con = $db->connect();
    $res_update = $con->query($sql_update);
    $n_update = $con->affected_rows;
    $con->close();

    if($res_update && $n_update) {
        $_SESSION['s']= 'Request added successfully';
    }
    else {
        $_SESSION['e']= 'Temporary problem, please try again.';
    }
}
else{
	$systemDenied = true;
	include('include/forced-logout.php');
}

/*

if(isset($_REQUEST['response']) && $_REQUEST['response'] == 'json') {
    header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    header("Pragma: no-cache"); // HTTP 1.0.
    header("Expires: 0"); // Proxies.
    header("Content-Type: application/json;charset=utf-8");

    $result['back'] = $back;
    $result['status'] = 1;
    $result['data'] = array();
    $result['message'] = 'Temporary Problem'.__LINE__;
    $result['w'] = '';

    if(isset($_SESSION['s']) && $_SESSION['s']) {
        $result['status'] = 0;
        $result['message'] = $_SESSION['s'];
        $result['refresh'] = 1;
        // $result['redirect'] = $back;
        unset($_SESSION['s']);
    }
    else if(isset($_SESSION['e']) && $_SESSION['e']) {
        $result['message'] = $_SESSION['e'];
        unset($_SESSION['e']);
    }
    else if(isset($_SESSION['w']) && $_SESSION['w']) {
        $result['message'] = "Test".$_SESSION['w'];
        $result['w'] = $_SESSION['w'];
        unset($_SESSION['w']);
    }
    else {

    }
    echo json_encode($result);
}
else {
    header('location:'.$back);
    die;
}
*/
?>