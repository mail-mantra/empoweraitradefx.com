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
        $user_code = $post_data['user_code'] ?? '';
        if(!$user_code) {
            throw new Exception('User Code Required');
        }

        $total_count = (total_downlineCount($con, $user_code)) ?? 0;
        $active_count = (active_team_count($con, $user_code)) ?? 0;
        $inactive_count = $total_count - $active_count;

        $response = [
            'status' => 1,
            'msg' => 'success',
            'result' => [
                'total_count' => $total_count,
                'active_count' => $active_count,
                'inactive_count' => $inactive_count,
            ]
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



