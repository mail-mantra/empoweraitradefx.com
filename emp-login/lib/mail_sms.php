<?php
include_once '../class/DbClass.php';
/*----- SMS function starts here -----*/
if(!function_exists('now')) /* Login Function */
{
    function now()
    {
        $date_time=date('Y-m-d H:i:s',time());
        return $date_time;
    }
}

function sms_mm($con,$authKey,$senderId,$sms_to,$sms_message,$sms_transaction_by,$DLT_TE_ID='', $flash=null,$unicode=null,$schtime=null)
{
    if(trim($authKey)=='') { $authKey= MM_SMS_AUTH_KEY; }
    $sms_message=urldecode($sms_message);
    if(strlen($senderId)!=6) {
        $senderId = 'EMAILT';
    }
    else {
        $senderId = $senderId;
    }

    $sms_date_time = now();
    $sms_msg_len = count_gsm_string($sms_message);
    $total_sms = multipart_count($sms_message);
    if($total_sms>0)
    {
        //$message4db = $con->real_escape_string($sms_message);
        $message4db = $sms_message;
        $sms_to_arr=explode(',',$sms_to);
        $sms_to_count=count($sms_to_arr);


        $sms_log_id=array();
        $sql1 = 0;
        foreach($sms_to_arr as $mobileNumber1)
        {
            $schtime4db = !empty($schtime) ? "'$schtime'" : "NULL";

            $sql1="INSERT INTO `sms_log`(`sender_id`, `added`, `receiver`, `status`, `description`, `msg_body`, `schedule_date`, `sms_api`, `total_word`, `total_sms`) VALUES ('".$senderId."', '".$sms_date_time."', '".$mobileNumber1."', '0', 'Added','".$message4db."', ".$schtime4db.", 'mm_co_in', ".$sms_msg_len.", ".$total_sms." )";
            $res1=$con->query($sql1);
            $n1=$con->insert_id;
            if(($res1===TRUE) && ($n1>0))
            {
                $sms_log_id[]=$n1;
            }
            else
            {
                // $con->rollback();
                $result['status']=0;
                $result['message']='Temporary Problem..';
                $result['code']='S001';
                return $result;
                exit;
            }
        }

        if(count($sms_log_id)>0)
        {

            //Multiple mobiles numbers separated by comma
            $mobileNumber = trim($sms_to);
            $message = urlencode($sms_message);


            $postData = array(
                'authkey' => $authKey,
                'mobiles' => $mobileNumber,
                'message' => $message,
                'response'=>'json',
                'DLT_TE_ID' => $DLT_TE_ID,
            );

            if($flash==1) { $postData['flash']=1; }
            if($unicode==1) { $postData['unicode']=1; }

            if($schtime!='') { $postData['schtime']=$schtime; }




            //API URL
            $url="http://sms1.mailmantra.com/v2/api/send_sms";
            // $url="http://sms.mailmantra.com/sendhttp.php";

            // init the resource
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postData
                //,CURLOPT_FOLLOWLOCATION => true
            ));


            //Ignore SSL certificate verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


            //get response
            $output =  curl_exec($ch); // '{ "type": "success", "message": "abc" }';

            //Print error if any
            if(curl_errno($ch))
            {
                // echo 'error:' . curl_error($ch);
                $result['status']=0;
                $result['message']='Internal Error'; // 'Curl Error..';
                $result['code']='S005';
                return $result;
            }
            else
            {
                curl_close($ch);
                $output_arr = json_decode($output,true);
                if($output_arr['status']=='1')
                {

                    $request_id = $output_arr['code'];
                    $description = 'Sent';

                    $result['status']=1;
                    $result['message']= count($sms_log_id).' SMS send Successfully..';
                    $result['code']=$output_arr['code'];

                }
                else
                {
                    $request_id = '';
                    $description = $output_arr['message'];

                    $result['status']=0;
                    $result['message']=$output_arr['message'];
                    $result['code']='S002';
                }


                $sql4_part = implode("') OR (`id`='", $sms_log_id);
                $sql4="UPDATE `sms_log` SET `request_id`='".$request_id."', `description`='".$description."' WHERE ((`id`='".$sql4_part."' ))";

                $res4=$con->query($sql4);

                return $result;
            }

        }
        else
        {
            // $con->rollback();
            $result['status']=0;
            $result['message']='Temporary Problem.... '.$sql1;
            $result['code']='S003';
            return $result;
        }
    }
    else
    {
        // $con->rollback();
        $result['status']=0;
        $result['message']='Invalid / Unsupported Text Message..';
        $result['code']='S006';
        return $result;
    }
}

