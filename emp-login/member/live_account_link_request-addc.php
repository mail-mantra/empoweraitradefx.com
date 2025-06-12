<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');

// require_once(__DIR__ . '/../../config/spaces_config.php');
// $space = new SpacesConnect(SPACE_ACCESS_KEY, SPACE_SECRET_KEY, SPACE_SPACENAME, SPACE_REGION);

$db = new Database();
$now = now();
if (!isset($_SERVER['HTTP_REFERER'])) {
    $systemDenied = true;
    include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['octa_mail_id']) && isset($_POST['octa_password']) && isset($_POST['investment']) && isset($_POST['mobile_no']) && isset($_POST['broker_name'])) {
    //	extract($_POST);

    $post_data = post_data();

    if ($post_data['octa_mail_id'] == '') {
        $_SESSION['e'] = "Octa Email Id Required";
    } elseif ($post_data['octa_password'] == '') {
        $_SESSION['e'] = "Octa Password Required";
    } elseif ($post_data['investment'] == '') {
        $_SESSION['e'] = "Investment Required";
    } elseif ($post_data['mobile_no'] == '') {
        $_SESSION['e'] = "Mobile Number Required";
    } elseif ($post_data['broker_name'] == '') {
        $_SESSION['e'] = "Broker Name Required";
    } else {
        $user_id = $_SESSION['user_data']['user_id'];
        $user_code = $_SESSION['user_data']['user_code'];


        $con = $db->connect();
        $sql_insert = "INSERT INTO `live_account_link_request`(`member_id`, `octa_mail_id`, `octa_password`, `investment`, `mobile_no`, `broker_name`, `created_by`) VALUES ($user_id, '{$post_data['octa_mail_id']}', '{$post_data['octa_password']}', '{$post_data['investment']}', '{$post_data['mobile_no']}', '{$post_data['broker_name']}', '$user_code')";
        $q_insert = mysqli_query($con, $sql_insert);
        $n_insert = $con->insert_id;
        $db->dbDisconnet($con);

        if ($q_insert && $n_insert) {
            $_SESSION['s'] = "Your Request Added Successfully.";
        } else {
            $_SESSION['e'] = "Temporary Error...!";
        }
    }
} else {
    $systemDenied = true;
    include('include/forced-logout.php');
}


if (isset($_REQUEST['response']) && $_REQUEST['response'] == 'json') {
    header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    header("Pragma: no-cache"); // HTTP 1.0.
    header("Expires: 0"); // Proxies.
    header("Content-Type: application/json;charset=utf-8");

    $result['back'] = $back;
    $result['status'] = 1;
    $result['data'] = array();
    $result['message'] = 'Temporary Problem' . __LINE__;
    $result['w'] = '';

    if (isset($_SESSION['s']) && $_SESSION['s']) {
        $result['status'] = 0;
        $result['message'] = $_SESSION['s'];
        $result['refresh'] = 0;
        // $result['redirect'] = $back;
        unset($_SESSION['s']);
    } else if (isset($_SESSION['e']) && $_SESSION['e']) {
        $result['message'] = $_SESSION['e'];
        unset($_SESSION['e']);
    } else if (isset($_SESSION['w']) && $_SESSION['w']) {
        $result['message'] = "Test" . $_SESSION['w'];
        $result['w'] = $_SESSION['w'];
        unset($_SESSION['w']);
    } else {
    }
    echo json_encode($result);
} else {
    header('location:' . $back);
    die;
}
