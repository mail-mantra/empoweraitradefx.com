<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();
$now = now();
//print_r($_FILES);die();
if (!isset($_SERVER['HTTP_REFERER'])) {
    $systemDenied = true;
    include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!empty($_POST['topup_id']) && !empty($_POST['insurance_issue_date']) && $_FILES['voucher_image']['size'] > 0) {
        // voucher_image --- start
        $topup_id = $_POST['topup_id'];
        $insurance_issue_date = $_POST['insurance_issue_date'];

        $ad_photo_file = img_save('voucher_image', 1000, 1000, '../assets/images/voucher/');
        if ($ad_photo_file['msg'] == 'Success') {
            $image_url = $ad_photo_file['img_name'];
            $sql_main = "UPDATE member_package_update_log SET 
                        insurance_issue_date = '$insurance_issue_date', 
                        voucher_image = '" . $image_url . "', 
                        insurance_status = '1',
                        insurance_status_date = '" . now() . "' 
                        WHERE id = '" . $topup_id . "'";
            $con = $db->connect();
            $q = mysqli_query($con, $sql_main);
            $db->dbDisconnet($con);
            if ($q) {
                $_SESSION['s'] = "Insurance Update Successfully.";
            } else {
                $_SESSION['e'] = "Temporary Error...!";
            }
        } else {
            $_SESSION['e'] = 'Unable to Upload, Please try again.';
        }
    } else {
        $_SESSION['e'] = "All the fields are mandatory";
    }
} else {
    $_SESSION['e'] = "Invalid Call.";
}
header("Location: $back");
die;
