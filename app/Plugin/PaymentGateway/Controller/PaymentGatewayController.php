<?php

class PaymentGatewayController extends PaymentGatewayAppController {
	
	public $successUrl = array("action"=>"payment_success");
	public $failureUrl = array("action"=>"payment_failed");
	public $cancelledUrl = array();
	public $pendingUrl = array();
	
	public function beforeFilter() {

        $this->Security->unlockedActions[] = "callback";
        $this->Auth->allow("forward", "callback", "payment_failed", "payment_success");

	}
	
	private function prepaymentCheck($transactionData) {
		$transactionClass = (Configure::read("PaymentGateway.invoiceModel"));
        $this->loadModel($transactionClass);
		if(!$transactionData[$transactionClass]['amount']>0) {
			throw new Exception(__("This invoice is already paid or this invoice has an invalid amount"));
		}
	}
	
	public function forward($gateway, $transactionId, $pay_req = null) {

		$this->loadModel('BookedSlot');
		
		if (!empty($pay_req)) {
			$locked = $this->BookedSlot->find('all', array(
				'conditions' => array(
					'BookedSlot.created >= ' => date('Y-m-d H:i:s', strtotime('-20 mins')),
					'BookedSlot.locked'=>1,
					'BookedSlot.booking_id' => $transactionId
				),
				'recursive' => -1
			));
			// echo '<pre>';
			// print_r(date('Y-m-d H:i:s', strtotime('-20 mins')));
			// exit;

			if (empty($locked)) { // if locked found then redirect
				throw new NotFoundException(__("Sorry, url has expired!"));
			}
		}
			
		$transactionClass = (Configure::read("PaymentGateway.invoiceModel"));
		$this->loadModel($transactionClass);
	
		$this->$transactionClass->recursive = 1;
		$transactionData = $this->$transactionClass->read(null, $transactionId);

		$gatewaysAvailable = Configure::read("PaymentGateway.gateways");
	
		if(!isset($gatewaysAvailable[$gateway])) {
			throw new NotFoundException(__("Invalid gateway chosen"));
		}
	
		$gatewayComponent = $gatewaysAvailable[$gateway];

		$this->loadModel("TransactionAttempt");
		$attemptData['TransactionAttempt'] = array(
				'invoice_id'=>$transactionId,
				'start_time'=>date("Y-m-d H:i:s"),
				'status'=>'Pending',
				'pg_type'=>$gateway,
				'amount'=>$transactionData[$transactionClass]['amount'],
				'currency_code'=>"INR"
				);
		if($this->TransactionAttempt->save($attemptData))
			$attemptId = $this->TransactionAttempt->getLastInsertID();
		else
			throw new Exception(__("Could not log the transaction attempt"));

        $this->Session->write("Auth.Transaction.id", $attemptId);
        $this->Session->write("Auth.OrderHash.id", Security::hash($transactionId, "sha1", true));
		return $this->$gatewayComponent->forward($transactionData, $attemptId);
	}
	
