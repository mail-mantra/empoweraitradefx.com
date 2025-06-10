<?php
date_default_timezone_set('Asia/Kolkata');
$current_date = date('Y-m-d');
$this_month_year = date('Y-m', strtotime($current_date));
$first_day_this_month = "$this_month_year-01";

$first_day_previous_month = date('Y-m-d', strtotime('first day of previous month', time()));
$last_day_previous_month = date('Y-m-d', strtotime('last day of previous month', time()));

function LastDay_of_specified_Date($newdate)
{
    $last_day_the_month = date("Y-m-d", strtotime('last day of previous month', strtotime($newdate)));
    return $last_day_the_month;
}

function post_data()
{
    $post_data = $_POST;
    array_walk_recursive($post_data, function(&$v) {
        $v = addslashes(trim($v));
    });
    return $post_data;
}

function dmy($input_date)
{
    return date('d-m-Y', strtotime($input_date));
}

function ymd($input_date)
{
    return date('Y-m-d', strtotime($input_date));
}

function dmy_time($input_date)
{
    return date('d-m-Y h:i:s:a', strtotime($input_date));
}

function ymd_time($input_date)
{
    return date('Y-m-d H:i:s', strtotime($input_date));
}

function time_format($input_date)
{
    return date('h:i:s:a', strtotime($input_date));
}

function now()
{
    $date_time = date('Y-m-d H:i:s');
    return $date_time;
}

function current_time()
{
    $t = date('H:i:s');
    return $t;
}

function today()
{
    $dt = date('Y-m-d');
    return $dt;
}

function date_diffence($date1, $date2)
{
    $date1 = strtotime($date1);
    $date2 = strtotime($date2);
    $seconds_diff = $date1 - $date2;
    return floor($seconds_diff / 3600 / 24);
}

function date_diffence_sec($date1, $date2)
{
    $date1 = strtotime($date1);
    $date2 = strtotime($date2);
    $seconds_diff = $date1 - $date2;
    return $seconds_diff;
}

function addDayswithdate($date, $days)
{
    $dt = date('Y-m-d', strtotime($date . '+' . $days . ' days'));
    return $dt;
}

function check_if_date_between_current_month($_date1, $_date2)
{
    $_month_1 = (int)date('m', strtotime($_date1));
    $_month_2 = (int)date('m', strtotime($_date2));

    if($_month_1 == $_month_2) {
        $k = '1';
    }
    else {
        $k = '0';
    }

    return $k;
}

/*---------------------------------------------------------------------*/

function encrypt($string)
{
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'RAzYcO0xMP6dAVGrFRc6RyTzaxXWaB4V';
    $secret_iv = '147ED62F47579D9D';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    return base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
}

function decrypt($string)
{
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'RAzYcO0xMP6dAVGrFRc6RyTzaxXWaB4V';
    $secret_iv = '147ED62F47579D9D';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    return openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
}


function isWorkingTime($today, $now)
{
    global $user_id;
    //if($user_id == '145') return true;
    $start_time = "$today 15:00:00";
    $end_time = "$today 17:00:00";
    if(strtotime($now) >= strtotime($start_time) && strtotime($now) <= strtotime($end_time)) {
        return true;
    }
    else {
        return false;
    }
}


function isWorkingDay($dateValue)
{
    //return true;
    $offDay = array();
    $time = strtotime($dateValue);
    $_month = date("F", $time);
    $month = date("m", $time);
    $year = date("Y", $time);

    $second = date('Y-m-d', strtotime("second sat of $_month $year"));
    $fourth = date('Y-m-d', strtotime("fourth sat of $_month $year"));
    //$offDay = array($second,$fourth);

    $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    for($i = 1; $i <= $days; $i++) {
        $day = "$year-$month-$i";
        $result = date("l", strtotime($day));
        if($result == "Sunday") {
            $s = date("Y-m-d", strtotime($day));
            array_push($offDay, $s);
        }
    }
    if(in_array($dateValue, $offDay)) {
        return false;
    }
    else {
        return true;
    }
}

/*-----------------------------------------*/

function curlPost($url, $post)
{
    /* $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);
    curl_close($ch);
    $json_output = json_decode($server_output);
    return $json_output; */
    //return $server_output;
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $post,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $json_output = json_decode($response);
    return $json_output;
}

function _curlPost($url, $post_values)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $post_values,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $json_output = json_decode($response);
    return $json_output;
}

function curlGet($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    /*curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$post);*/

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);
    curl_close($ch);
    $json_output = json_decode($server_output);
    return $json_output;
}

/*---------------------------------------------------------------------*/


function get_ip()
{
    $ip = $_SERVER['REMOTE_ADDR'];
    return $ip;
}

function show_visibility($str)
{
    if($str == 1) {
        $ouput = "<strong style='color:#090;'>On</strong>";
    }
    else {
        $ouput = "<strong style='color:#C00;'>Off</strong>";
    }
    return $ouput;
}

function show_status($status)
{
    if($status == 1) {
        $ouput = '<span class="label label-success">Accepted</span>';
    }
    else if($status == 2) {
        $ouput = '<span class="label label-danger">Rejected</span>';
    }
    else {
        $ouput = '<span class="label label-warning">Pending</span>';
    }
    return $ouput;
}

