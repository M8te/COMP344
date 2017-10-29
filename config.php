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

$shipmentConfig = array(
		'apikey' => "cee1166e-73a1-45ad-b78c-e13e8f9c88b1",
		'host' => "https://digitalapi.auspost.com.au",
		'domesticendpoint' => "/postage/parcel/domestic/calculate.json?",
		'internationalendpoint' => "/postage/parcel/international/calculate.json?"
);

$approvedResponseCodes = ["00","77"]; //payment approved response codes
		
// Time zone
date_default_timezone_set ("Australia/Sydney");

?>
