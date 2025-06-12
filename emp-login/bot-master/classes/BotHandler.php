<?php
class BotHandler {
    private $telegram;
    private $update;
    private $db;

    public function __construct($update) {
        $this->update = $update;
        $this->telegram = new Telegram('7490071011:AAFLjTY0uAHxuWVxKOK4y9hHcIIjY061ckY');
        $this->db = new Database();
    }
    
    private function now(){
    	$date_time = date('Y-m-d H:i:s');
    	return $date_time;
    }
    
    private function dmy($input_date){
    	return date('d-m-Y', strtotime($input_date));
    }
    
    private function encrypt($string){
        $encrypt_method = "AES-256-CBC";
    	$secret_key = 'RAzYcO0xMP6dAVGrFRc6RyTzaxXWaB4V';
    	$secret_iv = '147ED62F47579D9D';
    	$key = hash('sha256', $secret_key);
    	$iv = substr(hash('sha256', $secret_iv), 0, 16);
    	return base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    }
    
    public function getUserInfoByTelegramId($telegram_id) {
        $conn = $this->db->connect();
        $userInfo = [];

        $_stmt = $conn->prepare("SELECT member_id, mem_code, name, mobile, email FROM member WHERE BINARY telegram_id = ?");
        $_stmt->bind_param("i", $telegram_id);
        $_stmt->execute();
        $_stmt->bind_result($member_id, $mem_code, $name, $mobile, $email);

        if ($_stmt->fetch()) {
            $userInfo = [
                'member_id' => $member_id,
                'mem_code' => $mem_code,
                'name' => $name,
                'mobile' => $mobile,
                'email' => $email
            ];
        }

        $_stmt->close();
        $this->db->disconnect();

        return $userInfo;
    }
    
    public function saveChat($telegram_id, $user_query, $reply) {
        $date_time = $this->now();
        
        $conn = $this->db->connect();
        $_stmt = $conn->prepare("INSERT INTO telegram_bot (telegram_id, user_query, reply, date_time) VALUES (?, ?, ?, ?)");
        $_stmt->bind_param("isss", $telegram_id, $user_query, $reply, $date_time);
        $_stmt->execute();
        $_stmt->close();
        $this->db->disconnect();
        
        $this->saveChatMemCache($telegram_id, $user_query, $reply);
    }
    
     public function viewChat($telegram_id) {
        $results='';
        $conn = $this->db->connect();
        $_stmt = $conn->prepare("SELECT user_query,reply,date_time FROM telegram_bot WHERE BINARY telegram_id = ?");
        $_stmt->bind_param("i", $telegram_id);
        $_stmt->execute();
        $_stmt->bind_result($user_query, $reply, $date_time);
        while ($_stmt->fetch()) {
            /*$results[] = [
                'user_query' => $user_query,
                'reply' => $reply,
                'date_time' => $date_time
            ];*/
            $results = $results . "User Asked : $user_query, Reply :  $reply, Asked On : $date_time <br>";
        }

        $_stmt->close();
        $this->db->disconnect();
        return $results;
    }
    
    public function saveQuery($telegram_id, $user_query) {
        $date_time = $this->now();
        
        $conn = $this->db->connect();
        $_stmt = $conn->prepare("INSERT INTO telegram_query (telegram_id, user_query, date_time) VALUES (?, ?, ?)");
        $_stmt->bind_param("iss", $telegram_id, $user_query,  $date_time);
        $_stmt->execute();
        $_stmt->close();
        $this->db->disconnect();
    }
    
    
    public function saveChatMemCache($telegram_id, $user_query, $reply) {
        $date_time = $this->now();
        $host = $_SERVER['HTTP_HOST'];
        $host = preg_replace('/^www\./', '', $host);
    
        $postFields = http_build_query(array(
            'telegram_id' => $telegram_id,
            'user_query'  => $user_query,
            'reply'       => $reply,
            'date_time'   => $date_time,
            'domain_name' => $host
        ));
    
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://mailmantra.net/tg-api/set-cache.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
    
        $response = curl_exec($curl);
    
        if (curl_errno($curl)) {
            // Optional: log or handle the error
            error_log('cURL error: ' . curl_error($curl));
        }
    
        curl_close($curl);
        return $response;
    }
    
