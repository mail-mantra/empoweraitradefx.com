<?php
include('../include/privilege.php');
include('../../class/DbClass.php');
include('../../lib/my_function.php');
$db= new Database();
$now = now();
if(!isset($_SERVER['HTTP_REFERER'])){
	$systemDenied=true;
	include('../include/forced-logout.php');
}
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['id']) && $_POST['id']!=''){
    $virtual_account_id = hash_decode($_POST['id']);
    
    $con=$db->connect();
    $q1=mysqli_query($con,"select * from member_vpa_account where member_id='".$user_id."' and virtual_account_id='".$virtual_account_id."'");
    $db->dbDisconnet($con);	
    if(mysqli_num_rows($q1)==1){
        
        $con=$db->connect();
        $q2=mysqli_query($con,"select * from callback_bh_payment where virtual_account_id='".$virtual_account_id."' and wallet_credit=0");
        $db->dbDisconnet($con);	
        if(mysqli_num_rows($q2)==1){
            $r2 = mysqli_fetch_assoc($q2);
            
            $status= $r2['status'];
            $success = $r2['success'];
            
            if($status=='SUCCESS' && $success==1){
            
            $amount = $r2['amount'];
            $txn_code = $r2['txn_code'];
            $particulars = 'ADD_BALANCE_FROM_UPI_DESPOSITE';
           
            //update balance
            $con=$db->connect();
        	mysqli_autocommit($con,FALSE);
        	$tq=mysqli_query($con,"select * from myfund_wallet_balance where member_id='".$user_id."'");
        	if(mysqli_num_rows($tq)==1){
        	    $tr = mysqli_fetch_assoc($tq);
        	    $balance = $tr['balance'];
        	}else{
        	    $balance = 0;
        	}
	
	        $current_balance = $balance + $amount;
        	$tq1=mysqli_query($con,"INSERT INTO myfund_wallet_balance (member_id, balance, type, amount) VALUES ('".$user_id."', '".$current_balance."', 'Cr', '".$amount."')
                                     ON DUPLICATE KEY UPDATE
                                     balance = '".$current_balance."',
                                     type = 'Cr',
                                     amount = '".$amount."'");
        	$tq2=mysqli_query($con,"INSERT INTO myfund_wallet_transaction (member_id, particulars, txn_date, transaction_type, credit, balance, txn_code, created_by, txn_date_time) VALUES 
        	                                                        ('".$user_id."', '".$particulars."', '".$now."',  'CREDIT', '".$amount."', '".$current_balance."', '".$txn_code."', '".$user_code."', '".$now."')");
          
            $tq3=mysqli_query($con,"update callback_bh_payment set wallet_credit='1' where virtual_account_id='".$virtual_account_id."' and wallet_credit=0");
            $tq4=mysqli_query($con,"update  member_vpa_account set use_status='1' where virtual_account_id='".$virtual_account_id."' and member_id='".$user_id."'");
            
            if($tq1 && $tq2 && $tq3 && $tq4){
        		mysqli_commit($con);
        		$status = 1; 
        	}else{
        		mysqli_rollback($con);
        		$status = 0; 
        	}
        	$db->dbDisconnet($con);
        	if($status==1){
        	    $response = array('status'=>1); 
        	}else{
        	    $response = array('status'=>3); 
        	}
         }else{
             $response = array('status'=>4); 
         }
        }else{
           $response = array('status'=>0); 
        }
    }else{
        $response = array('status'=>2); 
    }
    
}
echo json_encode($response);
die;