<?php
include('../class/DbClass.php');
include('../lib/my_function.php');
$db= new Database();

$now=now();

$cal_date = date('Y-m-d', strtotime('-1 day', strtotime($current_date)));


/*$prev_date = date("Y-m-d", strtotime("-2 month", strtotime($current_date)));
$cal_date =  date("Y-m-t", strtotime($prev_date));*/

$sql="CALL CALCULATE_ROYALTY_BONUS('$first_day_previous_month', '$cal_date','$now','AUTO')";
//echo $sql; die;

$con=$db->connect();
$q=mysqli_query($con,$sql);
$db->dbDisconnet($con);

?>