<?php
class SMSResponse
{
	private $message;
	private $code;
	
	function __construct()
	{
		$this->message = "";
		$this->code = "";
	}
	
	public function setCode($code)
	{
		$this->code = $code;
	}
	
	public function setMessage($message)
	{
		$this->message = $message;
	}
	
	public function getCode()
	{
		return $this->code;
	}
	
	public function getMessage()
	{
		return $this->message;
	}
}

class SMSHelper
{
	const TYPE_A = 'A';
	const TYPE_B = 'B';
	const TYPE_C = 'C';
	const TYPE_D = 'D';
	const TYPE_E = 'E';
	
	const CALLBACK_INACTIVE				= 0;
	const CALLBACK_FINAL_STATUS			= 1;
	const CALLBACK_INTERMEDIARY_STATUS	= 2;
	
	private $timeout = 20;
	private $account;
	private $password;
	
	function __construct($account, $password)
	{
		$this->account  = $account;
        $this->password = $password;
	}
	
	public function query($idList)
	{
		$ids = implode(";", $idList);
		
		$params = array(
			"dispatch"			=> "checkMultiple",
			"account"			=> $this->account,
			"code"				=> $this->password,
			"idList"			=> $ids
		);
		
		return $this->post($this->parse($params));
	}
	
	public function send($type, $msgList, $callbackOption = self::CALLBACK_INACTIVE)
	{
		$params = array(
			"dispatch"			=> "sendMultiple",
			"account"			=> $this->account,
			"code"				=> $this->password,
			"type"				=> $type,
			"callbackOption"	=> $callbackOption,
			"list"				=> $msgList
		);
		
		return $this->post($this->parse($params));
	}
	
	private function parse($params)
	{
		$postdata = "";
		
		foreach ($params as $key => $value)
		{
			if (trim($postdata) != "")
			{
				$postdata .= "&";
			}
			
			$postdata .= $key . "=" . $value;
		}
		
		return $postdata;
	}
	
	private function post($data)
	{
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, "https://system.human.com.br/GatewayIntegration/msgSms.do");
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		$result = trim(curl_exec($curl));
		
		curl_close($curl);
		
		return $this->response($result);
	}
	
	private function response($content)
	{
		$result		= array();
		$content	= explode("\n", $content);
		
		foreach ($content as $cont)
		{
			$smsresponse = new SMSResponse();
			
			$contAux = explode(" - ", $cont);
			
			$smsresponse->setCode($contAux[0]);
			$smsresponse->setMessage($contAux[1]);
			
			array_push($result, $smsresponse);
		}
		
		return $result;
	}
}
?>