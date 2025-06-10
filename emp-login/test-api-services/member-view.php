<?php
header('Content-Type: application/json');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();
$test_api_key = '420c8166803c9ee85629ad505bb0be38';

$response = array('status'=>0, 'msg'=>'Invalid Request', 'result'=>'');

if($test_api_key==$_POST['api_key']){
    $low_limit = $_POST['start'];
    $up_limit = $_POST['end'];
    
    
    if(isset($_POST['member_id'])){
        $member_id = $_POST['member_id'];
        $mem_sql = " and m1.member_id='".$member_id."'";
        $low_limit=0;
        $up_limit=1;
    }else{
        $mem_sql = '';
    }
 
  
    $result=array();
    
    
    
    
    
    $sql = "SELECT m1.*, m2.*, m3.state, m3.city, m3.pin, m3.pan_no, 
            TOTAL_SELF_TOPUP(m1.member_id) AS total_topup_amount 
            FROM member m1 
            INNER JOIN member_login m2 ON m1.member_id = m2.member_id
            INNER JOIN member_details m3 ON m1.member_id = m3.member_id where 1 $mem_sql
            ORDER BY m1.member_id DESC";
  
    $con = $db->connect();
    $q1 = mysqli_query($con, $sql);
    $db->dbDisconnet($con);
    $total=mysqli_num_rows($q1);

    if ($q1 &&  $total > 0) {
        $sql2= "$sql limit $low_limit,$up_limit";
       
        $con = $db->connect();
        $q2 = mysqli_query($con, $sql2);
        $db->dbDisconnet($con);
        while($r2 = mysqli_fetch_assoc($q2)){
        $result[] = [
                    'member_id' => $r2['member_id'],
                    'mem_code' => $r2['mem_code'],
                    'name' => $r2['name'],
                    'mobile' => $r2['mobile'],
                    'email' => $r2['email'],
                    'password' => $r2['password'],
                    'intro_code' => $r2['intro_code'],
                    'doj' => $r2['doj'],
                    'member_status' => $r2['status']
                ];
        }
        $response = array('status'=>1, 'msg'=>'Records Found', 'total_records'=>$total, 'result'=>$result);
       
    } else {
        $response = array('status'=>0, 'msg'=>'No Records Found', 'result'=>'');
    }
}else{
    $response = array('status'=>0, 'msg'=>'Invalid API Key', 'result'=>'');
}
echo json_encode($response,JSON_PRETTY_PRINT); 
?>


