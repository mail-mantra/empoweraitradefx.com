<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$now = now();


if (!isset($_SERVER['HTTP_REFERER'])) {
    $systemDenied = true;
    include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_REQUEST['action']) && isset($_REQUEST['tid']) && is_numeric(hash_decode($_REQUEST['tid'])) && $_REQUEST['action'] === 'del') {

    $topup_id = abs(trim(hash_decode($_REQUEST['tid'])));

    $con = $db->connect();
    $qf = mysqli_query($con, "SELECT member_id, intro_id, amount, created_on from member_package_update_log where id='" . $topup_id . "'");
    $db->dbDisconnet($con);
    $rf = mysqli_fetch_assoc($qf);
    $member_id = $rf['member_id'];
    $_topup_intro_id = $rf['intro_id'];
    $_topup_amount = $rf['amount'];
    $_topup_date = $rf['created_on'];


    if ((strtotime($current_date) - strtotime(dmy($_topup_date))) > 0) {
        $_SESSION['e'] = "Topup cannot be deleted. Limit is over.";
        
    } else {
        
        $con = $db->connect();
        $qs = mysqli_query($con, "SELECT * from member_package_update_log where member_id='$member_id'");
        $db->dbDisconnet($con);
        $_num_rows = mysqli_num_rows($qs);
        

        $con = $db->connect();
        mysqli_autocommit($con, FALSE);

        
        /*$q_ba_1 = mysqli_query($con, "SELECT balance from ba_wallet_balance where member_id='$member_id'  GROUP BY member_id");
        $r_ba_1 = mysqli_fetch_assoc($q_ba_1);
        $_prev_ba_balance = $r_ba_1['balance'];
        
        $_ba_debit_amount = $_topup_amount * 3;
        
        $_cur_ba_balance = $_prev_ba_balance - $_ba_debit_amount;
        
        
        $sql_ba_2 = "UPDATE ba_wallet_balance set balance = '$_cur_ba_balance', type = 'DR', amount = '$_ba_debit_amount', date_time = '$now' where member_id='$member_id'";
        
        $sql_ba_3 = "INSERT INTO ba_wallet_transaction(member_id, particulars, txn_date, transaction_type, credit, debit, balance, txn_date_time, ref_id, created_by)
        VALUES('$member_id', 'TOPUP_DELETE', '$current_date', 'DEBIT', 0, '$_ba_debit_amount', '$_cur_ba_balance', '$now', '$topup_id', '$action_by')";
        
        $q_ba_2 = mysqli_query($con,$sql_ba_2);
        $q_ba_3 = mysqli_query($con,$sql_ba_3);*/




        $sql_1 = "INSERT INTO `deleted_member_package_update_log`(`id`, `member_id`, `package_id`, `intro_id`, `amount`, `actual_amount`, `created_by`, `created_on`, `wallet_used`, `narration`, `direct_income`, `roi_count`, `roi_amount`, `roi_status`, `roi_percentage`, `default_roi_percentage`, `direct_number`, `direct_topup_count`, `level_status`, `topup_is_expired`, `topup_expiry_date`, `topup_created_on`, `deleted_on`, `deleted_by`) 
                SELECT `id`, `member_id`, `package_id`, `intro_id`, `amount`, `actual_amount`, `created_by`, `created_on`, `wallet_used`, `narration`, `direct_income`, `roi_count`, `roi_amount`, `roi_status`, `roi_percentage`, `default_roi_percentage`, `direct_number`, `direct_topup_count`, `level_status`, `topup_is_expired`, `topup_expiry_date`, `topup_created_on`, '" . $now . "', '" . $action_by . "' FROM `member_package_update_log` WHERE id='" . $topup_id . "'";


        $sql_2 = "delete from member_package_update_log WHERE id='" . $topup_id . "'";

        $q_1 = mysqli_query($con, $sql_1);

        $q_2 = mysqli_query($con, $sql_2);
        

        //if ($q_1 && $q_2 && $q_ba_2 && $q_ba_3) {
        
        if ($q_1 && $q_2) {
                        
            $sql_3 = "INSERT INTO `member_package_update`(`member_id`, `package_id`, `intro_id`, `amount`, `actual_amount`, `created_by`, `created_on`, `wallet_used`, `topup_created_on`)
                        SELECT `member_id`, `package_id`, `intro_id`, `amount`, `actual_amount`, `created_by`, `created_on`, `wallet_used`, `topup_created_on` FROM `member_package_update_log` where member_id='$member_id' order by id desc limit 1 
                        ON DUPLICATE KEY UPDATE
                        package_id= VALUES(`package_id`),
                        intro_id= VALUES(`intro_id`),
                        amount= VALUES(`amount`),
                        actual_amount= VALUES(`actual_amount`),
                        created_by= VALUES(`created_by`),
                        created_on= VALUES(`created_on`),
                        wallet_used= VALUES(`wallet_used`),
                        topup_created_on= VALUES(`topup_created_on`)";


            $sql_4 = "delete from member_package_update WHERE member_id='" . $member_id . "'";

            if ($_num_rows > 1) {
                $q_3 = mysqli_query($con, $sql_3);
                if ($q_3) {
                    mysqli_commit($con);
                    $_SESSION['s'] = "Investment delete successfull.";
                    header("Location: $back");
                    die;
                } else {
                    mysqli_rollback($con);
                    $_SESSION['e'] = "Temporary Error2...!";
                    header("Location: $back");
                    die;
                }
            } else {

                $q_4 = mysqli_query($con, $sql_4);
                if ($q_4) {
                    mysqli_commit($con);
                    $_SESSION['s'] = "Investment delete successfull.";
                    header("Location: $back");
                    die;
                } else {
                    mysqli_rollback($con);
                    $_SESSION['e'] = "Temporary Error1...!";
                    header("Location: $back");
                    die;
                }
            }
        } else {
            mysqli_rollback($con);
            $_SESSION['e'] = "Temporary Error...!";
            header("Location: $back");
            die;
        }

        $db->dbDisconnet($con);
    }
    
} else {
    $systemDenied = true;
    include('include/forced-logout.php');
}
