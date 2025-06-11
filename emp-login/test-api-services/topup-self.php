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

    $con = $db->connect();

    try {
        $user_id = $post_data['user_id'] ?? '';
        if(!$user_id) {
            throw new Exception('User Id Required');
        }

        $amount = $post_data['amount'] ?? 0;
        if(!$amount) {
            throw new Exception('Amount Required');
        }

        if($post_data['amount'] < 50) {
            throw new Exception("Minimum amount 50");
        }

        if($post_data['amount'] > 50 && ($post_data['amount'] % 50 > 0)) {
            throw new Exception("Enter value in multiple of 50");
        }

        $member = member_id($con, $user_id);

        if(!$member) {
            throw new Exception("User not found");
        }

        if(!$member['valid_member']) {
            throw new Exception("Invalid User");
        }


        $wallet_balance = get_wallet_balance_of_member($con, $user_id, 'myfund_wallet_balance');

        if($wallet_balance < $amount || $wallet_balance <= 0) {
            throw new Exception("Your wallet balance is low to topup.");
        }


        $package_id = 1;
        $_amount = $amount;
        $roi_type = 1;
        $wallet_used = 'fund';
        $user_code = $member['mem_code'];
        $now = date('Y-m-d H:i:s');

        $sql = "CALL MEMBER_PACKAGE_TOPUP('" . $user_code . "','" . $package_id . "','" . $_amount . "','" . $user_code . "','" . $now . "', '" . $wallet_used . "', '" . $roi_type . "')";

        $q1 = mysqli_query($con, $sql);
        if($q1->num_rows > 0) {
            $r1 = mysqli_fetch_assoc($q1);
            $n = $r1['return_id'];

            if($n == 1) {
                // --------------- Start : email ------------------

                $arr4email = [
                    'name' => $member['name'],
                    'amount' => $_amount,
                ];
                $mail_to = $member['email'];
                $mail_subject = "Community Trade Investment Confirmation";
                $mail_message = getInvestmentEmailHtml($arr4email);
                mm_smtp($mail_to, $mail_subject, $mail_message);
                // --------------- End : email ------------------
                $response['status'] = 1;
                $response['msg'] = $_amount . " investment successfully.";

            }
            else {
                throw new Exception($r1['var_message']);
            }
        }
        else {
            throw new Exception('Invalid Request '.$sql);
        }
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



