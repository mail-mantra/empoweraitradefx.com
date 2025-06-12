<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
// include('../lib/mail_sms.php');
// include('../smtp/mail.php');
$db = new Database();
$now = now();

if (!isset($_SERVER['HTTP_REFERER'])) {
    $systemDenied = true;
    die('3');
    include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];


if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'Save') {

    $con = $db->connect();
    foreach ($_POST as $key => $value) {
        $post_data[$key] = prevent_injection($con, $value);
    }
    $db->dbDisconnet($con);
    $post_data['id'] = hash_decode($post_data['id']);

    if (is_numeric($post_data['id']) && $post_data['id'] > 0) {
        $member_id = $post_data['id'];
    } else {
        $systemDenied = true;
        include('include/forced-logout.php');
    }

    if (
        $post_data['gender'] == ''
        || $post_data['father_name'] == ''
        || $post_data['mother_name'] == ''
        || $post_data['marital'] == ''
        || $post_data['free_insurance'] == ''
    ) {

        $_SESSION['e'] = "Please enter the mandatory fields";
    }/*else if(!is_numeric($post_data['mobile']) || strlen($post_data['mobile'])<10 || strlen($post_data['mobile'])>10){
		
		$_SESSION['e']="Please enter a valid mobile no.";
	
    }*/ else {

        if ($post_data['dob'] == '') {
            $post_data['dob'] = 'NULL';
        } else {
            $post_data['dob'] = $post_data['dob'];
        }

        if ($post_data['marital'] === 'Married' && $post_data['spouse_name'] === '') {
            $_SESSION['e'] = "Spouse Name Required..!";
            header("Location: $back");
            die;
        } else {
            $post_data['spouse_name'] = '';
        }

        if ($post_data['free_insurance'] === 'yes') {
            if ($post_data['nominee_name'] === '') {
                $_SESSION['e'] = "Nominee Name Required..!";
                header("Location: $back");
                die;
            }
            if ($post_data['relation_with_nominee'] === '') {
                $_SESSION['e'] = "Relation with Nominee Required..!";
                header("Location: $back");
                die;
            }

            if ($post_data['nominee_dob'] === '') {
                $_SESSION['e'] = "Nominee DOB Required..!";
                header("Location: $back");
                die;
            } else {
                $post_data['nominee_dob'] = ymd($post_data['nominee_dob']);
            }
        } else {
            $post_data['nominee_name'] = '';
            $post_data['relation_with_nominee'] = '';
            $post_data['nominee_dob'] = today();
        }


        $sql1 = "UPDATE  member_details SET  
		           `profile_update` =  (`profile_update` + 1),
				   mem_dob =  '" . $post_data['dob'] . "',
				   gender =  '" . $post_data['gender'] . "',

				   `father_name` =  '" . $post_data['father_name'] . "',
				   `mother_name` =  '" . $post_data['mother_name'] . "',
				   `father_name` =  '" . $post_data['father_name'] . "',

				   `marital` =  '" . $post_data['marital'] . "',
				   `spouse_name` =  '" . $post_data['spouse_name'] . "',
				   `free_insurance` =  '" . $post_data['free_insurance'] . "',
				   `nominee_name` =  '" . $post_data['nominee_name'] . "',
				   `relation_with_nominee` =  '" . $post_data['relation_with_nominee'] . "',
				   `nominee_dob` =  '" . $post_data['nominee_dob'] . "', 
				   
				   updated_by =  '" . $action_by . "',
				   updated_on =  '" . $now . "'
				   WHERE member_id ='" . $member_id . "'";

        $sql2 = "UPDATE  member SET  
		           aadhar =  '" . $post_data['aadhar'] . "',
				   gender =  '" . $post_data['gender'] . "',
				   updated_by =  '" . $action_by . "',
				   updated_on =  '" . $now . "'
				   WHERE member_id ='" . $member_id . "'";
        /*echo $sql1 . "<br>". $sql2;
        die;*/

        $con = $db->connect();
        mysqli_autocommit($con, FALSE);
        $q1 = mysqli_query($con, $sql1);
        $q2 = mysqli_query($con, $sql2);

        if ($q1 && $q2) {
            mysqli_commit($con);
            $_SESSION['s'] = "Your Profile updated successfully.";
        } else {
            $_SESSION['e'] = "Temporary Error...!";
            mysqli_rollback($con);
        }
        $db->dbDisconnet($con);
    }
    header("Location: $back");
    die;
} else {
    $_SESSION['e'] = "Invalid Call..!";
    header("Location: $back");
    die;
}
