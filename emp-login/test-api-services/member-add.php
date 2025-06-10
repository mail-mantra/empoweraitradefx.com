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
        if(!(isset($post_data['intro']) && $post_data['intro'])) {
            throw new Exception('Intro Code Required');
        }
        if(!(isset($post_data['name']) && $post_data['name'])) {
            throw new Exception('Name Required');
        }
        if(!(isset($post_data['email']) && $post_data['email'])) {
            throw new Exception('Email Required');
        }
        if(!(isset($post_data['mobile']) && $post_data['mobile'])) {
            throw new Exception('Mobile Required');
        }

        if(!is_numeric($post_data['mobile']) || strlen($post_data['mobile']) < 10 || strlen($post_data['mobile']) > 10) {
            throw new Exception("Please enter a valid mobile no.");
        }

        if(!filter_var($post_data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        $post_data['intro'] = trim($post_data['intro']);
        $post_data['intro'] = strtoupper($post_data['intro']);


        $status = 1;
        $capping = 0;


        if($post_data['intro'] == 'ADMIN' || $post_data['intro'] == 'admin') {
            $intro_level = 1;
            $intro_id = 0;
            $valid_intro = 1;
        }
        else {
            $con = $db->connect();
            $function_member = member_code($con, $post_data['intro']) ?? false;
            $db->dbDisconnet($con);
            if($function_member && $function_member['valid_member']) {
                $in_level = $function_member['intro_level'];
                $intro_level = $in_level + 1;
                $intro_id = $function_member['member_id'];
                $valid_intro = $function_member['valid_member'];
            }
            else {
                throw new Exception('Invalid intro code : ' . $post_data['intro']);
            }
        }

        if($valid_intro == 0) {
            throw new Exception("Invalid Introducer.");
        }

        $con = $db->connect();
        $intro_mtree = intro_mtree_generate($con, $post_data['intro']);
        $mem_code = new_code($con, 'member', 'mem_code', PREFIX_MEMBER_CODE);
        $token = get_new_token($con);
        $db->dbDisconnet($con);
        $password = generatenumber(4, '123456789');


        $con = $db->connect();
        $sql21 = mysqli_query($con, "select * from member where mem_code='" . $mem_code . "'");
        $db->dbDisconnet($con);
        if(mysqli_num_rows($sql21) == 1) {
            throw new Exception($mem_code . " already exists. Try with another ID");
        }

        $post_data['state'] = '';
        $post_data['address'] = '';
        $post_data['country'] = '';
        $post_data['adhar_no'] = '';

        $post_data['pan_no'] = ''; //strtoupper(trim($post_data['pan_no']));

        $action_by = '';
        $session_id = session_id();
        $ip = get_ip();
        $now = now();

        $sql1 = "CALL ADD_MEMBER('" . $mem_code . "','" . $password . "','" . $intro_id . "','" . $post_data['intro'] . "','" . $now . "','" . $intro_level . "','" . $intro_mtree . "','" . $post_data['name'] . "','" . $post_data['mobile'] . "','" . $action_by . "','" . $now . "','" . $status . "','" . $session_id . "','" . $ip . "','','" . $post_data['state'] . "','" . $post_data['country'] . "','','" . strtoupper($post_data['pan_no']) . "','','" . $post_data['email'] . "','" . $post_data['address'] . "','" . $post_data['adhar_no'] . "','" . $token . "')";

        //echo $sql1; die;

        $con = $db->connect();
        $q1 = mysqli_query($con, $sql1);
        $db->dbDisconnet($con);
        $r1 = mysqli_fetch_assoc($q1);
        $n = $r1['return_id'];

        if($n >= 1) {

            $mobile = $post_data['mobile'];
            $mem_name = $post_data['name'];


            $arr_email = [
                'name' => $mem_name,
                'accountId' => $mem_code,
                'password' => $password,
            ];

            $message = getWelcomeEmailHtml($arr_email);
            $mail_to = $post_data['email'];
            $mail_subject = "Welcome To " . PROJECT_NAME;
            $mail_message = $message;
            mm_smtp($mail_to, $mail_subject, $mail_message);
            /* mail*/

            $msg = "Successfully added. Your Login ID is $mem_code and password is $password";

            $response = array('status' => 1, 'msg' => $msg, 'result' => '');
        }
        else {
            throw new Exception("Temporary Error...!");
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



