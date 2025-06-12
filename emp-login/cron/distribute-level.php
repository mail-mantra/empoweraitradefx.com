<?php
include('../class/DbClass.php');
include('../lib/my_function.php');
$db= new Database();

$now = now();

$income_type = 'COMMUNITY_TRADE_LEVEL_BONUS';

$sql="CALL DISTRIBUTE_INCOME_INTO_WORKING_WALLET('$income_type','$current_date','$now')";

$con=$db->connect();
$q=mysqli_query($con,$sql);
$db->dbDisconnet($con);

?>