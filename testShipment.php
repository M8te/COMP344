<?php 
require_once("config.php");
require_once('getShipmentCosts.php');
//require_once('processPaymentResponse.php');

//Set variable to d for testing domestic, blank for international
$testing = '';

$fromPostcode = '2000';
$toPostcode = '3000';
$lenghtCM= 22;
$widthCM= 16;
$heighCM= 7.7;
$weightKG= 1.5;




if($testing=='d'){
	$serviceType = 'AUS_PARCEL_EXPRESS_SATCHEL_3KG';
	$country = '';
} else {
	$serviceType = 'INT_PARCEL_EXP_OWN_PACKAGING';
	$country = 'NZ';
}


$costs = new CalculateShipment();

$responseCode = $costs->determinecosts($fromPostcode, $toPostcode, $lenghtCM, $widthCM, $heighCM, $weightKG, $serviceType, $country);

if ($testing=='d')
{
	echo 'Testing Domestic<br>';
	echo "<br> Delivery Time: " . $costs->getDeliveryTimeMessage() . "<br>";
} else 
{
	echo 'Testing International<br>';
}
echo "<br> Item Costs: " . $costs->getItemShipmentCost() . "<br>";

$shipOpt = $costs->determineShippingOptions($fromPostcode, $toPostcode, $lenghtCM, $widthCM, $heighCM, $weightKG, $country);

/*
if (in_array($responseCode, $approvedResponseCodes))
	echo "Approved!! </br>";

echo "responseCode is " . $responseCode . "<br/>";
echo "Corresponding message is " . $responseCodes[$responseCode] . "<br/>";
echo "responseText is " . $trans->getLastResponseText() . "<br/>";
echo "confirmation id: " . $trans->getConfirmationID();
*/
?>
