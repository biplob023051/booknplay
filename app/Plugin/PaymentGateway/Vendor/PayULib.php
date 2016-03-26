<?php

class PayULib {
	
	public static $REQUEST_URL = "";
	public static $SUCCESS_URL = "";
    public static $FAILED_URL = "";
	public static $TRANSACTION_TYPE = "SALE";
	public static $PAYMENT_METHOD = "CREDIT";
	
	public static $STATUS_FAILED = "FAILED";
	public static $STATUS_SUCCESS = "SUCCESS";
	public static $STATUS_PENDING = "PENDING";

    protected $config;

    public function __construct($config) {
        $this->config = $config;
        if($this->config['test_mode']) {
            self::$REQUEST_URL = $this->config['test_endpoint'];
        } else {
            self::$REQUEST_URL = $this->config['endpoint'];
        }
    }

    public function setSuccessCallbackUrl($url) {
        self::$SUCCESS_URL = $url;
    }

    public function setFailureCallbackUrl($url) {
        self::$FAILED_URL = $url;
    }
	
	//Modify this function based on the platform
	public function getConfig($type=false) {
		if(!$type)
			return $this->config;
		else if(isset($this->config[$type]))
			return $this->config[$type];
		else 
			return false;
	}

    private function getKeyValue($data) {
        $returnArray = array();

        $returnArray[] = empty($data['key'])?"":$data['key'];
        $returnArray[] = empty($data['txnid'])?"":$data['txnid'];
        $returnArray[] = empty($data['amount'])?"":$data['amount'];
        $returnArray[] = empty($data['productinfo'])?"":$data['productinfo'];
        $returnArray[] = empty($data['firstname'])?"":$data['firstname'];
        $returnArray[] = empty($data['email'])?"":$data['email'];
        $returnArray[] = empty($data['udf1'])?"":$data['udf1'];
        $returnArray[] = empty($data['udf2'])?"":$data['udf2'];
        $returnArray[] = empty($data['udf3'])?"":$data['udf3'];
        $returnArray[] = empty($data['udf4'])?"":$data['udf4'];
        $returnArray[] = empty($data['udf5'])?"":$data['udf5'];
        $returnArray[] = "";
        $returnArray[] = "";
        $returnArray[] = "";
        $returnArray[] = "";
        $returnArray[] = "";
        $returnArray[] = $data['salt'];

        return $returnArray;
    }

    private function getKeyValueForResponse($data) {
        $returnArray = array();

        $returnArray[] = $this->getConfig("salt");
        $returnArray[] = $data['status'];
        $returnArray[] = "";
        $returnArray[] = "";
        $returnArray[] = "";
        $returnArray[] = "";
        $returnArray[] = "";
        $returnArray[] = empty($data['udf5'])?"":$data['udf5'];
        $returnArray[] = empty($data['udf4'])?"":$data['udf4'];
        $returnArray[] = empty($data['udf3'])?"":$data['udf3'];
        $returnArray[] = empty($data['udf2'])?"":$data['udf2'];
        $returnArray[] = empty($data['udf1'])?"":$data['udf1'];
        $returnArray[] = empty($data['email'])?"":$data['email'];
        $returnArray[] = empty($data['firstname'])?"":$data['firstname'];
        $returnArray[] = empty($data['productinfo'])?"":$data['productinfo'];
        $returnArray[] = empty($data['amount'])?"":$data['amount'];
        $returnArray[] = empty($data['txnid'])?"":$data['txnid'];
        $returnArray[] = empty($data['key'])?"":$data['key'];

        return $returnArray;
    }
private function getKeyValueForVerifyPament($data){
	 $returnArray = array();

        $returnArray[] = empty($data['key'])?"":$data['key'];
        $returnArray[] = empty($data['command'])?"":$data['command'];
        $returnArray[] = empty($data['var1'])?"":$data['var1'];
        $returnArray[] = $data['salt'];
	
	return $returnArray;

}
    public function generateHash($data, $useResponseSequence = false) {
		
        $data['salt'] = empty($data['salt'])?$this->getConfig("salt"):$data['salt'];
        $hash_method = $this->getConfig("hash_method");

        $data['hash_method'] = "SHA512";

        if(!$useResponseSequence)
            $dataAsArray = $this->getKeyValue($data);
        else
            $dataAsArray = $this->getKeyValueForResponse($data);
	if(isset($data['command'])){

		$dataAsArray = $this->getKeyValueForVerifyPament($data);
	}
        $dataAsString = implode("|",$dataAsArray);
        return hash($data['hash_method'], $dataAsString);

    }

public function verify_payment($transactionId){
			
			if(is_array($transactionId)){
				$transactionId = implode("|",$transactionId);
				
			}
			
			$this->config = Configure::read("PayU.config");
        
			$PayuConfig=Configure::read("PayU.config");
			
			
			$data =array('key'=>$PayuConfig['merchant_key'],'command'=>"verify_payment",'var1'=>$transactionId);
			$resultdata=$this->generateHash($data);
		
		$data = http_build_query(array('key' => $PayuConfig['merchant_key'], 'command' => "verify_payment",'hash'=>$resultdata,'var1' => $transactionId));
			
			$cSession = curl_init();
			curl_setopt($cSession,CURLOPT_URL,"https://test.payu.in/merchant/postservice"); 
			curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($cSession,CURLOPT_HEADER,false);
			curl_setopt($cSession,CURLOPT_POSTFIELDS,$data);
			$result=curl_exec($cSession);
			curl_close($cSession);
			$unserializedata=array();
			$unserializedata=unserialize($result);
			
			return $unserializedata;
	}

public function refund_payment($pgIdentifier,$refundId,$amount){
		
		$this->config = Configure::read("PayU.config");
        
			$PayuConfig=Configure::read("PayU.config");
			
			$this->lib = new PayULib($this->config);
			$data =array('key'=>$PayuConfig['merchant_key'],'command'=>"cancel_refund_transaction",'var1'=>$pgIdentifier);
			$resultdata=$this->lib->generateHash($data);
		
		$data = http_build_query(array('key' => $PayuConfig['merchant_key'], 'command' => "cancel_refund_transaction",'hash'=>$resultdata,'var1'=>$pgIdentifier,'var2'=>$refundId,'var3'=>$amount));
			
			$cSession = curl_init();
			curl_setopt($cSession,CURLOPT_URL,"https://test.payu.in/merchant/postservice"); 
			curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($cSession,CURLOPT_HEADER,false);
			curl_setopt($cSession,CURLOPT_POSTFIELDS,$data);
			$result=curl_exec($cSession);
			curl_close($cSession);
		
			$unserializedata=unserialize($result);
			return $unserializedata;
}
    
