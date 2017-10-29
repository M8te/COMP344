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
	
	function determinecosts($frompostcode, $topostcode, $lenghtCM, $widthCM, $heighCM, $weightKG, $servicetype, $countrycode) 
	{
		
		/*
		 * Function returns shipment costs for domestic or international shipments
		 * Parameters:
		 * $frompostcode = postcode from where item will ship (mandatory for domestic shipment)
		 * $topostcode = postcode to where item will ship  (mandatory for domestic shipment)
		 * $lenghtCM = length of item to be shipped in centimeters  (mandatory for domestic shipment)
		 * $widthCM = width of item to be shipped in centimeters  (mandatory for domestic shipment)
		 * $heightCM = height of item to be shipped in centimeters  (mandatory for domestic shipment)
		 * $weightKG = weight of item to be shipped in Kilograms (mandatory for domestic shipment)
		 * $serviceType = domestic or international type of service / delivery (mandatory for domestic and international shipment)
		 * $countrycode = country to which item will be shipped (mandatory ONLY for international shipment)
		 * 
		 */
		
		global $debug;
		global $shipmentConfig;
		
		// build URL query parameters
		$queryParameters = array(
				"weight" => $weightKG,
				"service_code" => $servicetype);
		
		// parameters for domestic shipments only 
		if(!$countrycode){
			$queryParameters['from_postcode'] = $frompostcode;
			$queryParameters['to_postcode'] = $topostcode;
			$queryParameters['length'] = $lenghtCM;
			$queryParameters['width'] = $widthCM;
			$queryParameters['height'] = $heighCM;
			
			//set domestic endpoint
			$this->endpoint = $shipmentConfig['domesticendpoint'];
		}
		else 
		{
			//parameters for international shipments only
			$queryParameters['country_code'] = $countrycode;
			
			//set international end point
			$this->endpoint = $shipmentConfig['internationalendpoint'];
		}
			
	
		if($debug)
			print_r($queryParameters);
		
		// build URL + query string
		$calculateRateURL = $this->host . $this->endpoint . http_build_query($queryParameters);
		
		if($debug)
			echo "<br> URL: " . $calculateRateURL . "<br>";
		
		//initalize curl
		$ch = curl_init();
				
		//Calculate the delivery cost
		curl_setopt($ch, CURLOPT_URL, $calculateRateURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('AUTH-KEY: ' . $this->apikey));
		
		// transmit and store result
		$rawBody = curl_exec($ch);
		
		// if the body is empty kill and print error
		if(!$rawBody){
			die('An error has occurred: "' . curl_error($ch) . '" with code: ' . curl_errno($ch));
		}
		
		// decode json into array
		$jsonresponse = json_decode($rawBody, true);
		
		if($debug)
			print_r($jsonresponse);
		
		// set total cost if found in json response
		if(isset($jsonresponse['postage_result']['total_cost']))
			$this->totalshipmentcost = $jsonresponse['postage_result']['total_cost'];
		
		// set delivery time if found in json response.
		if(isset($jsonresponse['postage_result']['delivery_time']))
			$this->deliverytimemessage = $jsonresponse['postage_result']['delivery_time'];
		
		
		return true;
	}
	
	function getDeliveryTimeMessage(){
		//return parcel delivery time message
		return $this->deliverytimemessage;
	}
	
	function getItemShipmentCost(){
		//return item shipment cost
		return $this->totalshipmentcost;
	}
}
	
?>