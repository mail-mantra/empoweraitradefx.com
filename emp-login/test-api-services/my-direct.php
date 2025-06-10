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
        $user_id = $post_data['user_id'] ?? '';
        if(!$user_id) {
            throw new Exception('User Id Required');
        }

        // 1. Total records count (no limit)
        $total_stmt = $con->prepare("SELECT COUNT(*) as total FROM member WHERE intro_id = ?");
        $total_stmt->bind_param("i", $user_id);
        $total_stmt->execute();
        $total_result = $total_stmt->get_result();
        $total_row = $total_result->fetch_assoc();
        $total_records = $total_row['total'] ?? 0;
        $total_stmt->close();

        // 2. Fetch paginated records
        $sql = "SELECT m1.*, IS_ACTIVE_MEMBER(m1.member_id) as is_active_member, INTROWISE_SELF_BUSINESS(m1.member_id, '2020-01-01', NOW()) AS total_business
                FROM member m1
                WHERE m1.intro_id = ?
                ORDER BY m1.member_id DESC
                LIMIT ?, ?";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("iii", $user_id, $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        $response_data = [];

        while($row = $result->fetch_assoc()) {
            $member_id = $row['member_id'];

            // 3. Get investment logs for the member
            $inv_stmt = $con->prepare("SELECT amount, created_on FROM member_package_update_log WHERE member_id = ?");
            $inv_stmt->bind_param("i", $member_id);
            $inv_stmt->execute();
            $inv_result = $inv_stmt->get_result();

            $investments = [];
            while($inv_row = $inv_result->fetch_assoc()) {
                $investments[] = [
                    'amount' => $inv_row['amount'],
                    'created_on' => dmy_time($inv_row['created_on'])
                ];
            }
            $inv_stmt->close();

            // 4. Append member data with investments
            $response_data[] = [
                'member_id' => $row['member_id'],
                'mem_code' => $row['mem_code'],
                'name' => $row['name'],
                'mobile' => $row['mobile'],
                'email' => $row['email'],
                'doj' => $row['doj'],
                'is_active_member' => $row['is_active_member'],
                'activation_date' => $row['is_active_member']?dmy_time(current_package_date_of_member($con, $member_id)):'',
                'total_business' => $row['total_business'],
                'created_on' => $row['created_on'],
                'investments' => $investments
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
    catch
    (Exception $exception) {
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