    /*  
     * orderData => transaction_id, buyer_email, 'buyer_id, amount in paise, currency; [OPTIONAL*] shipping_address, billing_address, billing_name
     * */
    public function initiateRequest($orderData) {
    	$merchantId = $this->getConfig("merchant_key");
    	$secret = $this->getConfig("salt");

    	$getParams = array(
    			"key"=>$merchantId,
    			"txnid"=>trim($orderData['transaction_id']),
    			"email"=>trim($orderData['buyer_email']),
    			"amount"=>$orderData['amount'],
    			"surl"=>self::$SUCCESS_URL,
                "furl"=>self::$FAILED_URL,
                "productinfo"=>"Order ID: ".$orderData['transaction_id'],
                "firstname"=>trim($orderData['buyer_name']),
                "phone"=>trim($orderData['buyer_phone']),
    			"service_provider"=>"payu_paisa"
                //Optionals ignored
    			);

        /*if(!empty($orderData['payment_method'])) {
            $getParams['payment_method'] = $orderData['payment_method'];
            if($orderData['payment_method']=="NET") {
                if(!empty($orderData['bank_name'])) {
                    $getParams['bank_name'] = $orderData['bank_name'];
                }
            }
        }*/
    	
    	/*if(!empty($orderData['buyer_id']))
    		$getParams['buyer_unique_id'] = $orderData['buyer_id'];*/

        /*if(!empty($orderData['shipping_address'])) {
            $getParams['shipping_address'] = $orderData['shipping_address']['address'];
            $getParams['shipping_city'] = $orderData['shipping_address']['city'];
            $getParams['shipping_state'] = $orderData['shipping_address']['state'];
            $getParams['shipping_zip'] = $orderData['shipping_address']['zip'];
            $getParams['shipping_country'] = $orderData['shipping_address']['country'];
        }*/

        if(!empty($orderData['billing_address'])) {
            $getParams['address1'] = $orderData['billing_address']['address_line1'];
            $getParams['address2'] = $orderData['billing_address']['address_line2'];
            $getParams['city'] = $orderData['billing_address']['city'];
            $getParams['state'] = $orderData['billing_address']['state'];
            $getParams['zipcode'] = $orderData['billing_address']['zip'];
            $getParams['country'] = $orderData['billing_address']['country_id'];
        }

        /*if(!empty($orderData['billing_name']))
            $getParams['billing_name'] = $orderData['billing_name'];*/

    	$hash = $this->generateHash($getParams);
    	$getParams['hash'] = $hash;
    	//var_dump($getParams);die();
    	$requestUrl = array();
    	$requestUrl[0] = self::$REQUEST_URL;
    	$requestUrl[1] = $getParams;
    	return $requestUrl;
    	
    }
    
    public function responseDecode($responseArray) {
    	//Responses should be saved in database prior to coming into this function
    	
    	$returnedHash = $responseArray['hash'];
    	$hashMethod = $this->getConfig("hash_method");
    	
    	$result = ""; $resultMsg = ""; $data = null; $initiateRefund = false;
    	
        //Verification of hash
        unset($responseArray['hash']);
        $calculatedHash = $this->generateHash($responseArray, true);
        $responseArray['hash']=$returnedHash;
        if($calculatedHash!=$returnedHash) {
            $result = self::$STATUS_FAILED;
            $resultMsg = "Returned hash and internally calculated hashes do not match";
            $data = $responseArray;
            if ($responseArray['transaction_status']!=self::$STATUS_FAILED) {
                $initiateRefund = true;
            }
        } else {
            //Verifying the values of internal values
            $merchantId = $this->getConfig("merchant_key");

            if($merchantId!=$responseArray['key']) {
                $result = self::$STATUS_FAILED;
                $resultMsg = "Merchant key is wrong";
                $data = $responseArray;
                if (strtoupper($responseArray['status'])!=self::$STATUS_FAILED) {
                    $initiateRefund = true;
                }
            } else {

                //Further security checks to be done in the wrapping class:
                //IP check, validity of order id, verification of amount

                //Checking whether transaction is successfull
                $result = $responseArray['status'];
                if($result!="success") {
                    $resultMsg = $responseArray['error'].": ".$responseArray['error_Message'];
                } else {
                    $result = self::$STATUS_SUCCESS;
                }
                $data = $responseArray;
            }

        }

    	return array(
    			"result"=>$result,
    			"result_message"=>$resultMsg,
    			"data"=>$data,
    			"initiate_refund"=>$initiateRefund
    			);
    	
    }

}
