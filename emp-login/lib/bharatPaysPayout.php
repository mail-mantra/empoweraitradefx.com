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

class bharatPaysPayout
{
    #default parameters
    private $Token = BH_PAY_TOKEN;
    private $env = 'prod';

    #config objs
    private $baseUrls = array(
        'prod' => 'https://api.bharatpays.in',
        'test' => 'https://api.bharatpays.in',
    );
    private $urls = array(
        'createPayoutAccount' => '/api/va/create_payout_account?',
        'createPayout' => '/api/va/create_payout?',
    );

    public $beneficiary = [
        // 'token'=>'9026bc126a49ef0d03afdae8930735df',
        'bene_account_number' => '',
        'ifsc_code' => '',
        'recepient_name' => '',
        'email_id' => 'test',
        'mobile_number' => '',
    ];

    public $payout = [
        // 'token'=>'9026bc126a49ef0d03afdae8930735df',
        'amount' => '1',
        'purpose' => 'test',
        'ref_id' => '123456', // NUMERIC ONLY
        'txn_type' => 'IMPS', // IMPS, NEFT
        'bank_account_id' => '136',
    ];

    public $header = null;
    private string $baseurl;

    public function __construct()
    {
        $this->header = array(
            "Content-type: application/json",
//            'Authorization: Bearer '.$this->Token,
        );
        $baseUrlArray = $this->baseUrls;

        $this->baseurl = $baseUrlArray[$this->env];

    }

    function setBeneficiary($arr)
    {
//        unset($arr['token']);
//        $arr = array_merge(array("token"=> $this->Token), $arr);
        $this->beneficiary = $arr;
        return true;
    }

    function getBeneficiary()
    {
        return $this->beneficiary;
    }

    function setPayout($arr)
    {
//        unset($arr['token']);
//        $arr = array_merge(array("token"=> $this->Token), $arr);
        $this->payout = $arr;
        return true;
    }

    function getPayout()
    {
        return $this->payout;
    }

    function create_header($token = null)
    {
        $headers = $this->header;
        if(!is_null($token)) {
            array_push($headers, 'Authorization: Bearer ' . $token);
        }
        return $headers;
    }

    function post_helper($action, $data, $token = null)
    {
        $finalUrl = $this->baseurl . $this->urls[$action];
        $headers = $this->create_header($token);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $finalUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        if(!is_null($data)) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        $json_response = curl_exec($curl);
        if(curl_errno($curl)) {
            print('error in posting');
            print(curl_error($curl));
            die();
        }
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if($status != 200) {
            // You can handle Error yourself.
            // print_r($headers);
            print_r($data);
            die("Error: call to URL $finalUrl failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        curl_close($curl);

        $rObj = json_decode(trim($json_response), true);
//            if($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') {
//                throw new Exception('incorrect response: '.$rObj['message']);
//            }
//        var_dump($rObj);
//        die;
        return $rObj;
    }

    function get_helper($finalUrl, $token = null)
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


    #Create Payout Account
    function createPayoutAccount()
    {
        try {
            $token_arr = array("token" => $this->Token);
            $account_data = array_merge($token_arr, $this->beneficiary);
            $finalUrl = $this->baseurl . $this->urls['createPayoutAccount'] . http_build_query($account_data);
            return $response = $this->get_helper($finalUrl);
            // return true;
        }
        catch(Exception $ex) {
            $msg = $ex->getMessage();
            if(strstr($msg, 'Beneficiary does not exist')) return false;
            error_log('error in getting beneficiary details');
            error_log($msg);
            return $msg;
            // die();
        }
    }

    #Create Payout
    function createPayout()
    {
        try {
            $token_arr = array("token" => $this->Token);
            $payout_data = array_merge($token_arr, $this->payout);
            $finalUrl = $this->baseurl . $this->urls['createPayout'] . http_build_query($payout_data);
            return $response = $this->get_helper($finalUrl);
            // return true;
        }
        catch(Exception $ex) {
            $msg = $ex->getMessage();
            if(strstr($msg, 'Beneficiary does not exist')) return false;
            error_log('error in getting beneficiary details');
            error_log($msg);
            return $msg;
            // die();
        }
    }




#main execution
//$token = getToken();
//if(!getBeneficiary($token)) addBeneficiary($token);
//requestTransfer($token);
//getTransferStatus($token);

}