function sms_balance($authKey='',$senderId=null)
{

    if(!$authKey) { $authKey= MM_SMS_AUTH_KEY; }
    if(strlen($senderId)!=6) {
        $senderId = 'EMAILT';
    }
    else {
        $senderId = $senderId;
    }


    $postData = array(
        'authkey' => $authKey,
        'sender' => $senderId,
        'response' => 'json',
    );

    //API URL
    $url="http://sms1.mailmantra.com/v2/api/balance";

    // init the resource
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData
        //,CURLOPT_FOLLOWLOCATION => true
    ));

    //Ignore SSL certificate verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


    //get response
    $output =  curl_exec($ch); // '{ "type": "success", "message": "abc" }';

    //Print error if any
    if(curl_errno($ch))
    {
        // echo 'error:' . curl_error($ch);
        $result['status']=0;
        $result['message']='Internal Error'; // 'Curl Error..';
        $result['code']='S005';
        return $result;
    }
    else
    {
        curl_close($ch);
        $output_arr = json_decode($output,true);
        return $output_arr;
    }
}


function count_gsm_string($str)
{
    // Basic GSM character set (one 7-bit encoded char each)
    $gsm_7bit_basic = "@£\$¥èéùìòÇ\nØø\rÅåΔ_ΦΓΛΩΠΨΣΘΞÆæßÉ !\"#¤%&'()*+,-./0123456789:;<=>?¡ABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÑÜ§¿abcdefghijklmnopqrstuvwxyzäöñüà";

    // Extended set (requires escape code before character thus 2x7-bit encodings per)
    $gsm_7bit_extended = "^{}\\[~]|€";

    $len = 0;

    for($i = 0; $i < mb_strlen($str); $i++) {
        if(mb_strpos($gsm_7bit_basic, $str[$i]) !== FALSE) {
            $len++;
        } else if(mb_strpos($gsm_7bit_extended, $str[$i]) !== FALSE) {
            $len += 2;
        } else {
            return -1; // cannot be encoded as GSM, immediately return -1
        }
    }

    return $len;
}

//////////////////////////////////////////////////////////////////////////////////
// Internal encoding must be set to UTF-8,
// and the input string must be UTF-8 encoded for this to work correctly
function count_ucs2_string($str)
{
    $utf16str = mb_convert_encoding($str, 'UTF-16', 'UTF-8');
    // C* option gives an unsigned 16-bit integer representation of each byte
    // which option you choose doesn't actually matter as long as you get one value per byte
    $byteArray = unpack('C*', $utf16str);
    return count($byteArray) / 2;
}


function multipart_count($str)
{
    $one_part_limit = 159; // use a constant i.e. GSM::SMS_SINGLE_7BIT
    $multi_limit = 153; // again, use a constant
    $max_parts = 3; // ... constant

    $str_length = count_gsm_string($str);

    if($str_length === -1) {
        $one_part_limit = 70; // ... constant
        $multi_limit = 67; // ... constant
        $str_length = count_ucs2_string($str);
    }

    if($str_length <= $one_part_limit) {
        // fits in one part
        return 1;
    }
    else if($str_length > ($max_parts * $multi_limit)) {
        // too long
        return -1; // or throw exception, or false, etc.
    } else {
        // divide the string length by multi_limit and round up to get number of parts
        return ceil($str_length / $multi_limit);
    }
}



/*----- SMS function ends here -----*/