function img_save($inputName, $width, $height, $imgPath)
{
    //Photo of Applicant
    $image_upload = $_FILES[$inputName]["name"];
    if(($_FILES[$inputName]["type"] == "image/jpeg" || $_FILES[$inputName]["type"] == "image/pjpeg" || $_FILES[$inputName]["type"] == "image/gif" || $_FILES[$inputName]["type"] == "image/x-png" || $_FILES[$inputName]["type"] == "image/png")) {

        // some settings
        $max_upload_width = $width;
        $max_upload_height = $height;
        // if uploaded image was JPG/JPEG
        if($_FILES[$inputName]["type"] == "image/jpeg" || $_FILES[$inputName]["type"] == "image/pjpeg") {
            $image_source = imagecreatefromjpeg($_FILES[$inputName]["tmp_name"]);
        }
        // if uploaded image was GIF
        if($_FILES[$inputName]["type"] == "image/gif") {
            $image_source = imagecreatefromgif($_FILES[$inputName]["tmp_name"]);
        }
        // BMP doesn't seem to be supported so remove it form above image type test (reject bmps)
        // if uploaded image was BMP
        if($_FILES[$inputName]["type"] == "image/bmp") {
            $image_source = imagecreatefromwbmp($_FILES[$inputName]["tmp_name"]);
        }
        // if uploaded image was X-PNG
        if($_FILES[$inputName]["type"] == "image/x-png") {
            $image_source = imagecreatefrompng($_FILES[$inputName]["tmp_name"]);
        }

        // if uploaded image was PNG
        if($_FILES[$inputName]["type"] == "image/png") {
            $image_source = imagecreatefrompng($_FILES[$inputName]["tmp_name"]);
        }

        $img_file = time() . $_FILES[$inputName]["name"];
        $img_file = strtolower(str_replace(" ", "-", $img_file));
        //Move Image to UploadImage Folder
        $remote_file = $imgPath . "/" . $img_file;


        imagejpeg($image_source, $remote_file, 100);
        chmod($remote_file, 0644);

        // get width and height of original image
        list($image_width, $image_height) = getimagesize($remote_file);

        if($image_width > $max_upload_width || $image_height > $max_upload_height) {
            $proportions = $image_width / $image_height;

            if($image_width > $image_height) {
                $new_width = $max_upload_width;
                $new_height = round($max_upload_width / $proportions);
            }
            else {
                $new_height = $max_upload_height;
                $new_width = round($max_upload_height * $proportions);
            }


            $new_image = imagecreatetruecolor($new_width, $new_height);
            $image_source = imagecreatefromjpeg($remote_file);
            //Copy and resize part of an image with resampling
            imagecopyresampled($new_image, $image_source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
            imagejpeg($new_image, $remote_file, 100);

            imagedestroy($new_image);
        }
        imagedestroy($image_source);
        $returnData = array('msg' => 'Success', 'img_name' => $img_file);
        return $returnData;
    }
    else {
        $returnData = array('msg' => 'File format not supported. Please upload an image file.', 'img_name' => '');
        return $returnData;
    }
}

/*-------------------------------------------------*/


function randomCode($length)
{
    $random = "";

    srand((float)microtime() * 1000000);
    $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
    //$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
    $data .= "0FGH45OP89";
    for($i = 0; $i < $length; $i++) {
        $random .= substr($data, (rand() % (strlen($data))), 1);
    }
    return $random;
}

function generatenumber($length, $possible)
{
    $number = "";
    $i = 0;
    while($i < $length) {
        $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
        if(!strstr($number, $char)) {
            $number .= $char;
            $i++;
        }
    }
    return $number;
}

function day($input_date)
{
    $d = date('d', strtotime($input_date));
    return $d;
}

function month($input_date)
{
    $m = date('m', strtotime($input_date));
    return $m;
}

function year($input_date)
{
    $y = date('Y', strtotime($input_date));
    return $y;
}

function db_escape_string($str_value)
{
    return mysql_escape_string($str_value); // server
    //return mysql_real_escape_string($str_value); // local
}

function prevent_injection($con, $value)
{
    return htmlentities(stripslashes(mysqli_real_escape_string($con, $value)), ENT_QUOTES);
}

function salt_1()
{
    $salt_1 = "p8gQ0slX3wc5O1ak";
    return $salt_1;
}

function salt_2()
{
    $salt_2 = "=k7Mp0=j6Gn2aV3O";
    return $salt_2;
}


function hash_encode($param)
{
    $salt_value_1 = salt_1();
    $salt_value_2 = salt_2();
    $hash_value = $salt_value_1 . $param . $salt_value_2;
    $hash_encode_value = base64_encode($hash_value);
    return $hash_encode_value;
}

function hash_decode($val)
{
    $hash_decoded_str = base64_decode($val);
    $val = substr($hash_decoded_str, 16);
    $str = substr($val, 0, -16);
    return $str;
}

function new_code($con, $table, $field, $prefix)
{
    do {
        $mem_no = generatenumber(7, '123456789');
        $mem_code = $prefix . $mem_no;
        $q = mysqli_query($con, "select $field from $table where $field='" . $mem_code . "'");
        $n = mysqli_num_rows($q);
    }
    while($n);
    return $mem_code;
}

function get_new_token($con)
{
    do {
        $token_no = uniqid() . time();
        $q = mysqli_query($con, "select token from member where token='" . $token_no . "'");
        $n = mysqli_num_rows($q);
    }
    while($n);
    return $token_no;
}


function get_balance($con, $table, $column, $id)
{

    $q1 = mysqli_query($con, "select balance from  $table where $column='" . $id . "' order by id desc");
    $n = mysqli_num_rows($q1);
    if($n == 0) {
        $balance = 0;
    }
    else {
        $d1 = mysqli_fetch_array($q1);
        $balance = $d1['balance'];
    }

    return $balance;
}


function member_code($con, $mem_code)
{
    $q = mysqli_query($con, "select *,count(mem_code) as valid_member from member where mem_code='" . $mem_code . "' group by member_id");
    if($q->num_rows > 0) {
        return mysqli_fetch_array($q);
    }
    else {
        return ['valid_member' => 0];
    }

}

function member_id($con, $mem_id)
{
    $q = mysqli_query($con, "select *,count(mem_code) as valid_member from member where member_id='" . $mem_id . "' group by member_id");
    $d = mysqli_fetch_array($q);
    return $d;
}

function member_id_cred($con, $mem_id)
{
    $q = mysqli_query($con, "select *,count(username) as valid_member from member_login where member_id='" . $mem_id . "' group by member_id");
    $d = mysqli_fetch_array($q);
    return $d;
}

function is_blocked($con, $mem_id)
{
    $q = mysqli_query($con, "select member_id from member_login where member_id='" . $mem_id . "' AND status='0'");
    /*var_dump(mysqli_num_rows($q));
    die;*/
    return mysqli_num_rows($q);
}

function joining_package($con, $id)
{
    $q = mysqli_query($con, "select * from join_package where id='" . $id . "'");
    $d = mysqli_fetch_array($q);
    return $d;
}


function m_tree_generate($con, $upliner_code)
{
    if($upliner_code == 'admin') {
        $m_tree = 'admin,';
        return $m_tree;
    }
    else {
        $q = mysqli_query($con, "select * from member where mem_code='" . $upliner_code . "'");
        $r = mysqli_fetch_array($q);
        return $r['m_tree'] . $upliner_code . ',';
    }
}

function intro_mtree_generate($con, $intro_code)
{
    if($intro_code == 'admin') {
        $intro_mtree = 'admin,';
        return $intro_mtree;
    }
    else {
        $q = mysqli_query($con, "select * from member where mem_code='" . $intro_code . "'");
        $r = mysqli_fetch_array($q);
        return $r['intro_mtree'] . $intro_code . ',';
    }
}

function system_encode($str)
{
    $str = "WR" . $str . "DS";
    $str = rtrim(base64_encode($str), '=');
    return $str;
}

function system_decode($str)
{
    $str = base64_decode($str . str_repeat('=', strlen($str) % 4));
    $str = substr($str, 2);
    $str = substr($str, 0, -2);
    return $str;
}

function direct_downlineCount($con, $upliner)
{   //Direct member of intoducer
    $q = mysqli_query($con, "select mem_code from member where intro_code='$upliner'");
    $n = mysqli_num_rows($q);
    return $n;
}

function direct_refaralCount($con, $intro_code)
{   //Direct member of intoducer
    $q = mysqli_query($con, "select mem_code from member where intro_code='$intro_code'");
    $n = mysqli_num_rows($q);
    return $n;
}

function total_downlineCount($con, $upliner)
{    //Total downline count of a particular member
    $q = mysqli_query($con, "select mem_code from member where intro_mtree like '%$upliner%'");
    $n = mysqli_num_rows($q);
    return $n;
}

function member_id_details($con, $member_id)
{
    $q = mysqli_query($con, "select * from member_details where member_id='" . $member_id . "'");
    return mysqli_fetch_array($q);
}

function member_data($con, $parameters, $member_id)
{
    $q = mysqli_query($con, "SELECT $parameters FROM `member` where member_id='" . $member_id . "'");
    $d = mysqli_fetch_assoc($q);
    return $d;
}

function curlPost2($url, $post_values)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $post_values,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $json_output = json_decode($response);
    return $json_output;
}


