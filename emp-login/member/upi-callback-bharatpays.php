<?php
include('../class/DbClass.php');
include('../lib/my_function.php');

$db = new Database();

$now = now();

$data = file_get_contents("php://input");
$objData = json_decode($data);

$api_response = addslashes(json_encode($objData));

$success = $objData->success;
$message = $objData->message;

$txn_id = $objData->data->txn_id;
$remitter_full_name = $objData->data->remitter_full_name;
$utr= $objData->data->utr;
$payment_mode= $objData->data->payment_mode;
$amount = $objData->data->amount;
$service_charge = $objData->data->service_charge;
$gst_amount = $objData->data->gst_amount;
$service_charge_with_gst = $objData->data->service_charge_with_gst;
$narration = $objData->data->narration;
$status = $objData->data->status;
$created_at = $objData->data->created_at;
$virtual_account_id = $objData->data->virtual_account_id;
$account_type = $objData->data->type;


$usd_amount = $amount / 77;
$txn_code = uniqid('TXN') . time() . rand();

$con=$db->connect();
mysqli_autocommit($con,FALSE);	
$q1=mysqli_query($con,"insert into callback_bh_payment (txn_id, virtual_account_id, account_type, remitter_full_name, utr, payment_mode, amount, service_charge, gst_amount, service_charge_with_gst, narration, status, success, api_response, txn_code, usd_amount, created_at, date_time) values 
('".$txn_id."', '".$virtual_account_id."','".$account_type."','".$remitter_full_name."','".$utr."','".$payment_mode."','".$amount."','".$service_charge."','".$gst_amount."','".$service_charge_with_gst."','".$narration."','".$status."','".$success."','".$api_response."', '".$txn_code."', '".$usd_amount."','".$created_at."','".$now."')");

if($q1){
	mysqli_commit($con);

	$success = 1;
}else{
	$success = 0;
	mysqli_rollback($con);
}
$db->dbDisconnet($con);

echo $success;