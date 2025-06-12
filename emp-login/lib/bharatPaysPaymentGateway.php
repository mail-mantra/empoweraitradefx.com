<?php
/*
Below is an integration flow on how to use Cashfree's payouts.
Please go through the payout docs here: https://dev.cashfree.com/payouts

The following script contains the following functionalities :
    1.getToken() -> to get auth token to be used in all following calls.
    2.getBeneficiary() -> to get beneficiary details/check if a beneficiary exists
    3.createBeneficiaryEntity() -> to create beneficiaries
    4.requestTransfer() -> to create a payout transfer
    5.getTransferStatus() -> to get payout transfer status.


All the data used by the script can be found in the below assosciative arrays. This includes the clientId, clientSecret, Beneficiary object, Transaction Object.
You can change keep changing the values in the config file and running the script.
Please enter your clientId and clientSecret, along with the appropriate enviornment, beneficiary details and request details
*/

class bharatPaysPaymentGateway
{
    #default parameters
    private $Token = '8dc4b3ee3fd81da4aeb88e5bec9ba4b9';
    private $env = 'prod';

    #config objs
    private $baseUrls = array(
        'prod' => 'https://api.bharatpays.in',
        'test' => 'https://api.bharatpays.in',
    );
    private $urls = array(
        'createOrder' => '/api/upi_gateway/create_order',
        'getOrderStatus' => '/api/upi_gateway/status_check',
    );

    /*public $order = array(
        'customer_name' => 'skillludo',
        'customer_email' => 'info@skillludo.com',
        'customer_mobile' => '9999999999',
        'redirect_url' => 'https://skillludo.com/api/create_bharatpays_order_status.php',
        'p_info' => 'TopUp',
        'amount' => '1',
        'udf1' => '',
        'udf2' => '',
        'udf3' => ''
    );*/

    public $order_id = '00000000';


    public $header = null;

    public function __construct()
    {
        $this->header = array(
            'Authorization: Bearer '.$this->Token,
        );

        $this->baseurl = $this->baseUrls[$this->env];
    }

    function setOrder($arr)
    {
        $this->order = $arr;
        return true;
    }

    function setOrderId($order_id)
    {
        $this->order_id = $order_id;
        return true;
    }


    function create_header($token)
    {
        $headers = $this->header;
        if(!is_null($token)) {
            array_push($headers, 'Authorization: Bearer '.$token);
        }
        return $headers;
    }

    function post_helper($action, $data, $token=null)
    {
        $finalUrl = $this->baseurl.$this->urls[$action];
        $headers = $this->create_header($token);
        $curl = curl_init();
        /*
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.bharatpays.in/api/upi_gateway/create_order',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('customer_name' => 'parikshit','customer_email' => 'parikshitvaghasiya343@gmail.com','customer_mobile' => '7203964072','redirect_url' => 'https://google.com','p_info' => 'Product Name','amount' => '1','udf1' => '','udf2' => '','udf3' => ''),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer 9026bc126a49ef0d03afdae8930735df',
                'Cookie: ci_session=f8fcd70d404befbcd70b4fb879c1ad7348ca5b1e'
            ),
        ));
        */
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_URL, $finalUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        if(!is_null($data)) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $r = curl_exec($curl);

        if(curl_errno($curl)) {
            print('error in posting');
            print(curl_error($curl));
            die();
        }
        curl_close($curl);

        $rObj = json_decode(trim($r), true);
//            if($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') {
//                throw new Exception('incorrect response: '.$rObj['message']);
//            }
//        var_dump($rObj);
//        die;
        return $rObj;
    }

    function get_helper($finalUrl, $token)
    {
        $headers = $this->create_header($token);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $r = curl_exec($ch);

        if(curl_errno($ch)) {
//            print('error in posting');
            print_r(curl_errno($ch));
            print_r(curl_error($ch));
            die();
        }
        curl_close($ch);

        $rObj = json_decode($r, true);
//        if($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') throw new Exception('incorrect response: '.$rObj['message']);
        return $rObj;
    }

    #get auth token
    function getToken()
    {
        try {
            $response = $this->post_helper('auth', null, null);
            return $response['data']['token'];
        } catch(Exception $ex) {
            error_log('error in getting token');
            error_log($ex->getMessage());
            return false;
            // die();
        }

    }

    #Create Order
    function createOrder()
    {
        try {
            $response = $this->post_helper('createOrder', $this->order );
            return $response;
//            return true;
        }
        catch(Exception $ex) {
            $msg = $ex->getMessage();
            // if(strstr($msg, 'Beneficiary does not exist')) return false;
            error_log('error in Create Order');
            error_log($msg);
            return $msg;
//            die();
        }
    }



#get Order status
    function getOrderStatus($token = null)
    {
        try {
            $postData = array('order_id' => $this->order_id);
            $response = $this->post_helper('getOrderStatus', $postData);
            error_log(json_encode($response));
            return $response;
        }
        catch(Exception $ex) {
            $msg = $ex->getMessage();
            error_log('error in getting transfer status');
            error_log($msg);
            return $msg;
//            die();
        }
    }
#main execution
//$token = getToken();
//if(!getBeneficiary($token)) addBeneficiary($token);
//requestTransfer($token);
//getTransferStatus($token);

}