    public function viewChatMemCache($telegram_id) {
        $date_time = $this->now();
        $host = $_SERVER['HTTP_HOST'];
        $host = preg_replace('/^www\./', '', $host);
    
        $postFields = http_build_query(array(
            'telegram_id' => $telegram_id,
            'domain_name' => $host
        ));
    
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://mailmantra.net/tg-api/get-cache.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
    
        $response = curl_exec($curl);
    
        if (curl_errno($curl)) {
            // Optional: log or handle the error
            error_log('cURL error: ' . curl_error($curl));
        }
    
        curl_close($curl);
        $response = json_decode($response,true);
        $data = $response['data'];
        $results='';
        foreach($data as $d){
            $results = $results  . " {$d['user_query']} : {$d['reply']} on dated  {$d['date_time']} <br>";
        }
        return $results;
    }
    
    
   public function active_team_count($mem_code) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT COUNT(m1.member_id) AS team_count
                                FROM member_package_update m1
                                INNER JOIN member m2 ON m1.member_id = m2.member_id
                                WHERE m2.intro_mtree LIKE CONCAT('%', ?, '%')");
        $stmt->bind_param("s", $mem_code); 
        $stmt->execute();
        $stmt->bind_result($team_count);
        $stmt->fetch();
        $stmt->close();
        $this->db->disconnect();
        return $team_count;
    }

    
    

    public function handle() {
        $chat_id = $this->update["message"]["chat"]["id"] ?? null;
        $telegram_id = $this->update["message"]["from"]["id"] ?? null;
        $message = strtolower(trim($this->update["message"]["text"] ?? ''));

        
        try {
            
            
            $conn = $this->db->connect();
        
            $userAuth=true;
            //////////////////////////////////////
            /*$stmt = $conn->prepare("SELECT 1 FROM member WHERE telegram_id = ?");
            $stmt->bind_param("i", $telegram_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows ==1) {
                $userAuth=true;
            }else{
                $userAuth=false;
            } 
            $stmt->close();*/
            //////////////////////////////////////
            
            
            
            if($userAuth){
                if (strpos($message, "/start") === 0) {
                    $parts = explode(" ", $message);
                    $token = $parts[1] ?? null;
    
                    if ($token) {
                        $stmt = $conn->prepare("UPDATE member SET telegram_id = ? WHERE token = ?");
                        $stmt->bind_param("is", $telegram_id, $token);
                        $stmt->execute();
    
                        if ($stmt->affected_rows > 0) {
                            
                            $userInfo = $this->getUserInfoByTelegramId($telegram_id);
                            if(!empty($userInfo)) {
                                $this->telegram->sendMenu($chat_id, "Hello {$userInfo['name']}, Your software is now linked with your Telegram account. You can choose available option from the menu.");
                            }else{
                                $this->telegram->sendMessage($chat_id, "❗ No record found for your Telegram ID.");
                            }
                        } else {
                            $this->telegram->sendMessage($chat_id, "❗ Invalid or expired token.");
                        }
    
                        $stmt->close();
                    } else {
                        $this->telegram->sendMessage($chat_id, "Welcome! Please provide a valid token.");
                    }
    
                }
                elseif ($message === "👤 profile" || $message === "profile") {
                    /*$stmt = $conn->prepare("SELECT mem_code, name, mobile, email FROM member WHERE BINARY telegram_id = ?");
                    $stmt->bind_param("i", $telegram_id);
                    $stmt->execute();
                    $stmt->bind_result($mem_code, $name, $mobile, $email);
    
                    if ($stmt->fetch()) {
                        //$info = "🆔 ID : $mem_code\n 👤 Name : $name\n📧 Email : $email\n📱Mobile : $mobile";
                        $title = "🔹 Your Profile Information 🔹\n";
                        $info = $title . "🆔 ID : $mem_code\n👤 Name : $name\n📧 Email : $email\n📱 Mobile : $mobile";
                        $this->telegram->sendMessage($chat_id, $info);
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No record found for your Telegram ID.");
                    }
    
                    $stmt->close();*/
                    
                     $userInfo = $this->getUserInfoByTelegramId($telegram_id);
                     if(!empty($userInfo)) {
                            //$info = "👤 Name: {$userInfo['name']}\n📧 Email: {$userInfo['email']}\n📝 Other Info: {$userInfo['other_field']}";
                            $info = "🆔 ID : {$userInfo['mem_code']}\n 👤 Name : {$userInfo['name']}\n📧 Email : {$userInfo['email']}\n📱Mobile : {$userInfo['mobile']}";
                            $this->telegram->sendMessage($chat_id, $info);
                            
                            $this->saveChat($telegram_id, $message, $info);
                            
                     }else{
                        $this->telegram->sendMessage($chat_id, "❗ No record found for your Telegram ID.");
                     }
    
                }
                elseif ($message === "🔗 referral link" || $message === "referral link") {
                    $stmt = $conn->prepare("SELECT mem_code FROM member WHERE BINARY telegram_id = ?");
                    $stmt->bind_param("i", $telegram_id);
                    $stmt->execute();
                    $stmt->bind_result($mem_code);
                    $fetch = $stmt->fetch();
                    $stmt->close();
                 
    
                    if ($fetch) {
                        $ref = $this->encrypt($mem_code);
                        $ref_link = "https://empoweraitradefx.com/emp-login/member/referral-join?ref=" . $ref;
                        /*$info = "🔗 Referral Link : $ref_link";*/
                        $this->telegram->sendMessage($chat_id, $ref_link);
                        
                        $info = "🔗 Referral Link : $ref_link";
                        $this->saveChat($telegram_id, $message, $info);
                        
                        //$this->telegram->sendLinkButton($chat_id, "Click Here", "Ref. Link", $ref_link);
                        
                        
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No record found for your Telegram ID.");
                    }
    
                    
    
                }
                elseif ($message === "🧩 sponsor count" || $message === "sponsor count") {
                    $stmt = $conn->prepare("SELECT COUNT(*) 
                                            FROM member 
                                            WHERE intro_code = (
                                                SELECT mem_code 
                                                FROM member 
                                                WHERE BINARY telegram_id = ?
                                            )
                                        ");
                    $stmt->bind_param("i", $telegram_id);
                    $stmt->execute();
                    $stmt->bind_result($sponsor_count);
                    $fetch = $stmt->fetch();
                    $stmt->close();
                 
    
                    if ($fetch) {
                        $info = "🧩 Your direct team count is  $sponsor_count";
                        $this->telegram->sendMessage($chat_id, $info);
                        $this->saveChat($telegram_id, $message, $info);
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No record found for your Telegram ID.");
                    }
    
                    
    
                }
                
                /*elseif ($message === "🔢 team count" || $message === "team count") {
                    $stmt = $conn->prepare("SELECT COUNT(*) 
                                            FROM member 
                                            WHERE intro_mtree LIKE CONCAT('%', (
                                                SELECT mem_code 
                                                FROM member 
                                                WHERE BINARY telegram_id = ?
                                            ), '%')");
                    $stmt->bind_param("i", $telegram_id);
                    $stmt->execute();
                    $stmt->bind_result($team_count);
                    
    
                    if ($stmt->fetch()) {
                        
                        
                        //$active_count = active_team_count($userInfo['mem_code']);
                        $active_count = 0;
                        //$info = "🔢 Your total team count is $team_count out of which $active_count is active";
                        $userInfo = $this->getUserInfoByTelegramId($telegram_id);     
                        $info = $userInfo['mem_code'];
                        $this->telegram->sendMessage($chat_id, $info);
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No record found for your Telegram ID.");
                    }
    
                    $stmt->close();
    
                }*/
                
                elseif ($message === "🔢 team count" || $message === "team count") {
                    
                        $stmt = $conn->prepare("SELECT COUNT(*) 
                                                FROM member 
                                                WHERE intro_mtree LIKE CONCAT('%', (
                                                    SELECT mem_code 
                                                    FROM member 
                                                    WHERE BINARY telegram_id = ?
                                                ), '%')");
                        $stmt->bind_param("i", $telegram_id);
                        $stmt->execute();
                        $stmt->bind_result($team_count);
                        $stmt->fetch();
                        $stmt->close();
                        
                        $userInfo = $this->getUserInfoByTelegramId($telegram_id);   
                        $mem_code = $userInfo['mem_code'];
                        $active_count = $this->active_team_count($mem_code) ;
                        $info = "🔢 Your total team count is $team_count out of which $active_count is active";
                        $this->telegram->sendMessage($chat_id, $info);
                        
                        $this->saveChat($telegram_id, $message, $info);
                    
                }
                elseif ($message === "💰 profit" || $message === "profit") {
                    $stmt = $conn->prepare("SELECT IFNULL((
                                            SELECT a.balance 
                                            FROM roi_wallet_balance a 
                                            INNER JOIN member b ON a.member_id = b.member_id 
                                            WHERE BINARY b.telegram_id = ? 
                                            ORDER BY a.member_id DESC 
                                            LIMIT 1
                                        ), 0) AS balance");
                    $stmt->bind_param("i", $telegram_id);
                    $stmt->execute();
                    $stmt->bind_result($profit_balance);
                    $fetch = $stmt->fetch();
                    $stmt->close();
                 
    
                    if ($fetch) {
                        $info = "💰 Your available Profit balance is $profit_balance";
                        $this->telegram->sendMessage($chat_id, $info);
                        $this->saveChat($telegram_id, $message, $info);
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No record found for your Telegram ID.");
                    }
    
                    
    
                }
                elseif ($message === "📊 business" || $message === "business") {
                    
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
                    
                            $info = "📊 Your active business value is currently : $grand_business_total";
                            $this->telegram->sendMessage($chat_id, $info);
                            
                            $this->saveChat($telegram_id, $message, $info);
                            
                        } else {
                            $this->telegram->sendMessage($chat_id, "❗ No record found for your Telegram ID.");
                        }
                    
                }
                elseif ($message === "💼 fund" || $message === "fund") {
                    $stmt = $conn->prepare("SELECT IFNULL((
                                            SELECT a.balance 
                                            FROM myfund_wallet_balance a 
                                            INNER JOIN member b ON a.member_id = b.member_id 
                                            WHERE BINARY b.telegram_id = ? 
                                            ORDER BY a.member_id DESC 
                                            LIMIT 1
                                        ), 0) AS balance");
                    $stmt->bind_param("i", $telegram_id);
                    $stmt->execute();
                    $stmt->bind_result($fund_balance);
                    $fetch = $stmt->fetch();
                    $stmt->close();
                 
    
                    if ($fetch) {
                        $info = "💼 Your available fund wallet balance is  $fund_balance";
                        $this->telegram->sendMessage($chat_id, $info);
                        $this->saveChat($telegram_id, $message, $info);
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No record found for your Telegram ID.");
                    }
    
                    
    
                }elseif ($message === "👜 working" || $message === "working") {
                    $stmt = $conn->prepare("SELECT IFNULL((
                                            SELECT a.balance 
                                            FROM working_wallet_balance a 
                                            INNER JOIN member b ON a.member_id = b.member_id 
                                            WHERE BINARY b.telegram_id = ? 
                                            ORDER BY a.member_id DESC 
                                            LIMIT 1
                                        ), 0) AS balance");
                    $stmt->bind_param("i", $telegram_id);
                    $stmt->execute();
                    $stmt->bind_result($working_balance);
                    $fetch = $stmt->fetch();
                    $stmt->close();
                 
    
                    if ($fetch) {
                        $info = "👜 Your available working wallet balance is  $working_balance";
                        $this->telegram->sendMessage($chat_id, $info);
                        $this->saveChat($telegram_id, $message, $info);
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No record found for your Telegram ID.");
                    }
    
                    
    
                }
                elseif ($message === "💳 self investment" || $message === "self investment") {
                    $stmt = $conn->prepare("select ifnull(sum(a.amount),0) as total_investment 
                                            FROM member_package_update_log a 
                                            INNER JOIN member b on a.member_id=b.member_id 
                                            WHERE b.telegram_id=? ");
                    $stmt->bind_param("i", $telegram_id);
                    $stmt->execute();
                    $stmt->bind_result($self_investment);
                    $fetch = $stmt->fetch();
                    $stmt->close();
                 
    
                    if ($fetch) {
                        $info = "💳 You current self investment is $self_investment";
                        $this->telegram->sendMessage($chat_id, $info);
                        $this->saveChat($telegram_id, $message, $info);
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No record found for your Telegram ID.");
                    }
    
                   
    
                }
                elseif ($message === "🏅 my rank" || $message === "my rank") {
                    $stmt = $conn->prepare("SELECT IFNULL(a.rank, 'no_rank') AS my_rank
                                            FROM member b
                                            LEFT JOIN salary_eligibility a ON a.member_id = b.member_id
                                            WHERE b.telegram_id = ?");
                    $stmt->bind_param("i", $telegram_id);
                    $stmt->execute();
                    $stmt->bind_result($my_rank);
                    $fetch = $stmt->fetch();
                    $stmt->close();
                 
    
                    if ($fetch) {
                        if($my_rank=='no_rank'){
                             $info = "🏅 You have not achieved any rank yet. Wishing you the best of luck!";
                        }else{
                             $info = "🏅 You have achieved $my_rank rank. Wishing you the best of luck!";
                        }
                       
                        $this->telegram->sendMessage($chat_id, $info);
                        $this->saveChat($telegram_id, $message, $info);
                        
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No record found for your Telegram ID.");
                    }
    
                   
    
                }
                elseif ($message === "my intro" || $message === "myintro") {
                    $stmt = $conn->prepare("SELECT mem_code, name FROM member WHERE telegram_id = ?");
                    $stmt->bind_param("i", $telegram_id);
                    $stmt->execute();
                    $stmt->bind_result($mem_code,$name);
                    $fetch = $stmt->fetch();
                    $stmt->close();
                 
                    if ($fetch) {
                        $info = "Your Introducer Code is $mem_code and Name is $name";
                        $this->telegram->sendMessage($chat_id, $info);
                        $this->saveChat($telegram_id, $message, $info);
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No record found for your Telegram ID.");
                    }
    
                    
                }
                elseif ($message === "my bank" || $message === "mybank") {
                    $stmt = $conn->prepare("SELECT m1.bnk_nm, m1.brnch_nm, m1.acc_nm, m1.acc_no, m1.acc_type, m1.nom_name, m1.nom_relation, m1.pan_no FROM member_details m1 INNER JOIN member m2 ON m1.member_id = m2.member_id WHERE m2.telegram_id=?");
                    $stmt->bind_param("i", $telegram_id);
                    $stmt->execute();
                    $stmt->bind_result($bank_name,$branch_name,$ac_name,$ac_no,$ac_type,$nom_name,$nom_relation,$pan_no);
                    $fetch = $stmt->fetch();
                    $stmt->close();
                 
                    if ($fetch) {
                        $info = "Bank Name : $bank_name\n Branch Name : $branch_name\n Account Name : $ac_name\n Account No. : $ac_no \n Account Type. : $ac_type \n Nominee Name : $nom_name \n Nominee Relation : $nom_relation \n PAN No. : $pan_no";
                        $this->telegram->sendMessage($chat_id, $info);
                        $this->saveChat($telegram_id, $message, $info);
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No record found for your Telegram ID.");
                    }
    
                    
                }
                elseif ($message === "password" || $message === "pass word") {
                    $stmt = $conn->prepare("SELECT m1.password FROM member_login m1 INNER JOIN member m2 ON m1.member_id = m2.member_id WHERE m2.telegram_id=?");
                    $stmt->bind_param("i", $telegram_id);
                    $stmt->execute();
                    $stmt->bind_result($password);
                    $fetch = $stmt->fetch();
                    $stmt->close();
                 
                    if ($fetch) {
                        $info = "The password for your software login is $password";
                        $this->telegram->sendMessage($chat_id, $info);
                        $this->saveChat($telegram_id, $message, $info);
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No record found for your Telegram ID.");
                    }
                    
                }
                elseif ($message === "remove my telegram" || $message === "removemytelegram" || $message === "remove mytelegram" || $message === "removemy telegram") {
                    $a = NULL;
                    $stmt = $conn->prepare("UPDATE member SET telegram_id = ? WHERE telegram_id = ?");
                    $stmt->bind_param("ii", $a, $telegram_id);
                    $stmt->execute();
    
                    if ($stmt->affected_rows > 0) {
                        $this->update["message"]["from"]["id"]='';
                        $this->telegram->sendMessage($chat_id, "✔️ You have successfully removed your telegram");
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No record found for your Telegram ID.");
                    }
                    $stmt->close();
                }
                elseif ($message === "last txn fund" || $message === "lasttxn fund" || $message === "last txnfund") {
                    $stmt = $conn->prepare("SELECT m1.particulars, m1.txn_date, m1.credit, m1.debit, m1.balance FROM myfund_wallet_transaction m1 INNER JOIN member m2 ON m1.member_id = m2.member_id WHERE m2.telegram_id=? ORDER BY m1.id DESC LIMIT 1");
                    $stmt->bind_param("i", $telegram_id);
                    $stmt->execute();
                    $stmt->bind_result($particulars, $txn_date, $credit, $debit, $balance );
                    $fetch = $stmt->fetch();
                    $stmt->close();
                    
                    //$txn_date = $this->dmy($txn_date);
                    
                    if ($fetch) {
                        $info = "Your last fund transaction is \n Date : $txn_date | Particulars : $particulars | Credit : $credit | Debit : $debit  | Balance : $balance";
                        $this->telegram->sendMessage($chat_id, $info);
                        $this->saveChat($telegram_id, $message, $info);
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No transaction found");
                    }
                    
                }
                elseif ($message === "last txn working" || $message === "lasttxn working" || $message === "last txnworking") {
                    $stmt = $conn->prepare("SELECT  m1.particulars, m1.txn_date, m1.credit, m1.debit, m1.balance FROM working_wallet_transaction m1 INNER JOIN member m2 ON m1.member_id = m2.member_id WHERE m2.telegram_id=? ORDER BY m1.id DESC LIMIT 1");
                    $stmt->bind_param("i", $telegram_id);
                    $stmt->execute();
                    $stmt->bind_result($particulars, $txn_date, $credit, $debit, $balance );
                    $fetch = $stmt->fetch();
                    $stmt->close();
                    
                    //$txn_date = $this->dmy($txn_date);
                    
                    if ($fetch) {
                        $info = "Your last working transaction is \n Date : $txn_date | Particulars : $particulars | Credit : $credit | Debit : $debit  | Balance : $balance";
                        $this->telegram->sendMessage($chat_id, $info);
                        $this->saveChat($telegram_id, $message, $info);
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No transaction found");
                    }
                    
                }
                elseif ($message === "last txn profit" || $message === "lasttxn profit" || $message === "last txnprofit") {
                    $stmt = $conn->prepare("SELECT  m1.particulars, m1.txn_date, m1.credit, m1.debit, m1.balance FROM roi_wallet_transaction m1 INNER JOIN member m2 ON m1.member_id = m2.member_id WHERE m2.telegram_id=? ORDER BY m1.id DESC LIMIT 1");
                    $stmt->bind_param("i", $telegram_id);
                    $stmt->execute();
                    $stmt->bind_result($particulars, $txn_date, $credit, $debit, $balance );
                    $fetch = $stmt->fetch();
                    $stmt->close();
                    
                    //$txn_date = $this->dmy($txn_date);
                    
                    if ($fetch) {
                        $info = "Your last profit transaction is \n Date : $txn_date | Particulars : $particulars | Credit : $credit | Debit : $debit  | Balance : $balance";
                        $this->telegram->sendMessage($chat_id, $info);
                        $this->saveChat($telegram_id, $message, $info);
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No transaction found");
                    }
                    
                }
                
                elseif ($message === "🔐 open user panel" || $message === "open user panel") {
                    $stmt = $conn->prepare("SELECT token FROM member WHERE telegram_id = ?");
                    $stmt->bind_param("i", $telegram_id);
                    $stmt->execute();
                    $stmt->bind_result($user_token);
    
                    if ($stmt->fetch()) {
                        //$link = "https://empoweraitradefx.com/index?telegram_id=$telegram_id&token=$user_token";
                        $link = "https://empoweraitradefx.com/index";
                        $this->telegram->sendLinkButton($chat_id, "Click below to open your User Panel:", $link);
                    } else {
                        $this->telegram->sendMessage($chat_id, "❗ No token found. Please start with a valid token using /start.");
                    }
    
                    $stmt->close();
    
                }elseif ($message === "ℹ️ help" || $message === "help") {
                     $link = "https://t.me/xxx";
                     $this->telegram->sendLinkButton($chat_id, "Click Here", "Open Chat", $link);
                }
                elseif ($message === "📢 join our channel" || $message === "join our channel") {
                     $link = "https://t.me/empowertradefxofficial";
                     $this->telegram->sendLinkButton($chat_id, "Click Here", "Empower TradeFX", $link);
                     $info = "Our Telegram Channel is $link";
                     $this->saveChat($telegram_id, $message, $info);
                }
                else {
                            /*$userInfo = $this->getUserInfoByTelegramId($telegram_id);
                            $info = "Hi {$userInfo['name']},\n\n";
                            $info .= "You can easily interact with your software using the buttons provided below. ";
                            $info .= "Additionally, you may use the following keywords to perform specific actions:\n\n";
                            
                            $info .= "🔹 Type These Available Commands\n\n"; 
                            
                            $info .= "My Intro\n➡️ Get the name and ID of your introducer.\n\n";
                            //$info .= "My Bank\n➡️ View the bank account details you have added.\n\n";
                            $info .= "Password\n➡️ Retrieve your current password securely.\n\n";
                            $info .= "Remove My Telegram\n➡️ Disconnect the software from your Telegram account.\n\n";
                            $info .= "Last Txn Fund\n➡️ See your latest transaction in the Fund Wallet.\n\n";
                            $info .= "Last Txn Working\n➡️ See your latest transaction in the Working Wallet.\n\n";
                            $info .= "Last Txn Profit\n➡️ See your latest transaction in the Profit Wallet.";
    
                            $this->telegram->sendMenu($chat_id, $info);*/
                            
                            
                      
                            
                            //$assistant_content = $this->viewChat($telegram_id);
                            /*if($assistant_content==''){
                                 $assistant_content =  "Bot Commands:
                                                        My Intro – View introducer’s name & ID
                                                        Password – Retrieve your current password
                                                        Remove My Telegram – Disconnect bot access
                                                        Last Txn Fund – View last Fund Wallet transaction
                                                        Last Txn Working – View last Working Wallet transaction
                                                        Last Txn Profit – View last Profit Wallet transaction";   
                            }*/
                               
                            $this->saveQuery($telegram_id,$message) ;   
                            $assistant_content = $this->viewChatMemCache($telegram_id);
                            
                            $this->telegram->sendMenu($chat_id, 'Please Wait...');
                            include('openapi.php');
                            
                            $this->telegram->sendMenu($chat_id, $ai_result);
                }
            }else{
                $this->telegram->sendMessage($chat_id, "❗ Sorry ! Your are not register in telegram. Please login to your software");
            }

        } catch (Exception $e) {
            $this->telegram->sendMessage($chat_id, "❌ " . $e->getMessage());
        } finally {
            $this->db->disconnect();
        }
    }
}

