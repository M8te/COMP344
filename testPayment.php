<?php
require_once("config.php");
require_once('processPayment.php');
require_once('processPaymentResponse.php');

// $id = '8af793f9af34bea0cf40f5fb5c630c';
//$id = uniqid("JR", true);

$amount = str_replace(".","",urlencode("100.00"));
$requesttype = "";
$transactiontype = "";
$orderid = "test123";
$currency = "";
$preauthid = "";
$cardnumber = "4444333322221111";
$cvv = "123";
$expiry = "09/23";

$trans = new SecurePayTransaction();

$responseCode = $trans->approve($amount, $requesttype, $transactiontype, $orderid, $currency, $preauthid, $cardnumber, $cvv, $expiry);

if (in_array($responseCode, $approvedResponseCodes))
	echo "Approved!! </br>";

echo "responseCode is " . $responseCode . "<br/>";
echo "Corresponding message is " . $responseCodes[$responseCode] . "<br/>";
echo "responseText is " . $trans->getLastResponseText() . "<br/>";
echo "confirmation id: " . $trans->getConfirmationID();

?>