function get_carry_forward($con, $member_id, $col)
{
    $q_cf = mysqli_query($con, "select $col from binary_match_count where member_id='" . $member_id . "' order by id desc limit 1");
    $d_cf = mysqli_fetch_array($q_cf);
    return $d_cf[$col];
}


function auto_code($con, $table, $length, $prefix)
{
    $q = mysqli_query($con, "SELECT auto_increment  AS AUTO_ID FROM `information_schema`.`tables` WHERE  TABLE_SCHEMA = 'therevamp_db'  and table_name = '" . $table . "'");
    $r = mysqli_fetch_assoc($q);
    $auto_id = $r['AUTO_ID'];
    $auto_code = $prefix . str_pad($auto_id, $length, '0', STR_PAD_LEFT);
    return $auto_code;
}


function get_income_of_member($con, $member_id, $income_type)
{
    $sql = "select ifnull(sum(gross_amount),0) as total_income from income_fund where income_type='" . $income_type . "' and member_id='" . $member_id . "'";
    $q = mysqli_query($con, $sql);
    $r = mysqli_fetch_assoc($q);
    return $r['total_income'];
}

function get_income_of_member_datewise($con, $member_id, $income_type, $start_date, $end_date)
{
    if(!empty($start_date) && !empty($end_date)) {
        $search_with_date_range = " AND (DATE(`created_on`) BETWEEN '" . $start_date . "' AND '" . $end_date . "') ";
    }
    else {
        $search_with_date_range = "";
    }
    $sql = "SELECT ifnull(sum(gross_amount),0) as total_income from income_fund where income_type='" . $income_type . "' and member_id='" . $member_id . "'" . $search_with_date_range;
    $q = mysqli_query($con, $sql);
    $r = mysqli_fetch_assoc($q);
    return $r['total_income'];
}

function get_roi_income_of_member($con, $member_id, $income_type)
{
    $sql = "select ifnull(sum(gross_amount),0) as total_income from income_fund_roi where income_type='" . $income_type . "' and member_id='" . $member_id . "'";
    $q = mysqli_query($con, $sql);
    $r = mysqli_fetch_assoc($q);
    return $r['total_income'];
}


function get_wallet_balance_of_member($con, $member_id, $wallet_table)
{
    $sql = "select balance from $wallet_table where member_id='" . $member_id . "' order by member_id desc limit 1";
    $q = mysqli_query($con, $sql);
    if(mysqli_num_rows($q) == 0) {
        $balance = '0.00';
    }
    else {
        $r = mysqli_fetch_assoc($q);
        $balance = $r['balance'];
    }
    return $balance;
}

function get_last_transaction_wallet_id_of_member($con, $member_id, $wallet_table)
{
    $sql = "select id from $wallet_table where member_id='" . $member_id . "' order by id desc limit 1";
    $q = mysqli_query($con, $sql);
    if(mysqli_num_rows($q) == 0) {
        $id = 0;
    }
    else {
        $r = mysqli_fetch_assoc($q);
        $id = $r['id'];
    }
    return $id;
}


