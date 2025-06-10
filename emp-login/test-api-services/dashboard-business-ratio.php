<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
include('../class/DbClass.php');
include('../lib/my_function.php');
include('../lib/smtp_function.php');
$db = new Database();
$test_api_key = '420c8166803c9ee85629ad505bb0be38';

$response = array('status' => 0, 'msg' => 'Invalid Request', 'result' => '');

if(isset($_POST['api_key']) && $test_api_key == $_POST['api_key']) {
    $post_data = post_data();
    $con = $db->connect();
    try {
        $user_id = $post_data['user_id'] ?? '';
        if(!$user_id) {
            throw new Exception('User Id Required');
        }

        $current_date = date('Y-m-d');

        $sql_dwn = "INSERT INTO member_downline_business(member_id, business)
                        SELECT member_id, INTROWISE_BUSINESS_60_40(member_id, '$current_date') FROM member
                        ON DUPLICATE KEY UPDATE business = VALUES(`business`)";
        $q_dwn = mysqli_query($con, $sql_dwn);

        if($q_dwn) {
            $_business_ratio = get_business_ratio($con, $user_id);
            $business_1 = $_business_ratio['max_value'];
            $business_2 = $_business_ratio['min_value'];
        }
        else {
            $business_1 = 0;
            $business_2 = 0;
        }
        $ratio = "$business_1 : $business_2";


        $response = [
            'status' => 1,
            'msg' => 'success',
            'result' => $ratio
        ];


    }
    catch(Exception $exception) {
        $response['msg'] = $exception->getMessage();
    }
    finally {
        $con->close();
    }

}
else {
    $response = array('status' => 0, 'msg' => 'Invalid API Key', 'result' => '');
}
echo json_encode($response, JSON_PRETTY_PRINT);



