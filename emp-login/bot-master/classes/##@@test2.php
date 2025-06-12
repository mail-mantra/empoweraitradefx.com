<?php
date_default_timezone_set("Asia/Calcutta"); 
include('Database.php');
class BotHandler {
    private $db;
    private $update;

    public function __construct() {
        $this->update = $update;
        $this->db = new Database();
       
    }
    
    public function now(){
    	$date_time = date('Y-m-d H:i:s');
    	return $date_time;
    }
    
    public function dmy($input_date){
    	return date('d-m-Y', strtotime($input_date));
    }
    
    public function saveChat($telegram_id, $user_query, $reply) {
        $date_time = $this->now();
        
        $conn = $this->db->connect();
        $_stmt = $conn->prepare("INSERT INTO telegram_bot (telegram_id, user_query, reply, date_time) VALUES (?, ?, ?, ?)");
        $_stmt->bind_param("isss", $telegram_id, $user_query, $reply, $date_time);
        $_stmt->execute();
        $_stmt->close();
        $this->db->disconnect();
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

    
}
$telegram_id = '95017542';
$bot = new BotHandler();
/*$assistant_content = $bot->viewChat($telegram_id);
if($assistant_content==''){
 $assistant_content = 'No Data Found';   
}
echo $assistant_content;*/

/*$user_query = 'Hello';
$reply = 'Good Afternoon';

$aa = $bot->saveChatMemCache($telegram_id, $user_query, $reply);
echo $aa;

$aa = $bot->viewChatMemCache($telegram_id);
echo $aa;*/

$bot->saveQuery($telegram_id,'Hello');

?>