/*function side_checker($con,$up, $side) {
	
    $q=mysqli_query($con,"select count(mem_code) as count_mem,mem_code from member where upliner_code='$up' and side='$side'");
    $d = mysqli_fetch_array($q);
    return $d;
}*/

function spill_upliner($con, $intro, $side)
{    //Spill upliner search of introducer extreme left/right
    $start = $intro;
    $i = 1;
    while($i > 0) {
        $i++;
        //$q = side_checker($con,$start, $side);

        $q = mysqli_query($con, "select * from member where upliner_code='$start' and side='$side'");
        $count_mem = mysqli_num_rows($q);

        //$count_mem = $q['count_mem'];
        //$mem_code = $q['mem_code'];

        if($count_mem == 0) {
            return $start;
            break;
        }
        else {
            $d = mysqli_fetch_array($q);
            $mem_code = $d['mem_code'];

            $swap = $mem_code;
            $start = $swap;
        }
    }
}

function get_voucher_no($con, $table = 'voucher', $prefix = 'VOU')
{
    $q = mysqli_query($con, "select id from " . $table . " order by id desc limit 1");
    if(mysqli_num_rows($q) == 0) {
        $num = 1;
    }
    else {
        $r = mysqli_fetch_assoc($q);
        $num = $r['id'] + 1;
    }
    $uniq_code = str_pad($num, 4, '0', STR_PAD_LEFT);
    $voucher_no = $prefix . $uniq_code;

    return $voucher_no;
}

function new_voucher_no($con)
{
    $q = mysqli_query($con, "select id from voucher order by id desc limit 1");
    if(mysqli_num_rows($q) == 0) {
        $num = 1;
    }
    else {
        $r = mysqli_fetch_assoc($q);
        $num = $r['id'] + 1;
    }
    $uniq_code = str_pad($num, 4, '0', STR_PAD_LEFT);
    $voucher_no = 'VBST' . $uniq_code;

    return $voucher_no;
}

function new_voucher_no_roi($con)
{
    $q = mysqli_query($con, "select id from voucher_roi order by id desc limit 1");
    if(mysqli_num_rows($q) == 0) {
        $num = 1;
    }
    else {
        $r = mysqli_fetch_assoc($q);
        $num = $r['id'] + 1;
    }
    $uniq_code = str_pad($num, 4, '0', STR_PAD_LEFT);
    $voucher_no = 'VPRR' . $uniq_code;

    return $voucher_no;
}

function current_package_name_of_member($con, $user_id, $table)
{
    $sql_pack = "select p.package_name, m.created_on from $table m inner join join_package p on m.package_id=p.id where m.member_id='" . $user_id . "' ORDER BY m.id desc limit 1";
    $q_p = mysqli_query($con, $sql_pack);
    $r_p = mysqli_fetch_assoc($q_p);

    return $r_p['package_name'] . '<br>' . dmy($r_p['created_on']);
}

function member_package_own_amount($con, $user_id, $table)
{
    $sql_pack = "select ifnull(sum(amount),0) AS total_amount from $table where member_id='" . $user_id . "'";
    $q_p = mysqli_query($con, $sql_pack);
    $r_p = mysqli_fetch_assoc($q_p);
    $update_amount = $r_p['total_amount'];

    return $update_amount;
}

/*function member_business_side_by_date($con, $up, $side, $start_dt, $end_dt, $table)
{
	$sql = mysqli_query($con, "select mem_code,member_id from member where upliner_code='$up' and side='$side'");
	$n = mysqli_num_rows($sql);
	if ($n > 0) {
		$d = mysqli_fetch_array($sql);
		$left_right_member_code = $d['mem_code'];
		$left_right_member_id = $d['member_id'];

		$total_business_amt_of_member = 0;
		$sql1 = mysqli_query($con, "SELECT member_id from member where m_tree like '%$left_right_member_code%'");
		while ($arr1 = mysqli_fetch_array($sql1)) {
			$member_id = $arr1['member_id'];
			$total_business_amt_of_member = $total_business_amt_of_member +  get_total_investment_of_member_by_date($con, $member_id, $start_dt, $end_dt, $table);
		}

		$total_business_amt_of_self =  get_total_investment_of_member_by_date($con, $left_right_member_id, $start_dt, $end_dt, $table);
		$grand_business_total = $total_business_amt_of_member + $total_business_amt_of_self + get_topup_power($con, $up, $side, $start_dt, $end_dt);

		return $grand_business_total;
	} else {
		return '0';
	}
}*/

function get_total_investment_of_member_by_date($con, $member_id, $start_dt, $end_dt, $table)
{
    $sql = "select ifnull(sum(amount),0) as total_investment from $table where member_id='" . $member_id . "' and (DATE(created_on) between '" . $start_dt . "' and '" . $end_dt . "')";
    $q = mysqli_query($con, $sql);
    $r = mysqli_fetch_assoc($q);
    return $r['total_investment'];
}

function get_self_investment_of_member_by_date($con, $member_id, $start_dt, $end_dt, $table)
{
    $sql = "select ifnull(sum(amount),0) as total_investment from $table where member_id='" . $member_id . "' and (DATE(created_on) between '" . $start_dt . "' and '" . $end_dt . "')";
    $q = mysqli_query($con, $sql);
    $r = mysqli_fetch_assoc($q);
    return $r['total_investment'];
}

