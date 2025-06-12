<?php
    /*
    $apiToken = "7869600040:AAGsYuX0aQ_FIPu3OWMb29jUbNRrhIlv054";
    $msg = "Hi,\nGood Afternoon !";
    $data = [
        'chat_id' => '-1002331144280', 
        'text' => $msg
    ];
    $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data) );   
    $response_array = json_decode($response,true);
    echo "<pre>";
    print_r($response_array);
    */
    
    
    /*
    Array
(
    [ok] => 1
    [result] => Array
        (
            [message_id] => 173
            [sender_chat] => Array
                (
                    [id] => -1002331144280
                    [title] => Blue sky trade solutions
                    [type] => channel
                )

            [chat] => Array
                (
                    [id] => -1002331144280
                    [title] => Blue sky trade solutions
                    [type] => channel
                )

            [date] => 1742285635
            [text] => Hi,
Good Afternoon !
        )

)
    */
    
    
    
    
    
    
   /*
    $apiToken = "7869600040:AAGsYuX0aQ_FIPu3OWMb29jUbNRrhIlv054";
    $message_id='184';
    $data = [
        'chat_id' => '-1002331144280', 
        'message_id' => $message_id
    ];
    $response = file_get_contents("https://api.telegram.org/bot$apiToken/deleteMessage?" . http_build_query($data) );   
    $response_array = json_decode($response,true);
    echo "<pre>";
    print_r($response_array);
   */
    /*
    Array
    (
    [ok] => 1
    [result] => 1
    )
    
   
    
   /*
    $apiToken = "7869600040:AAGsYuX0aQ_FIPu3OWMb29jUbNRrhIlv054";
    $msg = "Hi,\nGood Afternoon ! Everyone";
    $data = [
        'chat_id' => '-1002331144280', 
        'message_id' => '184',
        'text' => $msg
    ];
    $response = file_get_contents("https://api.telegram.org/bot$apiToken/editMessageText?" . http_build_query($data) );   
    $response_array = json_decode($response,true);
    echo "<pre>";
    print_r($response_array);
    */
    
    
    
  
?>

