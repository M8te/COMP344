<?php 
require_once('config.php');

global $debug;
$debug = false;

class CalculateShipment {
	private $apikey;
	private $host;
	private $endpoint;
	private $jsonresponse;
	private $deliverytimemessage;
	private $totalshipmentcost;
	
	
	function __construct() {
		
		$this->jsonresponse = [];
		// config.php variables
		
		global $shipmentConfig;
		//service credentials
		$this->apikey = $shipmentConfig['apikey'];
		$this->host = $shipmentConfig['host'];

		
	}
	
	function determinecosts($frompostcode, $topostcode, $lenghtCM, $widthCM, $heighCM, $weightKG, $serviceType, $countrycode) 
	{
		
		global $debug;
		global $shipmentConfig;
		
		$queryParameters = array(
				"weight" => $weightKG,
				"service_code" => $serviceType);
		
		if(!$countrycode){
			$queryParameters['from_postcode'] = $frompostcode;
			$queryParameters['to_postcode'] = $topostcode;
			$queryParameters['length'] = $lenghtCM;
			$queryParameters['width'] = $widthCM;
			$queryParameters['height'] = $heighCM;
			$this->endpoint = $shipmentConfig['domesticendpoint'];
		}
		else 
		{
			$queryParameters['country_code'] = $countrycode;
			$this->endpoint = $shipmentConfig['internationalendpoint'];
		}
			
	
	if($debug)
		print_r($queryParameters);
		
/*		
		$queryParameters = array(
				"from_postcode" => $frompostcode,
				"to_postcode" => $topostcode,
				"length" => $lenghtCM,
				"width" => $widthCM,
				"height" => $heighCM,
				"weight" => $weightKG,
				"service_code" => $serviceType
		);
*/	
		$calculateRateURL = $this->host . $this->endpoint . http_build_query($queryParameters);
		
		if($debug)
			echo "<br> URL: " . $calculateRateURL . "<br>";
		
		//initalize curl
		$ch = curl_init();
				
		//Calculate the final domestic parcel delivery price
		curl_setopt($ch, CURLOPT_URL, $calculateRateURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('AUTH-KEY: ' . $this->apikey));
		
		// transmit and store result
		$rawBody = curl_exec($ch);
		
		// if the body is empty kill and print error
		if(!$rawBody){
			die('An error has occurred: "' . curl_error($ch) . '" with code: ' . curl_errno($ch));
		}
		
		// parse json to array
		$jsonresponse = json_decode($rawBody, true);
		
		if($debug)
			//print_r($jsonresponse);
		
			if(isset($jsonresponse['postage_result']['total_cost']))
				$this->totalshipmentcost = $jsonresponse['postage_result']['total_cost'];
			
			if(isset($jsonresponse['postage_result']['delivery_time']))
				$this->deliverytimemessage = $jsonresponse['postage_result']['delivery_time'];
			//echo "TEst:" . $jsonresponse['postage_result']['total_cost'];
		
		
		return true;
	}
	
	function getDeliveryTimeMessage(){
		return $this->deliverytimemessage;
	}
	
	function getItemShipmentCost(){
		return $this->totalshipmentcost;
	}
}
	
?>