/*function member_business_side($con, $up, $side, $table) 
{
	$now = now();
	$sql = mysqli_query($con, "select mem_code,member_id from member where upliner_code='$up' and side='$side'");
	$n = mysqli_num_rows($sql);
	if ($n > 0) {
		$d = mysqli_fetch_array($sql);
		$left_right_member_code = $d['mem_code'];
		$left_right_member_id = $d['member_id'];

		$total_business_amt_of_member = 0;
		$sql1 = mysqli_query($con, "SELECT member_id from member where m_tree like '%$left_right_member_code%'");
		while ($arr1 = mysqli_fetch_array($sql1)) {
			$member_id = $arr1['member_id'];
			$total_business_amt_of_member = $total_business_amt_of_member +  get_self_investment_of_member($con, $member_id, $table);
		}

		$total_business_amt_of_self =  get_self_investment_of_member($con, $left_right_member_id, $table);
		$grand_business_total = $total_business_amt_of_member + $total_business_amt_of_self;

		return $grand_business_total;
	} else {
		return '0';
	}
}*/

function get_self_investment_of_member($con, $member_id, $table)
{
    $sql = "select ifnull(sum(amount),0) as total_investment from $table where member_id='" . $member_id . "'";
    $q = mysqli_query($con, $sql);
    $r = mysqli_fetch_assoc($q);
    return $r['total_investment'];
}

/*function downline_left_right_count($con, $up, $side)
{
	$sql = "select mem_code from member where upliner_code='$up' and side='$side'";
	$q = mysqli_query($con, $sql);
	$n = mysqli_num_rows($q);
	if ($n > 0) {
		$d = mysqli_fetch_assoc($q);
		$left_right_member = $d['mem_code'];

		$sql1 = "select mem_code from member where m_tree like '%$left_right_member%' ";
		$q1 = mysqli_query($con, $sql1);

		$left_right_downline_count = mysqli_num_rows($q1);
		$left_right_downline_count = $left_right_downline_count + 1;

		return $left_right_downline_count;
	} else {
		return 0;
	}
}


function downline_left_right_active_count($con, $up, $side)
{
	$sql = "select member_id,mem_code from member where upliner_code='$up' and side='$side'";
	$q = mysqli_query($con, $sql);
	$n = mysqli_num_rows($q);
	if ($n > 0) {
		$d = mysqli_fetch_assoc($q);
		$left_right_member_id = $d['member_id'];
		$left_right_member_code = $d['mem_code'];

		$active_amount = member_is_active($con, $left_right_member_id);
		if ($active_amount > 0) {
			$self_active_count = 1;
		} else {
			$self_active_count = 0;
		}

		$lr_active_count = 0;
		$sql1 = mysqli_query($con, "SELECT member_id from member where m_tree like '%$left_right_member_code%'");
		while ($arr1 = mysqli_fetch_array($sql1)) {
			$lr_member_id = $arr1['member_id'];
			$lr_active_amount = member_is_active($con, $lr_member_id);
			if ($lr_active_amount > 0) {
				$lr_active_count = $lr_active_count + 1;
			}
		}

		$total_active_count = $self_active_count + $lr_active_count;

		return $total_active_count;
	} else {
		return 0;
	}
}*/

function active_direct_count($con, $member_id)
{
    $sql = "select COUNT(distinct(m1.member_id)) as direct_count from member_package_update_log m1 inner join member m2 on m1.member_id=m2.member_id where m2.intro_id='" . $member_id . "'";
    $q = mysqli_query($con, $sql);
    $r = mysqli_fetch_assoc($q);
    return $r['direct_count'];
}

function active_team_count($con, $mem_code)
{    //Total downline count of a particular member
    $q = mysqli_query($con, "select COUNT(m1.member_id) as team_count from member_package_update m1 inner join member m2 on m1.member_id=m2.member_id where m2.intro_mtree like '%$mem_code%'");
    $r = mysqli_fetch_assoc($q);
    return $r['team_count'];
}

function downline_member_business($con, $mem_code, $table)
{
    $total_business_amt_of_member = 0;
    $sql1 = mysqli_query($con, "SELECT member_id from member where intro_mtree like '%$mem_code%'");
    while($arr1 = mysqli_fetch_array($sql1)) {
        $member_id = $arr1['member_id'];
        $total_business_amt_of_member = $total_business_amt_of_member + get_self_investment_of_member($con, $member_id, $table);
    }

    $total_business_amt_of_self = get_self_investment_of_member($con, $mem_code, $table);
    $grand_business_total = $total_business_amt_of_member + $total_business_amt_of_self;

    return $grand_business_total;
}


function team_member_business($con, $mem_code, $table)
{
    $q = mysqli_query($con, "SELECT IFNULL(SUM(b.amount),0) as amount FROM member AS a, $table AS b WHERE a.member_id = b.member_id AND a.intro_mtree like '%$mem_code%'");
    $r = mysqli_fetch_assoc($q);
    $total_business_amt_of_member = $r['amount'];

    return $total_business_amt_of_member;
}

function direct_member_business($con, $member_id, $table)
{
    $sql = "select ifnull(sum(amount),0) as total_investment from $table where intro_id='" . $member_id . "'";
    $q = mysqli_query($con, $sql);
    $r = mysqli_fetch_assoc($q);
    return $r['total_investment'];
}

function active_inactive_label_show($con, $member_id)
{
    $is_active = member_is_active($con, $member_id);
    if($is_active > 0) {
        return "<span class='badge badge-success'>Active</span>";
    }
    else {
        return "<span class='badge badge-danger'>Inactive</span>";
    }
}

function no_of_account_by_pan($con, $pan)
{
    $q_pan = mysqli_query($con, "select member_id from member_details where pan_no='" . $pan . "'");
    $n_pan = mysqli_num_rows($q_pan);
    return $n_pan;
}

function is_valid_pan_format($value)
{
    // $value = "PAN Card NUMBER"; //PUT YOUR PAN CARD NUMBER HERE
    $pattern = '/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/';
    $result = preg_match($pattern, $value);
    if($result) {
        $findme = ucfirst(substr($value, 3, 1));
        $mystring = 'CPHFATBLJG';
        $pos = strpos($mystring, $findme);
        if($pos === false) {
            $msg = FALSE; // "Not valid";
        }
        else {
            $msg = TRUE; // "Valid";
        }
    }
    else {
        $msg = FALSE; // "Not Valid";
    }
    return $msg;
}

