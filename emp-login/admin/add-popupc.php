<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();
$now = now();
//print_r($_FILES);die();
if(!isset($_SERVER['HTTP_REFERER'])) {
    $systemDenied = true;
    include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];


if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'Save') {

    if($_FILES['popup_pic']['size'] > 0) {
        //getn priveios data
        // popup_pic --- start
        $ad_photo_file = img_save('popup_pic', 1000, 1000, '../assets/images/popup/');
        if($ad_photo_file['msg'] == 'Success') {
            $data['panel_for'] = "WEBSITE";
            $image_url = $ad_photo_file['img_name'];

            $con = $db->connect();
            $sql_get = "SELECT id, image FROM popups WHERE `id` = '1'";
            $result = $con->query($sql_get);
            $con->close();
            if($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $old_image = $row["image"];
                $sql_main = "UPDATE popups SET image = '$image_url' WHERE id = 1";
            }
            else {
                $sql_main = "INSERT INTO `popups`(`id`, `panel_for`, `image`) VALUES ('1', 'popup', '$image_url')";
            }

            /*
            echo $sql_main;
            die;
            */

            $con = $db->connect();
            $q = mysqli_query($con, $sql_main);
            $db->dbDisconnet($con);
            if($q) {
                $_SESSION['s'] = "Popup Photo Posted Successfully.";
            }
            else {
                $_SESSION['e'] = "Temporary Error...!";
            }
        }
        else {
            $_SESSION['e'] = 'Unable to Upload, Please try again.';
        }
    }
    else {
        $_SESSION['e'] = "Popup Photo not present...";
    }
    header("Location: $back");
    die;
}
elseif($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_REQUEST['id'])) {

    $id = hash_decode($_REQUEST['id']);
    if($id == '') {
        $_SESSION['e'] = "Popup selection error...!";
    }
    else {
        $con = $db->connect();
        $sql_del = "DELETE FROM popups WHERE id='" . $id . "'";
        $q = mysqli_query($con, $sql_del);
        $db->dbDisconnet($con);
        if($q) {
            $_SESSION['s'] = "Popup deleted Successfully.";
        }
        else {
            $_SESSION['e'] = "Temporary Error...!";
        }
    }
    header("Location: $back");
    die;
}
else {
    $systemDenied = true;
    include('include/forced-logout.php');
}
