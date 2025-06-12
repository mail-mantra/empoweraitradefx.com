<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
include('../class/DbClass.php');
include('../lib/my_function.php');
include('../lib/smtp_function.php');
$db = new Database();
$test_api_key = '420c8166803c9ee85629ad505bb0be38';

$response = array('status' => 0, 'msg' => 'Invalid Request', 'result' => '');

if(isset($_POST['api_key']) && $test_api_key == $_POST['api_key']) {
    $post_data = post_data();

    $offset = isset($post_data['offset']) ? (int)$post_data['offset'] : 0;
    $limit = isset($post_data['limit']) ? (int)$post_data['limit'] : 5;


    $con = $db->connect();
    try {

        $user_code = $post_data['user_code'] ?? '';
        if(!$user_code) {
            throw new Exception('User Code Required');
        }

        $mem_code = $post_data['mem_code'] ?? '';
        $name = $post_data['name'] ?? '';
        $mobile = $post_data['mobile'] ?? '';
        $email = $post_data['email'] ?? '';
        $doj_start = $post_data['doj_start'] ?? '';
        $doj_end = $post_data['doj_end'] ?? '';


        // Prepare WHERE conditions
        $where = "WHERE 1";
        $params = [];
        $param_types = '';

        if ($user_code !== 'admin') {
            $where .= " AND m1.intro_mtree LIKE ?";
            $params[] = "%$user_code%";
            $param_types .= 's';
        }

        if (!empty($mobile)) {
            $where .= " AND m1.mobile = ?";
            $params[] = strval($mobile);
            $param_types .= 's';
        }

        if (!empty($email)) {
            $where .= " AND m1.email = ?";
            $params[] = $email;
            $param_types .= 's';
        }

        if (!empty($name)) {
            $where .= " AND m1.name LIKE ?";
            $params[] = "%$name%";
            $param_types .= 's';
        }

        if (!empty($doj_start) && !empty($doj_end)) {
            $where .= " AND m1.doj BETWEEN ? AND ?";
            $params[] = $doj_start;
            $params[] = $doj_end;
            $param_types .= 'ss';
        }

        // 1. Total records count (no limit)

        $total_sql = "SELECT COUNT(*) as total FROM `member` m1 $where";
        $total_stmt = $con->prepare($total_sql);
        if (!empty($param_types)) {
            $total_stmt->bind_param($param_types, ...$params);
        }
        $total_stmt->execute();
        $total_result = $total_stmt->get_result();
        $total_row = $total_result->fetch_assoc();
        $total_records = $total_row['total'] ?? 0;
        $total_stmt->close();

        // 2. Fetch paginated records
        $sql = "select m1.*,m2.*,m3.state,m3.city,m3.pin, pu1.created_on AS activation_date
			from member m1 
			inner join member_login m2 on m1.member_id=m2.member_id 
			inner join member_details m3 on m1.member_id=m3.member_id 
			LEFT JOIN `member_package_update` AS pu1 on m1.member_id= pu1.member_id 
			$where 
			ORDER BY m1.member_id DESC
                LIMIT ?, ?";

        $stmt = $con->prepare($sql);

        // Add offset and limit
        $params[] = $offset;
        $params[] = $limit;
        $param_types .= 'ii';

        $stmt->bind_param($param_types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        $response_data = [];

        while($row = $result->fetch_assoc()) {

            $response_data[] = [
                'member_id' => $row['member_id'],
                'mem_code' => $row['mem_code'],
                'name' => $row['name'],
                'mobile' => $row['mobile'],
                'email' => $row['email'],
                'password' => $row['password'],
                'intro_code' => $row['intro_code'],
                'doj' => $row['doj'],
                'member_status' => $row['status'],
                'is_active_member' => $row['activation_date'] ? 1 : 0,
                'activation_date' => $row['activation_date'] ? dmy_time($row['activation_date']) : '',
            ];
        }

        // 5. has_more logic
        $current_count = count($response_data);
        $has_more = ($offset + $current_count) < $total_records;

        // 6. Final response
        $response = [
            'status' => 1,
            'msg' => 'success',
            'total_records' => $total_records,
            'current_count' => $current_count,
            'has_more' => $has_more,
            'result' => $response_data
        ];


    }
    catch(Exception $exception) {
        $response['msg'] = $exception->getMessage();
    }
    finally {
        $con->close();
    }

}
else {
    $response = array('status' => 0, 'msg' => 'Invalid API Key', 'result' => '');
}
echo json_encode($response, JSON_PRETTY_PRINT);



