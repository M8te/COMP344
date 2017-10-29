<?php 
require_once("config.php");
require_once('getShipmentCosts.php');
//require_once('processPaymentResponse.php');

$fromPostcode = '2000';
$toPostcode = '3000';
$lenghtCM= 22;
$widthCM= 16;
$heighCM= 7.7;
$weightKG= 1.5;

//Only set these for domestic
$serviceType = 'AUS_PARCEL_REGULAR';
$country = '';

//Onluy set these for international
//$serviceType = 'INT_PARCEL_STD_OWN_PACKAGING';
//$country = 'NZ';


$costs = new CalculateShipment();

$responseCode = $costs->determinecosts($fromPostcode, $toPostcode, $lenghtCM, $widthCM, $heighCM, $weightKG, $serviceType, $country);

echo "<br> Delivery Time: " . $costs->getDeliveryTimeMessage() . "<br>";
echo "<br> Item Costs: " . $costs->getItemShipmentCost() . "<br>";


/*
if (in_array($responseCode, $approvedResponseCodes))
	echo "Approved!! </br>";

echo "responseCode is " . $responseCode . "<br/>";
echo "Corresponding message is " . $responseCodes[$responseCode] . "<br/>";
echo "responseText is " . $trans->getLastResponseText() . "<br/>";
echo "confirmation id: " . $trans->getConfirmationID();
*/
?>
