<?php
ini_set('memory_limit', -1);

include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
//include('../lib/mail_sms.php');

$db = new Database();

$now = now();
$ip = get_ip();
$session_id = session_id();


if(!isset($_SERVER['HTTP_REFERER'])) {
    $systemDenied = true;
    include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];

if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'Submit') {
    $data = post_data();

    if($data['declaration']) {
        if($data['refund_amount']) {
            if($data['transaction_date']) {
                if($data['transaction_number']) {
                    $user_id = $_SESSION['user_data']['user_id'];
                    $user_code = $_SESSION['user_data']['user_code'];

                    $sql_exist = "SELECT id FROM refunds WHERE current_status<>2 AND `member_id`='$user_id' AND MONTH(CURDATE()) = MONTH(`created_at`) AND YEAR(CURDATE()) = YEAR(`created_at`); ";
                    $con = $db->connect();
                    $res_exist = $con->query($sql_exist);
                    $con->close();

                    if($res_exist->num_rows == 0) {

                        $transaction_img = '';
                        if($_FILES['transaction_img']['error'] == 0) {
                            if(($_FILES['transaction_img']['type'] == 'image/jpeg') || ($_FILES['transaction_img']['type'] == 'image/pjpeg')) {
                                if(getimagesize($_FILES["transaction_img"]["tmp_name"]) !== false) {

                                    $uploadFileType = strtolower(pathinfo($_FILES["transaction_img"]["name"], PATHINFO_EXTENSION));
                                    if($_FILES["transaction_img"]["size"] > 50 * 1024 * 1024) {
                                        // 500 * 1024 * 1024 -- 50MB
                                        // Check file size
                                        $_SESSION['e'] = 'Sorry, your file is too large.';
                                    }
                                    elseif($uploadFileType != "jpg" && $uploadFileType != "jpeg") {
                                        $_SESSION['e'] = "Sorry, only JPG & JPEG files are allowed.";
                                    }
                                    else {
                                        // transaction_img --- start
                                        if($_FILES["transaction_img"]["name"] == NULL) {

                                            $_SESSION['e'] = "Please upload your photo.";
                                        }
                                        else {
                                            $photo_file = img_save('transaction_img', 500, 500, '../assets/images/screenshot/');

                                            if($photo_file['msg'] == 'Success') {

                                                $photo_img_name = $photo_file['img_name'];
                                                // transaction_img --- end

                                                $user_id = $_SESSION['user_data']['user_id'];
                                                $user_code = $_SESSION['user_data']['user_code'];

                                                // usd rate
                                                $con = $db->connect();
                                                $q_get = mysqli_query($con, "SELECT * FROM `refund_rate` ORDER BY id DESC LIMIT 1");
                                                $con->close();
                                                if(mysqli_num_rows($q_get)) {
                                                    $r1 = $q_get->fetch_assoc();
                                                    $usd_rate = $r1['rate'];

                                                    $reference_id = "RE" . time() . uniqid(mt_rand());
                                                    $inr_amount = $data['refund_amount'] * $usd_rate;

                                                    $sql_refunds = "INSERT INTO `refunds`
                                                        (`member_id`, `amount`, `usd_amount`, `reference_id`, `transaction_number`, `screenshot`, `input_date`, `created_by`) VALUES 
                                                        ('$user_id', '{$inr_amount}', '{$data['refund_amount']}','{$reference_id}','{$data['transaction_number']}', '{$photo_img_name}', '{$data['transaction_date']}', '$user_code' )";

                                                    $con = $db->connect();
                                                    $res_refunds = $con->query($sql_refunds);
                                                    $n_refunds = $con->insert_id;
                                                    $con->close();

                                                    if($res_refunds && $n_refunds) {
                                                        $_SESSION['s'] = "Detail added Successfully.";
                                                    }
                                                    else {
                                                        $_SESSION['e'] = "Temporary Problem " . __LINE__;
                                                    }
                                                }
                                                else {
                                                    $_SESSION['e'] = "USD rate not found.";
                                                }
                                            }
                                            else {
                                                $_SESSION['e'] = "Unable to Upload, Please try again.";

                                            }
                                        }
                                        // transaction_img --- end
                                    }
                                }
                                else {
                                    $_SESSION['e'] = "File is not an image.";
                                }
                            }
                            else {
                                $_SESSION['e'] = "Invalid File type : " . $_FILES['transaction_img']['type'];
                            }
                        }
                        elseif(isset($phpFileUploadErrors[$_FILES['transaction_img']['error']])) {
                            $_SESSION['e'] = $phpFileUploadErrors[$_FILES['transaction_img']['error']];
                        }
                        else {
                            $_SESSION['e'] = "Temporary Problem" . __LINE__ . " (error:" . $_FILES['transaction_img']['error'] . ")";
                        }
                    }
                    else {
                        $daysRemaining = (int)date('t', time()) - (int)date('j', time());

                        $_SESSION['e'] = "Only one refund allowed in a month, Please try after {$daysRemaining} days";
                    }
                }
                else {
                    $_SESSION['e'] = 'Transaction Number Required';
                }
            }
            else {
                $_SESSION['e'] = 'Transaction Date Required';
            }
        }
        else {
            $_SESSION['e'] = 'Amount Required';
        }
    }
    else {
        $_SESSION['e'] = 'Please check declaration Required';
    }


}
else {
    $systemDenied = true;
    include('include/forced-logout.php');
}




if(isset($_REQUEST['response']) && $_REQUEST['response'] == 'json') {
    header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    header("Pragma: no-cache"); // HTTP 1.0.
    header("Expires: 0"); // Proxies.
    header("Content-Type: application/json;charset=utf-8");

    $result['back'] = $back;
    $result['status'] = 1;
    $result['data'] = array();
    $result['message'] = 'Temporary Problem'.__LINE__;
    $result['w'] = '';

    if(isset($_SESSION['s']) && $_SESSION['s']) {
        $result['status'] = 0;
        $result['message'] = $_SESSION['s'];
        $result['refresh'] = 1;
        // $result['redirect'] = $back;
        unset($_SESSION['s']);
    }
    else if(isset($_SESSION['e']) && $_SESSION['e']) {
        $result['message'] = $_SESSION['e'];
        unset($_SESSION['e']);
    }
    else if(isset($_SESSION['w']) && $_SESSION['w']) {
        $result['message'] = "Test".$_SESSION['w'];
        $result['w'] = $_SESSION['w'];
        unset($_SESSION['w']);
    }
    else {

    }
    echo json_encode($result);
}
else {
    header('location:'.$back);
    die;
}
