<?php
// Stores a list of global configurations

$gstRate =  0.15;

// SecurePay Credentials.

$securePayConfig = array(
		'merchantID' => "ABC0001",
		'merchantPW' => "abc123",
		'endpoint' => "https://test.api.securepay.com.au/xmlapi/payment",
		'timeout' => "60",
		'apiversion' => "xml-4.2",
		'timezone' => "+600", //Australia East Cast
		'txnsource' => "23");

$approvedResponseCodes = ["00","77"]; //payment approved response codes
		
// Time zone
date_default_timezone_set ("Australia/Sydney");

?>
