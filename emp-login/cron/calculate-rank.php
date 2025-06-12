<?php
include('../class/DbClass.php');
include('../lib/my_function.php');
$db= new Database();

$now=now();

$last_date = LastDay_of_specified_Date($current_date);

$cal_date = date('Y-m-d', strtotime('-1 day', strtotime($current_date)));

if($last_date == $cal_date){
    $var_monthly_closing = 1;
}else{
    $var_monthly_closing = 0;
}

$sql="CALL CALCULATE_RANK('$cal_date','$now','AUTO','$var_monthly_closing')";
//echo $sql; die;

$con=$db->connect();
$q=mysqli_query($con,$sql);
$db->dbDisconnet($con);



//$cal_date = '2021-09-24';
/*$cal_date = '2022-06-04';
$now = '2022-08-30';
while(strtotime($cal_date) <= strtotime($now)){
    echo $cal_date."..DONE<br>";
    $sql="CALL CALCULATE_RANK('2021-09-24','$cal_date','$cal_date')";
    $con=$db->connect();
    $q=mysqli_query($con,$sql);
    $db->dbDisconnet($con);
    $cal_date = date("Y-m-d", strtotime("+1 day", strtotime($cal_date)));
}
echo "END";*/

?>
