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

        $wallet = $post_data['table'] ?? '';
        if(!$wallet) {
            throw new Exception('Table Name Required');
        }

        $response = [
            'status' => 1,
            'msg' => 'success',
            'result' => round(get_wallet_balance_of_member($con, $user_id, $wallet)??0,2)
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



