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
    try {
        if(!(isset($post_data['mem_code']) && $post_data['mem_code'])) {
            throw new Exception('Member Code Required');
        }
        if(!(isset($post_data['user_code']) && $post_data['user_code'])) {
            throw new Exception('User Code Required');
        }

        $mem_code = $post_data['mem_code'];
        $user_code = $post_data['user_code'];

        $con = $db->connect();
        $member = member_code($con, $mem_code);
        $db->dbDisconnet($con);
        $valid_member = $member['valid_member'];

        if($valid_member == 0) {
            throw new Exception('Invalid Member Code');
        }
        else {
            $con = $db->connect();
            $is_downline = is_downline($con, $user_code, $mem_code);
            $db->dbDisconnet($con);
            if($is_downline) {
                $response["status"] = 1;
                $response["msg"] = 'success';
                $response["name"] = $member['name'];
            }
            else {
                throw new Exception('Not a downline Member');
            }
        }

    }
    catch(Exception $exception) {
        $response['msg'] = $exception->getMessage();
    }

}
else {
    $response = array('status' => 0, 'msg' => 'Invalid API Key', 'result' => '');
}
echo json_encode($response, JSON_PRETTY_PRINT);



