<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
require_once(__DIR__.'/../../config/spaces_config.php');
$space = new SpacesConnect(SPACE_ACCESS_KEY, SPACE_SECRET_KEY, SPACE_SPACENAME, SPACE_REGION);

$db = new Database();
$now = now();

if(!isset($_SERVER['HTTP_REFERER'])) {
    $_SESSION['e'] = "Invalid Call..!";
    header("Location: $back");
    die;
}
@$back = $_SERVER['HTTP_REFERER'];

if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit_pan'] == 'Upload') {
    if($_FILES["pan_img"]["name"] == NULL) {
        $_SESSION['e'] = "Please upload photo of your pan card.";
    }
    else {
        $con = $db->connect();
        $qs = mysqli_query($con, "select pan_img from kyc_details where member_id ='".$user_id."'");
        $db->dbDisconnet($con);
        $rs = mysqli_fetch_assoc($qs);
//		$current_pan=$rs['pan_img'];


        if($_FILES['pan_img']['error'] == 0) {
            if(($_FILES['pan_img']['type'] == 'image/jpeg') || ($_FILES['pan_img']['type'] == 'image/pjpeg')) {
                if(getimagesize($_FILES["pan_img"]["tmp_name"]) !== false) {

                    $uploadFileType = strtolower(pathinfo($_FILES["pan_img"]["name"], PATHINFO_EXTENSION));
                    if($_FILES["pan_img"]["size"] > 50 * 1024 * 1024) {
                        // 500 * 1024 * 1024 -- 50MB
                        // Check file size
                        $_SESSION['e'] = 'Sorry, your file is too large.';
                    }
                    elseif($uploadFileType != "jpg" && $uploadFileType != "jpeg") {
                        $_SESSION['e'] = "Sorry, only JPG & JPEG files are allowed.";
                    }
                    else {
                        // pan_img --- start

                        $compression_quality = compression_quality($_FILES['pan_img']);
                        try {
                            $contents = webpConvert2_mm($_FILES['pan_img']['tmp_name'], $compression_quality);
                        } catch(Exception $exception) {
                            $contents = false;
                        }
                        if($contents === false) {
                            $_SESSION['e'] = "Unable to Upload, Please try with a different(smaller) file..";
                            header("Location: $back");
                            die;
                        }
                        else {
                            $folder_name = "pan_img/";
                            $mime_type = mime_content_type($contents);
//                            $new_file_name = uniqid(time()).clean_file_name(basename($contents));
                            $new_file_name = clean_file_name($user_code.$user_full_name.uniqid(time()).basename($contents));
                            $result_pan_img = $space->UploadFile($contents, "public", $folder_name.$new_file_name, $mime_type);
                            // pan_img --- end
                            if($result_pan_img['@metadata']['statusCode'] == '200') {
                                $pan_img_name = $result_pan_img['@metadata']['effectiveUri'];
                                // --------------- CONTENT ---------------------

                                $con = $db->connect();
                                $qs = mysqli_query($con, "select pan_img from kyc_details where member_id ='" . $user_id . "'");
                                $db->dbDisconnet($con);
                                $rs = mysqli_fetch_assoc($qs);
                                $current_pan = $rs['pan_img'];

                                $sql = "INSERT INTO  kyc_details (member_id, pan_img, created_on)
                                        VALUES ('" . $user_id . "', '" . $pan_img_name . "', '" . $now . "')
                                        ON DUPLICATE KEY UPDATE
                                        pan_img =  '" . $pan_img_name . "',
                                        pan_status = '0',
                                        created_on =  '" . $now . "'";


                                $con = $db->connect();
                                $res1 = mysqli_query($con, $sql);
                                $n1 = $con->affected_rows;
                                $err = $con->error;
                                $con->close();

                                if($res1 && $n1) {
                                    if (filter_var($current_pan, FILTER_VALIDATE_URL) && (strpos($current_pan, 'https://'.SPACE_SPACENAME.'.'.SPACE_REGION.'.digitaloceanspaces.com/') !== false)) {
                                        $result_DeleteObject = $space->DeleteObject(space_url_to_path($current_pan));
                                    }
                                    $_SESSION['s'] = "Your PAN updated successfully.";
                                }
                                elseif($err) {
                                    $result_DeleteObject = $space->DeleteObject(space_url_to_path($pan_img_name));
                                    $_SESSION['e'] = $err;
                                }
                                else {
                                    $result_DeleteObject = $space->DeleteObject(space_url_to_path($pan_img_name));
                                    $_SESSION['e'] = "Temporary Error...!";
                                }
                                // --------------- CONTENT ----- end ---------------------

                            }
                            else {
                                $_SESSION['e'] = "Unable to Upload, Please try again.";
                                header("Location: $back");
                                die;
                            }
                        }
                        // pan_img --- end
                    }
                }
                else {
                    $_SESSION['e'] = "File is not an image.";
                }
            }
            else {
                $_SESSION['e'] = "Invalid File type : ".$_FILES['pan_img']['type'];
            }

            unset($_FILES["pan_img"]["tmp_name"]);

        }
        elseif(isset($phpFileUploadErrors[$_FILES['pan_img']['error']])) {
            $_SESSION['e'] = $phpFileUploadErrors[$_FILES['pan_img']['error']];
        }
        else {
            $_SESSION['e'] = "Temporary Problem".__LINE__." (error:".$_FILES['pan_img']['error'].")";
        }


    }
    header("Location: $back");
    die;
}
else {
    $_SESSION['e'] = "Invalid Call.";
    header("Location: $back");
    die;
}
?>