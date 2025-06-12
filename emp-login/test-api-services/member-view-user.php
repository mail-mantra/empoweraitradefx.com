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
    $mem_code = $_POST['mem_code'];
    
    
  
    $result=array();
    
    
    
    
    
    $sql = "select m1.*,m2.*,m3.state,m3.city,m3.pin, pu1.created_on AS member_package_update_on
			from member m1 
			inner join member_login m2 on m1.member_id=m2.member_id 
			inner join member_details m3 on m1.member_id=m3.member_id 
			LEFT JOIN `member_package_update` AS pu1 on m1.member_id= pu1.member_id 
			where m1.intro_mtree like '%$mem_code%'";
  
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


