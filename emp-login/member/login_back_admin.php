<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
include('../lib/mail_sms.php');
$db = new Database();

$now = now();
$ip = get_ip();
$session_id = session_id();

if(!isset($_SERVER['HTTP_REFERER'])){
    $systemDenied=true;
    include('include/forced-logout.php');
}

@$back = $_SERVER['HTTP_REFERER'];

/* - - - - - - - Under Maintainance - - - - - Start - - - - - - - */
$blocked_upto = "2017-08-16 23:59:59";
$server_date1 = now();
if(strtotime($server_date1) < strtotime($blocked_upto))
{
    $_SESSION['e']='Under Maintainance, We will Back after '.date('d-M-Y h:i:s A',strtotime($blocked_upto)).' ...';
    header("Location:".$back);
    die;
}
/* - - - - - - - Under Maintainance - - - - - End - - - - - - - */

// $back = $_SESSION['url'];

// extract($_POST);

if($_SERVER['REQUEST_METHOD']=="GET" && isset($_SESSION['user_data']['_login_from']) && ($_SESSION['user_data']['_login_from']=='admin'))
{
    $mem_code = trim($_SESSION['user_data']['_login_from']);
    $user_type='admin';

    $sql1 = "select * from admin where binary(username)='".$mem_code."' /* and user_type='$user_type' */";
    $conn1 = $db->connect();
    $res1 = $conn1->query($sql1);
    $conn1->close();
    if($res1->num_rows == 1)
    {
        unset($_SESSION['user_data']['_login_from']);

        $r1 = $res1->fetch_assoc();

        $_SESSION['user_data'] = array(
            'logged_in'=>TRUE,
            'user_type'=>$user_type,
            'username'=>$r1['username'],
            'user_id'=>$r1['id'],
            'user_code'=>'admin',
            'full_name'=>$r1['name'],
            'mobile'=>$r1['mobile'],
            'email'=>$r1['email'],
            'last_login_time'=>$r1['login_time'],
            'dp'=>'default.png'
        );


        $_SESSION['s']="Welcome to Back";
        header("Location:../admin/dashboard.php");
        die;
    }
    else
    {
        $_SESSION['e'] = 'Invalid member..';
    }
}
else
{
    $_SESSION['e'] = 'Invalid url..';
}


header("Location:".$back);
die;