/*------------------- FOR FRANCHAISEE ----------------------------*/


function member_is_registered($con, $member_id)
{
    $sql_pack = "select COUNT(1) AS is_active from member_reg_update_log where member_id='" . $member_id . "'";
    $q_p = mysqli_query($con, $sql_pack);
    $dp = mysqli_fetch_assoc($q_p);
    $is_active = $dp['is_active'];

    if($is_active >= 1) {
        return 1;
    }
    else {
        return 0;
    }
}

function member_is_active($con, $member_id)
{
    $sql_pack = "select COUNT(1) AS is_active from member_package_update_log where member_id='" . $member_id . "'";
    $q_p = mysqli_query($con, $sql_pack);
    $dp = mysqli_fetch_assoc($q_p);
    $is_active = $dp['is_active'];

    if($is_active >= 1) {
        return 1;
    }
    else {
        return 0;
    }
}


function is_missing_direct($con, $user_id)
{
    $redeem_elligibility = true;
    $sql_roi = "SELECT MAX(direct) AS max_direct FROM roi_eligibility WHERE member_id = '" . $user_id . "'";

    $result_roi = $con->query($sql_roi);
    if($result_roi->num_rows > 0) {
        $row_roi = $result_roi->fetch_assoc();
        $direct_in_roi_table = $row_roi["max_direct"];

        $sql_mem = "SELECT COUNT(member_id) AS direct_count FROM member WHERE intro_id = '" . $user_id . "'";
        $result_mem = $con->query($sql_mem);
        if($result_mem->num_rows > 0) {
            $row_mem = $result_mem->fetch_assoc();
            $direct_in_member_table = $row_mem["direct_count"];
            if($direct_in_member_table >= $direct_in_roi_table) {
                $redeem_elligibility = false;
            }
        }
    }
    //echo $direct_in_member_table;
    //echo "----";
    //echo $direct_in_roi_table;

    return $redeem_elligibility;
}

function is_pan_verified($con, $member_id)
{
    $sql = "SELECT member_id FROM member_details WHERE member_id = '" . $member_id . "' AND pan_verified = '1'";
    $res = $con->query($sql);
    if($res->num_rows > 0) {
        return TRUE;
    }
    else {
        return TRUE;
    }
}

function get_balance_fast($con, $table, $id)
{
    $q1 = mysqli_query($con, "select balance from  $table where member_id='" . $id . "'");
    $n = mysqli_num_rows($q1);
    if($n == 0) {
        $balance = 0;
    }
    else {
        $d1 = mysqli_fetch_array($q1);
        $balance = $d1['balance'];
    }

    return $balance;
}

function ismtblock($con, $mem_code)
{
    $q = mysqli_query($con, "select mem_code from member_money_transfer_block where mem_code='$mem_code'");
    $n = mysqli_num_rows($q);
    return $n;
}


function pin_generator($length, $possible)
{
    $r1 = trim(generatenumber($length, $possible));
    $r2 = trim(generatenumber($length, $possible));
    $r3 = trim(generatenumber($length, $possible));

    $pin_no = "$r1$r2$r3";

    return $pin_no;
}

/*-----------------  pinTransfer  -----------------------------------*/

