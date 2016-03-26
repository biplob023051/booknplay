<?php
App::uses('Component', 'Controller');
class SmsComponent extends Component {
	//Authentication key
	private $authKey = "7214AV8cEiyPkaq5506ca1f";
	
	//For multiple number use 9999999999,66666666666,66666666666
	public $mobileNumber = "";
	
	//Sender id
	public $senderId = "bknply";

	//Define route
	public $route = 4;
	
	//API URL
	public $url="http://sms.ssdindia.com/sendhttp.php";
	
	public function sendSms($no,$msg) {
		$message = urlencode($msg);
		$this->mobileNumber = $no;
		
		//Post Data
		$postData = array(
				'authkey' => $this->authKey,
				'mobiles' => $this->mobileNumber,
				'message' => $message,
				'sender' => $this->senderId,
				'route' => $this->route
		);
		
		if(!empty($msg) && !empty($no)){
			$ch = curl_init();
			curl_setopt_array($ch, array(
			CURLOPT_URL => $this->url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $postData
			));
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$output = curl_exec($ch);
			if(curl_errno($ch))
				return false;
			
			curl_close($ch);
		}
		return true;
	}
}
?>