<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include('../class/DbClass.php');
include('../lib/my_function.php');
include('../lib/classDbTable.php');

$db = new Database();
$now = now();
$ip = get_ip();


// Parse JSON input
$input = json_decode(file_get_contents("php://input"), true);
if (!$input) {
    echo json_encode(["status" => 0, "msg" => "Invalid JSON input"]);
    exit;
}

$data = array_map(function($val) use ($db) {
    return prevent_injection($db->connect(), $val);
}, $input);

if (empty($data['intro']) || empty($data['name']) || empty($data['email']) || empty($data['mobile'])) {
    echo json_encode(["status" => 0, "msg" => "Missing required fields"]);
    exit;
}

if (!is_numeric($data['mobile']) || strlen($data['mobile']) !== 10) {
    echo json_encode(["status" => 0, "msg" => "Invalid mobile number"]);
    exit;
}

// OTP/Email validation skipped or use your session check here

$data['pan_no'] = 'N/A';
$data['adhar_no'] = '';
$data['intro'] = strtoupper(trim($data['intro']));

$valid_intro = 0;
$intro_level = 1;
$intro_id = 0;

if ($data['intro'] === 'ADMIN') {
    $valid_intro = 1;
} else {
    $con = $db->connect();
    $function_member = member_code($con, $data['intro']);
    $db->dbDisconnet($con);
    if ($function_member['valid_member']) {
        $valid_intro = 1;
        $intro_id = $function_member['member_id'];
        $intro_level = $function_member['intro_level'] + 1;
    }
}

if (!$valid_intro) {
    echo json_encode(["status" => 0, "msg" => "Invalid introducer code"]);
    exit;
}

// Generate member code and password
$con = $db->connect();
$mem_code = new_code($con, 'member', 'mem_code', PREFIX_MEMBER_CODE);
$password = generatenumber(4, '123456789');
$token = get_new_token($con);
$intro_mtree = intro_mtree_generate($con, $data['intro']);
$db->dbDisconnet($con);

// Final SQL Call
$sql1 = "CALL ADD_MEMBER('$mem_code', '$password', '$intro_id', '{$data['intro']}', '$now', '$intro_level', '$intro_mtree', '{$data['name']}', '{$data['mobile']}', 'system', '$now', 1, '$session_id', '$ip', '', '', '', '', '{$data['pan_no']}', '', '{$data['email']}', '', '{$data['adhar_no']}', '$token')";

$con = $db->connect();
$result = mysqli_query($con, $sql1);
$r1 = mysqli_fetch_assoc($result);
$db->dbDisconnet($con);

if ($r1 && $r1['return_id'] >= 1) {
    echo json_encode([
        "status" => 1,
        "msg" => "Registration successful",
        "mem_code" => $mem_code,
        "password" => $password
    ]);
} else {
    echo json_encode(["status" => 0, "msg" => "Something went wrong"]);
}