function pinTransfer($from_member, $from_member_id, $to_member, $to_member_id, $pin_details, $action_by, $now, $con)
{
    $return_id = 0;
    $relation = 1;
    if($relation == 1) {
        mysqli_autocommit($con, FALSE);
        $arr_count = count($pin_details);

        if($arr_count >= 1) {
            $j = 0;
            for($i = 0; $i < $arr_count; $i++) {
                $pin_id = $pin_details[$i];

                $q = "select * from pin where id='" . $pin_id . "' and status='Active' and hold_by='" . $action_by . "'";
                $sql = mysqli_query($con, $q);
                $n = mysqli_num_rows($sql);

                if($n == 1) {
                    $rec = mysqli_fetch_assoc($sql);
                    $pdt_id = $rec['pin_type_id'];
                    $pin_no = $rec['pin_no'];
                    $status = $rec['status'];

                    $q1 = "update pin set hold_by='" . $to_member . "', hold_date='" . $now . "', updated_by='" . $action_by . "' where id='" . $pin_id . "'";
                    $sql1 = mysqli_query($con, $q1);

                    if($sql1 === TRUE) {
                        $sql2 = "insert into pin_transfer(pin_id, pdt_id, status, from_id, to_id, created_by, created_on) values('" . $pin_id . "','" . $pdt_id . "','" . $status . "','" . $action_by . "','" . $to_member . "','" . $action_by . "','" . $now . "')";
                        $q2 = mysqli_query($con, $sql2);

                        $q3 = mysqli_query($con, "INSERT INTO `all_pin_history`(`pin_no`, `pdt_id`, `particulars`, `status`, `from_id`, to_id, `created_by`, `created_on`)
						VALUES('" . $pin_no . "','" . $pdt_id . "','PIN_TRANSFER','" . $status . "','" . $from_member . "','" . $to_member . "','" . $action_by . "','" . $now . "')");

                        if($q2 === TRUE && $q3 === TRUE) {
                            $j = $j + 1;
                        }
                    }
                    else {
                        break;
                    }
                }
                else {
                    break;
                }
            }

            if($j == $arr_count) {
                mysqli_commit($con);
                $return_id = 1; //success
            }
            else {
                mysqli_rollback($con);
                $return_id = 0; //temporary problem
            }
        }
        else {
            $return_id = 2; //enter at least one pin to transfer
        }
    }
    else {
        $return_id = 3; //member relation break
    }

    return $return_id;
}


function is_downline($con, $upline_code, $downline_code)
{
    $sql_mm = "select member_id,name,mobile from member where mem_code='" . $downline_code . "' and intro_mtree like '%," . $upline_code . "%'";
    $q_mm = mysqli_query($con, $sql_mm);
    $valid_member = mysqli_num_rows($q_mm);
    //var_dump($valid_member);
    if($valid_member == 0) {
        return false;
    }
    else {
        return true;
    }
}

function generate_new_invoice_no($con)
{
    $q = mysqli_query($con, "select id from member_package_update_log order by id desc limit 1");
    $n = mysqli_num_rows($q);
    if($n == 0) {
        $invoice_no = 'GCD-00001';
    }
    else {
        $idd = $r['id'] + 1;
        $invoice_no = 'GCD-' . str_pad($idd, 5, '0', STR_PAD_LEFT);
    }
    return $invoice_no;
}


function current_package_id_of_member($con, $user_id, $table)
{
    $sql_pack = "select p.id from $table m inner join join_package p on m.package_id=p.id where m.member_id='" . $user_id . "' ORDER BY m.id desc limit 1";
    $q_p = mysqli_query($con, $sql_pack);
    $r_p = mysqli_fetch_assoc($q_p);

    return $r_p['id'];
}


/*function get_topup_power($con, $member_code, $side, $start_dt, $end_dt)
{
	$sql = "SELECT IFNULL(SUM(a.amount),0) AS var_power_amount FROM topup_power a inner join member b on a.member_id=b.member_id WHERE b.mem_code='" . $member_code . "' AND a.side='" . $side . "' AND (DATE(a.created_on) between '$start_dt' and '$end_dt')";
	$q = mysqli_query($con, $sql);
	$n = mysqli_num_rows($q);
	if ($n == 0) {
		return 0;
	} else {
		$r = mysqli_fetch_assoc($q);
		return $r['var_power_amount'];
	}
}*/

function current_package_date_of_member($con, $user_id)
{
    $sql_pack = "select COUNT(1) AS is_active, created_on from member_package_update_log where member_id='" . $user_id . "' ORDER BY id asc limit 1";
    $q_p = mysqli_query($con, $sql_pack);
    $dp = mysqli_fetch_assoc($q_p);
    $is_active = $dp['is_active'];

    if($is_active > 0) {
        return $dp['created_on'];
    }
    else {
        return '-';
    }
}

function getMfAmount($con, $amount)
{
    $q = mysqli_query($con, "SELECT rate from mf_rate where id=1");
    if(mysqli_num_rows($q)) {
        $data = mysqli_fetch_assoc($q);
        return $amount / $data['rate'];
    }
    return 0;
}

function getMfRate($con)
{
    $q = mysqli_query($con, "SELECT rate from mf_rate where id=1");
    if(mysqli_num_rows($q)) {
        $data = mysqli_fetch_assoc($q);
        return $data['rate'];
    }
    return 0;
}


function get_business_ratio($con, $member_id)
{
    $sql = "SELECT *
	FROM member_tree AS a, member_package_update_log AS b 
    WHERE a.member_id = b.member_id
    AND a.intro_id = '$member_id'";

    $q = mysqli_query($con, $sql);

    if(mysqli_num_rows($q)) {

        $sql_1 = "SELECT a.member_id AS var_mem_id, b.business AS var_downline_business1
                FROM member AS a, member_downline_business AS b 
                WHERE a.member_id = b.member_id AND a.intro_id = '$member_id' ORDER BY b.business DESC LIMIT 1;";

        $q_1 = mysqli_query($con, $sql_1);

        if(mysqli_num_rows($q_1)) {
            $d_1 = mysqli_fetch_array($q_1);
            $_var_member_id = $d_1['var_mem_id'];
            $_var_downline_business1 = $d_1['var_downline_business1'];

            $sql_2 = "SELECT IFNULL(SUM(b.business),0) AS var_downline_business2
                    FROM member AS a, member_downline_business AS b 
                    WHERE a.member_id = b.member_id AND a.intro_id = '$member_id' AND a.member_id != '$_var_member_id';";

            $q_2 = mysqli_query($con, $sql_2);
            $d_2 = mysqli_fetch_array($q_2);
            $_var_downline_business2 = $d_2['var_downline_business2'];
        }
        else {
            $_var_downline_business1 = 0;
            $_var_downline_business2 = 0;
        }
    }
    else {
        $_var_downline_business1 = 0;
        $_var_downline_business2 = 0;
    }

    if($_var_downline_business1 < $_var_downline_business2) {
        $cal_type = '60 : 40';
    }
    else if($_var_downline_business1 == $_var_downline_business2) {
        $cal_type = '50 : 50';
    }
    else {
        $cal_type = '40 : 60';
    }

    $_ratio = array('max_value' => $_var_downline_business1, 'min_value' => $_var_downline_business2, 'cal_type' => $cal_type);

    return $_ratio;
}


function get_rank($con, $member_id)
{
    $sql_2 = "select `rank` from salary_eligibility WHERE member_id = '$member_id'";
    $q_2 = mysqli_query($con, $sql_2);
    if(mysqli_num_rows($q_2)) {
        $d_2 = mysqli_fetch_array($q_2);
        return $d_2['rank'];
    }
    else {
        return 'Not yet achieved.';
    }
}

function get_reward($con, $member_id)
{
    $sql_2 = "select reward from reward_achievement WHERE member_id = '$member_id'";
    $q_2 = mysqli_query($con, $sql_2);
    if(mysqli_num_rows($q_2)) {
        $d_2 = mysqli_fetch_array($q_2);
        return $d_2['reward'];
    }
    else {
        return 'Not yet achieved.';
    }
}


function get_rank_count($con, $member_id, $rank_id)
{
    $sql_2 = "SELECT GET_RANK_COUNT('$member_id','$rank_id') AS rank_count";
    $q_2 = mysqli_query($con, $sql_2);
    $d_2 = mysqli_fetch_array($q_2);
    return $d_2['rank_count'];
}


// KYC UPLOAD ON AWS 

if(!function_exists('get_space_url')) {
    function get_space_url($file_path, $keyPrefix = "")
    {
        global $space;

        if(filter_var($file_path, FILTER_VALIDATE_URL)) {
            try {
                if($space->DoesObjectExist($file_path)) {
                    $space->MakePublic($file_path);
                }
            }
            catch(Exception $exception) {
                //        print_r($exception);
            }
        }
        else {
            $file_path_clean = $keyPrefix . clean_file_name($file_path);
            $file_path = $keyPrefix . $file_path;
            try {
                if($space->DoesObjectExist($file_path)) {
                    $space->MakePublic($file_path);
                    $file_path = 'https://' . SPACE_SPACENAME . '.' . SPACE_REGION . '.digitaloceanspaces.com/' . $file_path;
                }
                elseif($space->DoesObjectExist($file_path_clean)) {
                    $space->MakePublic($file_path_clean);
                    $file_path = 'https://' . SPACE_SPACENAME . '.' . SPACE_REGION . '.digitaloceanspaces.com/' . $file_path_clean;
                }
                else {
                    $file_path = 'https://a1globaltrade.com/aone-login/assets/images/' . $file_path;
                }
            }
            catch(Exception $exception) {
                //        print_r($exception);
            }
        }
        return $file_path;
    }
}


if(!function_exists('clean_file_name')) {
    function clean_file_name($file_name)
    {
        $file_name = strtolower(str_replace(" ", "-", $file_name));
        $file_name_arr = explode('.', $file_name);
        $file_name_extention = array_pop($file_name_arr);
        $file_name_name = implode('', $file_name_arr);
        // $file_name_name = preg_replace('/[^A-Za-z0-9\-]/', '', $file_name_name);
        $file_name_name = preg_replace('/[^A-Za-z0-9]/', '', $file_name_name);
        $file_name_name = (strlen($file_name_name) > 100) ? (uniqid() . substr($file_name_name, 0, 50)) : $file_name_name;
        $file_name = $file_name_name . "." . $file_name_extention;
        return $file_name;
    }
}


$phpFileUploadErrors = array(
    0 => 'There is no error, the file uploaded with success',
    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
    3 => 'The uploaded file was only partially uploaded',
    4 => 'No file was uploaded',
    6 => 'Missing a temporary folder',
    7 => 'Failed to write file to disk.',
    8 => 'A PHP extension stopped the file upload.',
);

function compression_quality($file)
{
    $size = (int)$file['size']; // bytes
    $size_kb = $size / 1024; // KB

    $compression_quality = (1024 * 2) / $size_kb;

    if($compression_quality > 100) {
        $compression_quality = 100;
    }

    return ceil($compression_quality);
}

if(!function_exists('mime_content_type')) {
    function mime_content_type($filename)
    {
        $filename = escapeshellcmd($filename);
        $command = "file -b --mime-type -m /usr/share/misc/magic {$filename}";

        $mimeType = shell_exec($command);

        return trim($mimeType);
    }
    /*function mime_content_type($filename) {
        $result = new finfo();

        if (is_resource($result) === true) {
            return $result->file($filename, FILEINFO_MIME_TYPE);
        }

        return false;
    }
    /*
    function mime_content_type( $filename , $mode=0) {
        // mode 0 = full check
        // mode 1 = extension check only

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'docx' => 'application/msword',
            'xlsx' => 'application/vnd.ms-excel',
            'pptx' => 'application/vnd.ms-powerpoint',


            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $array = explode(".", $filename);
        $ext = strtolower(array_pop($array));

        echo $ext;
        die;

        if(function_exists('finfo_open')&&$mode==0){
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }elseif(array_key_exists($ext, $mime_types)){
            return $mime_types[$ext];
        }else {
            return 'application/octet-stream';
        }
    }
*/
}

function webpConvert2_mm($file, $compression_quality = 10)
{
    // check if file exists
    if(!file_exists($file)) {
        return false;
    }

    $mime_type = mime_content_type($file);

    $current_extention = pathinfo($file, PATHINFO_EXTENSION);

    $output_file = str_ireplace($current_extention, 'webp', $file);


    if(file_exists($output_file)) {
        return $output_file;
    }
    if(function_exists('imagewebp')) {
        switch($mime_type) {
            case 'image/gif':
                $image = imagecreatefromgif($file);
                break;
            case 'image/pjpeg':
            case 'image/jpeg':
                $image = imagecreatefromjpeg($file);
                break;
            case 'image/png':
            case 'image/x-png':
                $image = imagecreatefrompng($file);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            case 'image/bmp': // IMAGETYPE_BMP
                $image = imagecreatefrombmp($file);
                break;
            case 'image/webp': //IMAGETYPE_Webp
                return false;
                break;
            case 'image/xbm': //IMAGETYPE_XBM
                $image = imagecreatefromxbm($file);
                break;
            default:
                return false;
        }
        // Save the image
        $result = imagewebp($image, $output_file, $compression_quality);
        if(false === $result) {
            return false;
        }
        // Free up memory
        imagedestroy($image);
        return $output_file;
    }
    elseif(class_exists('Imagick')) {
        $image = new Imagick();
        $image->readImage($file);
        if($mime_type === "image/x-png") {
            $image->setImageFormat('webp');
            $image->setImageCompressionQuality($compression_quality);
            $image->setOption('webp:lossless', 'true');
        }
        $image->writeImage($output_file);
        return $output_file;
    }
    return false;
}


if(!function_exists('space_url_to_path')) {
    function space_url_to_path($url)
    {
        if(filter_var($url, FILTER_VALIDATE_URL)) {
            $path_domain = 'https://' . SPACE_SPACENAME . '.' . SPACE_REGION . '.digitaloceanspaces.com/';
            return str_replace($path_domain, '', $url);
        }
        else {
            return false;
        }
    }
}
