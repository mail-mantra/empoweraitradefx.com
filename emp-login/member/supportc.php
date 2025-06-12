<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
include(__DIR__ . '/../lib/smtp_function.php');
/* require_once(__DIR__.'/../../config/spaces_config.php');
$space = new SpacesConnect(SPACE_ACCESS_KEY, SPACE_SECRET_KEY, SPACE_SPACENAME, SPACE_REGION); */

$db = new Database();
$now = now();
if (!isset($_SERVER['HTTP_REFERER'])) {
    $systemDenied = true;
    include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'Submit') {
    //	extract($_POST);

    $post_data = post_data();

    $screenshots_url = '';

    /* if ($_FILES["screenshots"]["name"] != NULL) {
        if ($_FILES['screenshots']['error'] == 0) {
            if (($_FILES['screenshots']['type'] == 'image/jpeg') || ($_FILES['screenshots']['type'] == 'image/pjpeg') || ($_FILES['screenshots']['type'] == 'image/png')) {
                if (getimagesize($_FILES["screenshots"]["tmp_name"]) !== false) {

                    $uploadFileType = strtolower(pathinfo($_FILES["screenshots"]["name"], PATHINFO_EXTENSION));

                    if ($_FILES["screenshots"]["size"] > 50 * 1024 * 1024) {
                        // 500 * 1024 * 1024 -- 50MB
                        // Check file size
                        $_SESSION['e'] = 'Sorry, your file is too large.';
                    } elseif ($uploadFileType != "jpg" && $uploadFileType != "jpeg" && $uploadFileType != "png") {
                        $_SESSION['e'] = "Sorry, only JPG & JPEG & PNG files are allowed.";
                    } else {
                        // screenshots --- start

                        $compression_quality = compression_quality($_FILES['screenshots']);
                        try {
                            $contents = webpConvert2_mm($_FILES['screenshots']['tmp_name'], $compression_quality);
                        } catch (Exception $exception) {
                            $contents = false;
                        }

                        if ($contents === false) {
                            $_SESSION['e'] = "Unable to Upload, Please try with a different(smaller) file..";
                            header("Location: $back");
                            die;
                        } else {
                            $folder_name = "screenshots/";
                            $mime_type = mime_content_type($contents);
                            $new_file_name = uniqid(time()) . clean_file_name(basename($contents));
                            $result_screenshots = $space->UploadFile($contents, "public", $folder_name . $new_file_name, $mime_type);
                            // screenshots --- end
                            if ($result_screenshots['@metadata']['statusCode'] == '200') {
                                $screenshots_url = $result_screenshots['@metadata']['effectiveUri'];
                            } else {
                                $_SESSION['e'] = "Temporary problem, Error Code: NON-200";
                            }
                        }
                    }
                } else {
                    $_SESSION['e'] = "File is not an image.";
                }
            } else {
                $_SESSION['e'] = "Invalid File type : " . $_FILES['screenshots']['type'];
            }

            unset($_FILES["screenshots"]["tmp_name"]);
        } elseif (isset($phpFileUploadErrors[$_FILES['screenshots']['error']])) {
            $_SESSION['e'] = "Error 291 :" . $phpFileUploadErrors[$_FILES['screenshots']['error']];
        } else {
            $_SESSION['e'] = "Temporary Problem" . __LINE__ . " (error:" . $_FILES['screenshots']['error'] . ")";
        }
    } else {
        $screenshots_url = '';
    } */

    if ($_FILES['screenshots']['size'] > 0) {
        $photo_file = img_save('screenshots', 1000, 1000, '../assets/images/screenshots/');
        if ($photo_file['msg'] == 'Success') {
            $screenshots_url = $photo_file['img_name'];
        } else {
            $_SESSION['e'] = 'Unable to Upload, Please try again.';
        }
    }/*  else {
        $_SESSION['e'] = "Photo not present...";
    } */

    if (!isset($_SESSION['e'])) {
        $con = $db->connect();
        $sql_insert = "INSERT INTO `support_query`(`sup_cat_id`, `sup_query_desc`, `member_id`,`created_on`, `screenshots_url`) VALUES ('{$post_data['category']}','{$post_data['description']}','$user_id','$now', '$screenshots_url')";
        $q_insert = mysqli_query($con, $sql_insert);
        $last_id = mysqli_insert_id($con);
        $db->dbDisconnet($con);

        if ($q_insert) {
            $_SESSION['s'] = "Thanks! Our Support Executive Will Solve Your Query Soon.";
            $token = base64_encode(sprintf('%09d', $last_id));
            $image = !empty($screenshots_url) ? 'https://mhadvanfinancial.com/mdf-login/' . $screenshots_url : 'NA';
            /* mail*/
            $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                            <title>Mhadvan Financial</title>
                        </head>
                        <body>
                        <div style="width:500px; height:430px; margin:0 auto; padding:20px 5px; background:#292d35">
                            <div style="width:400px; height:300px; margin:0 auto; padding:25px; background:#fff;">
                                <h5 style="font-family: Arial, Helvetica, sans-serif; font-size:20px; font-weight:bold; line-height:20px;">Token #' . $token . '</h5>
                                <p style="font-family: Arial, Helvetica, sans-serif; font-size:16px; color:#3d3c41;  line-height:20px;">Username : ' . $user_code . ' </p>
                                <p style="font-family: Arial, Helvetica, sans-serif; font-size:16px; color:#3d3c41;  line-height:20px; margin-bottom:20px;">Query : ' . $post_data['description'] . ' </p>
                                <p style="font-family: Arial, Helvetica, sans-serif; font-size:16px; color:#3d3c41;  line-height:20px; margin-bottom:20px;">Screenshot : ' . $image . ' </p>
                            </div>
                            <p  style="font-family: Arial, Helvetica, sans-serif; font-size:14px; text-align:center; color:#FFFFFF;  line-height:28px;">© 2023 ' . PROJECT_NAME . '. All Rights Reserved </p>
                        </div>
                        </body>
                        </html>';
            $mail_to = 'info@mhadvanfinancial.com';
            $mail_subject = "Support Query from " . $user_code;
            $mail_message = $message;
            mm_smtp($mail_to, $mail_subject, $mail_message);
            /* mail*/
        } else {
            /*  if ($screenshots_url) {
                $space->DeleteObject(space_url_to_path($screenshots_url));
            } */
            $_SESSION['e'] = "Temporary Error...!";
        }
    }
    header("Location: $back");
    die;
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'comment') {
    if (isset($_REQUEST['complain_id']) && isset($_REQUEST['comment'])) {
        if (!empty($_REQUEST['complain_id']) && !empty($_REQUEST['comment'])) {
            extract($_POST);
            $con = $db->connect();
            $comment = mysqli_real_escape_string($con, $comment);
            $query = "INSERT INTO `support_query_answers`(`sup_query_id`, `sup_query_ans_desc`, `created_by`, `created_on`) VALUES ('$complain_id', '$comment', '$user_id', '$now')";
            $sql = mysqli_query($con, $query);
            $num = mysqli_affected_rows($con);
            $db->dbDisconnet($con);
            if ($num == 1) {
                $msg = "Comment Successfully Added...!";
                $_SESSION['s'] = $msg;
                $status = 1;
            } else {
                $msg = "Temporary problem, try again...!";
                $_SESSION['e'] = $msg;
                $status = 0;
            }
        } else {
            $msg = "Temporary problem, try again...!";
            $_SESSION['e'] = $msg;
            $status = 0;
        }
    } else {
        $msg = "Temporary problem, try again...!";
        $_SESSION['e'] = $msg;
        $status = 0;
    }
    $result = array('status' => $status, 'msg' => $msg);
    echo json_encode($result);
} else {
    $systemDenied = true;
    include('include/forced-logout.php');
}
