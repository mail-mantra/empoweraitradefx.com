<?php
include('../include/privilege.php');
include('../../class/DbClass.php');
include('../../lib/my_function.php');
$db = new Database();
$now = now();

$result = array('status' => 0, 'result' => '');
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $con = $db->connect();
    foreach($_POST as $key => $value) {
        $data[$key] = prevent_injection($con, $value);
    }
    $db->dbDisconnet($con);

    $service_type = $data['service_type'];

    if($service_type == 'business_ratio') {
        $sql_dwn = "INSERT INTO member_downline_business(member_id, business)
                        SELECT member_id, INTROWISE_BUSINESS_60_40(member_id, '$current_date') FROM member
                        ON DUPLICATE KEY UPDATE business = VALUES(`business`)";
        $con = $db->connect();
        $q_dwn = mysqli_query($con, $sql_dwn);
        $db->dbDisconnet($con);

        if($q_dwn) {
            $con = $db->connect();
            $_business_ratio = get_business_ratio($con, $user_id);
            $db->dbDisconnet($con);
            $business_1 = $_business_ratio['max_value'];
            $business_2 = $_business_ratio['min_value'];
        }
        else {
            $business_1 = 0;
            $business_2 = 0;
        }
        $ratio = "$business_1 : $business_2";
        $result = array('status' => 1, 'result' => $ratio);
    }
    else if($service_type == 'total_business') {
        $con = $db->connect();
        $total_business = downline_member_business($con, $user_code, 'member_package_update_log');
        $db->dbDisconnet($con);
        $result = array('status' => 1, 'result' => round($total_business, 2));
    }

    echo json_encode($result);
}
