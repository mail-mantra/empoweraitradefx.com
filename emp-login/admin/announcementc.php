<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();
$now = now();
$ip = get_ip();
$session_id = session_id();


if(!isset($_SERVER['HTTP_REFERER'])) {
    $systemDenied = true;
    include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $con = $db->connect();
    foreach($_POST as $key => $value) {
        $data[$key] = prevent_injection($con, $value);
    }
    $db->dbDisconnet($con);

        $description = addslashes($_POST['description']);
        $scroll = $data['scroll'];
        if(empty($scroll)) {
            $scroll = 0;
        }
        $query = "INSERT INTO `announcements`(id, `description`, `scroll`, `created_by`) VALUES (1, '$description', '$scroll', 'admin') ON DUPLICATE KEY UPDATE    
description=VALUES(`description`), scroll=VALUES(`scroll`)";
        $con = $db->connect();
        $sql = mysqli_query($con, $query);
        $num = mysqli_affected_rows($con);
        $db->dbDisconnet($con);
        if($num) {
            $msg = 'Announcement Added Successfully...!';
            $_SESSION['s'] = $msg;
        }
        else {
            $msg = "Temporary problem, try Again...1!";
            $_SESSION['e'] = $msg;
        }

}
else {
    $systemDenied = true;
    include('include/forced-logout.php');
}

header("Location: $back");
die;