/*----- MAIL function starts here -----*/
function _mail($mail_to,$mail_message){
    $mail_subject = "COMPANY SETTING ALERT" ;
    $uself = 0;
    $headersep = (!isset( $uself ) || ($uself == 0)) ? "\r\n" : "\n" ;
    $account_login_date_time=date("l F d, Y, h:i:s A",(time()+((5*3600)+(30*60))));
    $http_referrer = getenv( "HTTP_REFERER" );

    $url=$_SERVER['HTTP_HOST'];
    preg_match('@^(?:http://)?([^/]+)@i',
        "$url", $matches);
    $host = $matches[1];
    preg_match('/[^.]+\.[^.]+$/', $host, $matches);
    $your_domain="{$matches[0]}";

    $mail_from="alert@$your_domain";
    $mail_body =
        "This message was sent from: " .
        "$mail_from\n\n" .
        $mail_message .
        "\n\n" ;
    mail($mail_to, $mail_subject, $mail_body,"From: $mail_from" . $headersep . "Reply-To: $mail_from", '-f'.$mail_from );
}
/*----- MAIL function ends here -----*/



/*----- Bulk SMS function start here -----*/


function sms_v2($con, $sms=null, $sms_transaction_by='', $authKey=null, $unicode=null, $flash=null, $scheduledatetime=null, $country='91', $DLT_TE_ID='')
{
    if(!$authKey)
    {
        $authKey=MM_SMS_AUTH_KEY;
    }

    /*
    {
      "sms": [
        {
          "message": "Message1",
          "to": [
            "98260XXXXX",
            "98261XXXXX"
          ]
        },
        {
          "message": "Message2",
          "to": [
            "98260XXXXX",
            "98261XXXXX"
          ]
        }
      ]
    }*/

    $sms_data = json_encode($sms);

    // $post_data =array();

    $post_data['sms'] = $sms_data;

    if($flash==1) { $post_data['flash']=1; }
    if($unicode==1) { $post_data['unicode']=1; }
    if($DLT_TE_ID != '') { $post_data['DLT_TE_ID']=$DLT_TE_ID; }

    if($scheduledatetime!='') { $post_data['scheduledatetime']=$scheduledatetime; }

    $curl = curl_init();

    $url="http://sms1.mailmantra.com/v2/api/send_sms_v2";

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $post_data,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HTTPHEADER => array(
            "authkey: ".$authKey,
        ),
    ));

    //get response
    $output =  curl_exec($curl); // '{ "type": "success", "message": "abc" }';

    //Print error if any
    if(curl_errno($curl))
    {
        // echo 'error:' . curl_error($curl);
        $result['status']=0;
        $result['message']='Internal Error'; // 'Curl Error..';
        $result['code']='S005';
        return $result;
    }
    else
    {
        curl_close($curl);
        /*
        {
            "status": 1,
            "message": "2 SMS send Successfully..",
            "code": "5d245401d6fc0538002dedbb"
        }
        */
        $output_arr = json_decode($output,true);
        return $output_arr;

    }
}

/*----- Bulk SMS function ends here -----*/


/*----- Minified SMS function start here -----*/
function sms_mm_min($con,$sms_to,$sms_message,$dlt_te_id, $extras=[]) {
    $sms_transaction_by = (isset($extras['sms_transaction_by'])) ?$extras['sms_transaction_by']:"";
    $flash = (isset($extras['flash'])) ?$extras['flash']:null;
    $unicode = (isset($extras['unicode'])) ?$extras['unicode']:null;
    $schtime = (isset($extras['schtime'])) ?$extras['schtime']:null;
    $authKey = (isset($extras['authKey'])) ?$extras['authKey']:"";
    $senderId = (isset($extras['senderId'])) ?$extras['senderId']:"";

    return sms_mm($con,$authKey,$senderId,$sms_to,$sms_message,$sms_transaction_by,$dlt_te_id, $flash,$unicode,$schtime);
}

function sms_v2_min($sms, $DLT_TE_ID,  $extras=[]) {
    $authKey = (isset($extras['authKey'])) ?$extras['authKey']:null;
    $unicode = (isset($extras['unicode'])) ?$extras['unicode']:null;
    $flash = (isset($extras['flash'])) ?$extras['flash']:null;
    $scheduledatetime = (isset($extras['scheduledatetime'])) ?$extras['scheduledatetime']:null;

    return sms_v2(null, $sms, null, $authKey, $unicode, $flash, $scheduledatetime, null, $DLT_TE_ID);
}
/*----- Minified SMS function ends here -----*/



