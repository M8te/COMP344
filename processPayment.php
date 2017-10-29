<?php

require_once('config.php');

global $debug;
$debug = false;

class SecurePayTransaction {
	private $merchantid;
	private $merchantpw;
	private $endpoint;
	private $timeout;
	private $apiversion;
	private $timezone;
	private $txnsource;
	private $requestDOM;
	private $responseDOM;
	private $lastResponseCode;
	private $lastResponseText;
	
	function __construct() {
		
		$this->requestDOM = new DOMDocument('1.0', 'UTF-8');
		// config.php variables
		
		global $securePayConfig;	
		//service credentials	
		$this->merchantid = $securePayConfig['merchantID'];
		$this->merchantpw = $securePayConfig['merchantPW'];
		$this->timeout = $securePayConfig ['timeout'];
		$this->apiversion = $securePayConfig['apiversion'];
		$this->timezone = $securePayConfig['timezone'];
		$this->txnsource = $securePayConfig['txnsource'];
		
		//service url
		$this->endpoint = $securePayConfig['endpoint'];
		
	}
	
	static function getGMTtimeStamp($zone)
	{
		/* 
		 * Return encoded DateTime in format YYYYDDMMHHNNSSKKK000sOOO
		 */
		
		$fillforformat = "000000"; // value to append to the datetime to meet securePay format requirement.
		
				$stamp = date("YdmGis") . $fillforformat . $zone;
		return urldecode($stamp);
	}
	
	
	function add_element($parent, $child, $text) {
		
		/* function to add child elements to DOM
		 * Parameters:
		 * $parent = XML structure to which element will be appended
		 * $child = element being created and appended
		 * $value = value of child element being added
		 */
		
		$temp = $this->requestDOM->createElement($child);
		$child_element = $parent->appendChild($this->requestDOM->createElement($child));
		$child_element->appendChild(new DOMText($text));
		return $child_element;
	}
	
	function approve($amount, $requesttype, $transactiontype, $orderid, $currency, $preauthid, $cardnumber, $cvv, $expiry) {
		
		/*
		 * Function sends request to SecurePay to process payment. 
		 * Parameters:
		 * $amount = transaction amount (mandatory)
		 * $requesttype = type of request being sent to SecurePay (optional, default is 'Payment')
		 * $transactiontype = the of financial transaction processed by SecurePay (optional, default is 'Standard Payment')
		 * $orderid = id of order being paid. used in purchaseOrderNo field of securePay request (mandatory)
		 * $currency = currency of transaction (optional, default is Australian Dollar)
		 * $preauthid = pre-authorization id (optional, no default)
		 * $cardnumber = number of card  being charged (mandatory)
		 * $ccv = ccv of card being charged (mandatory)
		 * $expiry = date card expires (mandatory)
		 */
		
		//used for debug messages.
		global $debug;
		
		//SecurePay response codes and descriptions
		require_once('processPaymentResponse.php');
		
		//initialize max number of payments - restricted to one by securepay.
		$txncount = 1;
		
		//set request type default to Payment
		if (!$requesttype)
			$requesttype = "Payment";
		
		//set transaction type default to Standard Payment
		if (!$transactiontype)
			$transactiontype = "0";
		
		//set currentcy default to AUD
		if (!$currency)
			$currency = "AUD";
			
		// generate unique transaction identifier with orderid prefixed
		$id = uniqid($orderid,true);
		
		if ($debug)
			echo "uniqueID = $id <br>";
		
		// Create the root element
		$message = $this->requestDOM->appendChild($this->requestDOM->createElement('SecurePayMessage'));
		$message_info = $this->add_element($message, 'MessageInfo', null);
		$message_id = $this->add_element($message_info, 'messageID', $id);
		//encode time stamp.
		$message_timestamp = $this->add_element($message_info, 'messageTimestamp', $this->getGMTTimeStamp($this->timezone));
		$message_timeout = $this->add_element($message_info, 'timeoutValue', $this->timeout);
		$api_version = $this->add_element($message_info, 'apiVersion', $this->apiversion);
		
		// Now MerchantInfo
		$merchant_info = $this->add_element($message, 'MerchantInfo', null);
		$merchant_id = $this->add_element($merchant_info, 'merchantID', $this->merchantid);
		$merchant_password = $this->add_element($merchant_info, 'password', $this->merchantpw);
		
		// set the payment request type.
		$request_type = $this->add_element($message, 'RequestType', $requesttype);
		
		// build payment nodes
		$payment = $this->add_element($message, 'Payment', null);
		$txn_list = $this->add_element($payment, 'TxnList', null);
		$txn_list->setAttribute('count', $txncount);
		
		//iterate for payments
		for($i = 1; $i <= $txncount; $i++) {
			$txn = $this->add_element($txn_list, 'Txn', null);
			$txn->setAttribute('ID', $i);
			$txn_type = $this->add_element($txn, 'txnType', $transactiontype);
			$txn_source = $this->add_element($txn, 'txnSource', $this->txnsource);
			$txn_amount = $this->add_element($txn, 'amount', $amount);
			$txn_currency = $this->add_element($txn, 'currency', $currency);
			$txn_ponum = $this->add_element($txn, 'purchaseOrderNo',$orderid);
			$txn_id = $this->add_element($txn, 'txnID', "");
			$txn_preauth_id = $this->add_element($txn, 'preauthid', $preauthid);
			$cci = $this->add_element($txn, 'CreditCardInfo', null);
			$card_number = $this->add_element($cci, 'cardNumber', $cardnumber);
			$card_cvv = $this->add_element($cci, 'cvv', $cvv);
			$card_expiry = $this->add_element($cci, 'expiryDate', $expiry);
		}
		
		//save xml
		$xmlquery = $this->requestDOM->saveXML();
		
		if ($debug)
			echo htmlentities($xmlquery) . "<br/>";
			
			//tranmission of xml 
			
			$host = $this->endpoint;
			$c = curl_init($host);
			curl_setopt($c, CURLOPT_POST, 1);
			curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($c, CURLOPT_HTTPHEADER, array(
					'Content-type' => 'application/x-www-form-urlencoded\r\n',
					'Content-length' => strlen($xmlquery),
					'Connection' => 'close'
			)
					);
			curl_setopt($c,CURLOPT_POSTFIELDS, $xmlquery);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			
			//response handling
			
			$response = curl_exec($c);
			
			if ($response == false) {
				die("CURL Transfer failed; error: " . curl_error($c));
			}
			
			if ($debug) {
				echo "<br/><br/>";
				echo "And here is the response:<br/>";
				echo htmlentities($response);
			}
			
			$this->responseDOM = new DOMDocument('1.0');
			$this->responseDOM->formatOutput = true;
			$this->responseDOM->loadXML($response);
			
			//determine response code
			$response_codes = $this->responseDOM->getElementsByTagName('responseCode');
			if ($debug) {
				echo "<br/><br/>";
				foreach ($response_codes as $response_code) {
					echo "responseCode = " . $response_code->nodeValue . "<br/>";
				}
				echo "responseCodes length = " . $response_codes->length . "<br/>";
			}
			
			if ($response_codes->length == 1) {
				$responseCode = $response_codes->item(0)->nodeValue;
				$this->lastResponseCode = $responseCode;
				$this->lastResponseText = $this->responseDOM->getElementsByTagName('responseText')->item(0)->nodeValue;
			}
			return $responseCode;
	}
	
	function getLastResponseCode() {
		return $this->lastResponseCode;
	}
	
	function getLastResponseText() {
		return $this->lastResponseText;
	}
	
}	// End of SecurePayTransaction class
?>