	public function callback($gateway, $attemptId=null) {
		$this->autoRender = false;
        if($attemptId==null) {
            $attemptId = $this->Session->read("Auth.Transaction.id");
        }

        if(empty($attemptId)) {
            throw new ForbiddenException(__("We have a security exception. If you feel this is an error, please contact our admin"));
        }
        $this->Session->delete("Auth.Transaction.id");

		$transactionClass = (Configure::read("PaymentGateway.invoiceModel"));
		$this->loadModel($transactionClass);
		$this->loadModel("TransactionAttempt");

		$this->TransactionAttempt->getDataSource()->begin();
		
		// TransactionAttempt class is inside plugin but Invoice class is in main app, so association does not work for some reason.
		$this->TransactionAttempt->recursive = -1;
		$this->$transactionClass->recursive = 0;
		$transactionData = $this->TransactionAttempt->read(null, $attemptId);
		$transactionData = array_merge($transactionData, $this->$transactionClass->read(null, $transactionData['TransactionAttempt']['invoice_id']));

        if(Security::hash($transactionData['TransactionAttempt']['invoice_id'], "sha1", true) != $this->Session->read("Auth.OrderHash.id")) {
            throw new ForbiddenException(__("Unable to process the checkout due to security token mismatch"));
        }
        $this->Session->delete("Auth.OrderHash.id");

        if($transactionData['TransactionAttempt']['result']==="1" || $transactionData['TransactionAttempt']['result']==="0" ) {
            throw new Exception("This transaction has ended already. If you feel there is a problem, please contact our admins");
        }

		$gatewaysAvailable = Configure::read("PaymentGateway.gateways");
		
		if(!isset($gatewaysAvailable[$gateway])) {
			throw new NotFoundException(__("Invalid gateway chosen"));
		}
		
		$gatewayComponent = $gatewaysAvailable[$gateway];
		try {
			$feedback = $this->$gatewayComponent->callback($transactionData);
		} catch(Exception $e) {
			$this->Session->setFlash($e->getMessage());
			echo ($e->getMessage());
			exit;
			//return $this->redirect(array('action'=>'payment_failed'));
		}

		if($feedback['status'] && !$feedback['pending']) {
			$status = "Success";
		} else if(!$feedback['status']) {
			$status = "Failure";
		} else {
			$status = "Pending";
		}
        $statusMessage = $feedback['status_message'];
		
		if($status!="Pending") {
			$transactionUpdate['TransactionAttempt'] = array(
					"status"=>$status,
					"pg_identifier"=>$feedback['pg_identifier'],
					"end_time"=>date("Y-m-d H:i:s"),
					"result"=>($status=="Failure")?"0":"1"
					);
			
			$this->TransactionAttempt->save($transactionUpdate);
			
			if($status=="Success") {
				/*$this->$transactionClass->id = $transactionData['TransactionAttempt']['invoice_id'];
				$this->$transactionClass->updateAll(array("cart_complete"=>1), array("{$transactionClass}.id"=>$this->$transactionClass->id));*/
				
				if(method_exists($this->$transactionClass, "onSuccessfulPayment")) {
					$this->$transactionClass->onSuccessfulPayment($attemptId, $transactionData['TransactionAttempt']['invoice_id'], $feedback);
				}
				
				$this->TransactionAttempt->getDataSource()->commit();
				
				//mails
				

                if(!is_array($this->successUrl)) {
                    $parsed = Router::parse($this->successUrl);
                    $parsed["invoiceid"] = $transactionData['TransactionAttempt']['invoice_id'];
                    $this->successUrl = $parsed;
                } else {
                    $this->successUrl = array_merge($this->successUrl,array("invoiceid"=>$transactionData['TransactionAttempt']['invoice_id']));
                }
				$this->redirect($this->successUrl);
			} else {
				
				if(method_exists($this->$transactionClass, "onFailedPayment")) {
					$this->$transactionClass->onFailedPayment($attemptId, $transactionData['TransactionAttempt']['invoice_id'], $feedback);
				}
				
				$this->TransactionAttempt->getDataSource()->commit();

                if(!is_array($this->failureUrl)) {
                    $parsed = Router::parse($this->failureUrl);
                    $parsed["invoiceid"] = $transactionData['TransactionAttempt']['invoice_id'];
                    $parsed['feedback'] = $feedback;
                    $this->failureUrl = $parsed;
                } else {
                    $this->failureUrl = array_merge($this->failureUrl,array("invoiceid"=>$transactionData['TransactionAttempt']['invoice_id'], "feedback"=>$feedback));
                }
				$this->redirect($this->failureUrl);
			}
		} else {
			$this->TransactionAttempt->getDataSource()->commit();
			$this->redirect($this->pendingUrl);
		}
	}
	
	public function payment_success() {
        if(!empty($this->request->params['named']['invoiceid']))
		    $this->redirect(array("controller"=>"bookings", "action"=>"payment_status", "plugin"=>false, 1));
	}
	
	public function payment_failed($invoiceId=null) {
		$this->redirect(array("controller"=>"bookings", "action"=>"payment_status", "plugin"=>false, 0));
	}
	
}
