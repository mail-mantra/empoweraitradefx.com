<?php
include('../class/DbClass.php');
include('../lib/my_function.php');
$db= new Database();

$now=now();

$cal_date = date('Y-m-d', strtotime('-1 day', strtotime($current_date)));

$sql="CALL CALCULATE_LIVE_TRADE_BONUS('$cal_date','$now','AUTO')";

$con=$db->connect();
$q=mysqli_query($con,$sql);
$db->dbDisconnet($con);

/*$cal_date = '2024-10-13';
$now = '2024-10-20';
while(strtotime($cal_date) <= strtotime($now)){
    echo $cal_date."..DONE<br>";
    $sql="CALL CALCULATE_ROI_BONUS('$cal_date','$cal_date','PB')";
    $con=$db->connect();
    $q=mysqli_query($con,$sql);
    $db->dbDisconnet($con);
    $cal_date = date("Y-m-d", strtotime("+1 day", strtotime($cal_date)));
}
echo "END";*/

?>