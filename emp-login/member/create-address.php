<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db= new Database();
$back='trx-deposite';

$con=$db->connect();
$_q1=mysqli_query($con,"SELECT trx_address,trx_hex FROM `member` where member_id='".$user_id."'");
$con->close();
if(mysqli_num_rows($_q1)==1){
    $_r1 = mysqli_fetch_assoc($_q1);
    $trx_address = $_r1['trx_address'];
    $trx_hex = $_r1['trx_hex'];
    if($trx_address==NULL || $trx_address==''){

    	$url="http://143.110.247.255/trxapi/createWallet";
	    $post='';
	    $response = curlPost($url,$post);
	    $address =  trim($response->address);
	    $privateKey =  trim($response->privateKey);
	    $hex =  trim($response->Hex);
	    
	    if($address!='' && $privateKey!=''){
	        $con=$db->connect();
			$tq1=mysqli_query($con,"update member set trx_address='".$address."', trx_privateKey='".$privateKey."' , trx_hex='".$hex."' where member_id='".$user_id."'");
			$aff_rows = mysqli_affected_rows($con);
			if($tq1 && $aff_rows==1){
				$_SESSION['s']="<strong>Congratulations !</strong> <br> Your address has been successfully created";
			}else{
				$_SESSION['e']="Temporary Error.";
			}
			$db->dbDisconnet($con);
	    }else{
	         $_SESSION['e']="Please try again later";
	    }
    } else {
    	$_SESSION['e']="TRX address already created.";
    }
} else {
	$_SESSION['e']="Member not fund.";
}
header("Location: $back");
die;