<?php 
App::uses('HttpSocket', 'Network/Http');
App::uses("PayULib", 'Plugin/PaymentGateway/Vendor');

/**
 * Class PaypalComponent
 * @property PayULib $lib
 */
class PayuComponent extends Component {

	protected $_controller = null;
    protected $config = null;
    /**
     * @var null
     */
    protected $lib = null;
	public $components = array("Session");
	
	
	/**
	 * Start up, gets an instance on the controller class (needed for redirect) sets 
	 * the config (live or sandbox) and sets the users IP 
	 *
	 * @return void
	 **/
	public function initialize(Controller $controller) {
		$this->_controller = $controller;
        $this->config = Configure::read("PayU.config");
        $this->lib = new PayULib($this->config);
	}

    public function isConfigured() {
        $mandatoryConfigs = array("merchant_key", "salt", "hash_method");
        foreach($mandatoryConfigs as $singleConfig) {
            if(empty($this->config[$singleConfig]))
                return false;
            return true;
        }
    }
	
	public function forward($transactionData, $attemptId) {
		if(!$this->isConfigured()) {
				
			$this->Session->setFlash(__("PayU has not been configured. Please contact the admins"));
			$this->_controller->redirect($this->_controller->referer());
		}
		
		$transactionClass = Configure::read("PaymentGateway.invoiceModel");
		
        $userClass = Configure::read("PaymentGateway.userModel");
	
        $orderData = array(
            "transaction_id"=>$transactionData[$transactionClass]['id'],
            "buyer_email"=>$transactionData[$userClass]['email'],
            "amount"=>floatval(round($transactionData[$transactionClass]['amount'], 2)),
            "currency"=>Configure::read("PaymentGateway.defaultCurrency"),
            "buyer_id"=>$transactionData[$userClass]['id'],
            "buyer_name"=>(!empty($transactionData[$userClass]['display_name']))?$transactionData[$userClass]['display_name']:explode('@',$transactionData[$userClass]['username'])[0],
            "buyer_phone"=>$transactionData[$userClass]['phone']
        );

		$this->lib->setSuccessCallbackUrl(Router::url(array("plugin"=>"payment_gateway", "controller"=>"payment_gateway", "action"=>"callback", "payu", $attemptId), true));
        $this->lib->setFailureCallbackUrl(Router::url(array("plugin"=>"payment_gateway", "controller"=>"payment_gateway", "action"=>"callback", "payu", $attemptId), true));

		$requestURL = $this->lib->initiateRequest($orderData);
		$this->_controller->set(compact('requestURL'));
		$this->_controller->layout = false;
		$this->_controller->render('redirection');
		
	}
	
	public function callback($transactionData) {
			$transactionClass = (Configure::read("PaymentGateway.invoiceModel"));
            $userClass = Configure::read("PaymentGateway.userModel");

//		    $responseArray = $this->_controller->request->query;
            $responseArray = $this->_controller->request->data;
            if(empty($responseArray)) {
                $responseArray = $this->_controller->request->query;
            }
            $this->log("Payu response received at ".date("Y-m-d H:i:s")." : ".http_build_query($responseArray), "payu_logs");

			if(!$this->callbackSecurity($responseArray, $transactionData)) {
                $this->log("Transaction failed due to security error. Payu ID: ".$responseArray['mihpayid'].", Cart ID: ".$transactionData[$transactionClass]['id']);
				throw new Exception(__("Security Error: There was a mismatch of details"));
			}

		    $resultMessage = $this->lib->responseDecode($responseArray);

			$feedback = array();
			$feedback['pg_identifier'] = $resultMessage['data']['mihpayid'];
			if($resultMessage['result']!="SUCCESS")
				$feedback['status'] = 0;
			else {
				$feedback['status'] = 1;
				
				if($resultMessage['result']=="SUCCESS") {
					$feedback['pending'] = 0;
				} else {
					$feedback['pending'] = 1;
				}
				
				
			}
            $feedback['status_message'] = $resultMessage['result_message'];

			return $feedback;
		
	}
	
	private function callbackSecurity($transDetails, $invoiceDetails) {
		$transactionClass = (Configure::read("PaymentGateway.invoiceModel"));
		if($transDetails['txnid']!=$invoiceDetails[$transactionClass]['id']) {
            $this->log("payu transaction with ID: ".$transDetails['mihpayid']." was discarded on security grounds as it did not equal internal cart id", "payu_logs");
			return false;
        }

		if($transDetails['amount']!="".round($invoiceDetails[$transactionClass]['amount'],2)) {
            $this->log("payu transaction with ID: ".$transDetails['mihpayid']." was discarded on security grounds since amounts were not equal", "payu_logs");
            return false;
        }
		return true;
	}
}
