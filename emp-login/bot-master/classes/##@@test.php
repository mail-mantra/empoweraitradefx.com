<?php
require_once 'Database.php'; 
$telegram_id = 950175423; 

try {
    $db = new Database();
    $conn = $db->connect();
    /*
    $total_business_amt_of_member = 0;
    $grand_business_total = 0;

    $stmt1 = $conn->prepare("SELECT member_id as self_id, mem_code as self_code FROM member WHERE BINARY telegram_id = ?");
    if (!$stmt1) {
        throw new Exception("Prepare failed for stmt1: " . $conn->error);
    }

    $stmt1->bind_param("i", $telegram_id);
    $stmt1->execute();
    $stmt1->bind_result($self_id, $self_code);

    if ($stmt1->fetch()) {
        $stmt1->close();

        $like_code = "%" . $self_code . "%";
        $stmt2 = $conn->prepare("SELECT member_id FROM member WHERE intro_mtree LIKE ?");
        if (!$stmt2) {
            throw new Exception("Prepare failed for stmt2: " . $conn->error);
        }

        $stmt2->bind_param("s", $like_code);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        while ($row2 = $result2->fetch_assoc()) {
            $member_id = $row2['member_id'];

            $stmt3 = $conn->prepare("SELECT IFNULL(SUM(amount), 0) as total_investment FROM member_package_update_log WHERE member_id=?");
            if (!$stmt3) {
                throw new Exception("Prepare failed for stmt3: " . $conn->error);
            }

            $stmt3->bind_param("i", $member_id);
            $stmt3->execute();
            $stmt3->bind_result($total_investment);
            $stmt3->fetch();
            $total_business_amt_of_member += $total_investment;
            $stmt3->close();
        }

        $stmt2->close();

        $stmt4 = $conn->prepare("SELECT IFNULL(SUM(amount), 0) as total_business_amt_of_self FROM member_package_update_log WHERE member_id=?");
        if (!$stmt4) {
            throw new Exception("Prepare failed for stmt4: " . $conn->error);
        }

        $stmt4->bind_param("i", $self_id);
        $stmt4->execute();
        $stmt4->bind_result($total_business_amt_of_self);
        $stmt4->fetch();
        $stmt4->close();

        $grand_business_total = $total_business_amt_of_member + $total_business_amt_of_self;

        $info = "Business : $grand_business_total";
        echo $info;
    } else {
        echo "No Telegram ID Found";
    }
    */
    
    
    
    
    $stmt = $conn->prepare("SELECT mem_code, name, mobile, email FROM member WHERE BINARY telegram_id = ?");
    $stmt->bind_param("i", $telegram_id);
    $stmt->execute();
    $stmt->bind_result($mem_code, $name, $mobile, $email);
    $fetch = $stmt->fetch();
    $stmt->close();

    if ($fetch) {
        $title = "🔹 Your Profile Information 🔹\n";
        $info = $title . "🆔 ID : $mem_code\n👤 Name : $name\n📧 Email : $email\n📱 Mobile : $mobile";
        echo $info;
       
    } else {
        echo "No Data Found";
    }

    
    
    

    $db->disconnect();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
