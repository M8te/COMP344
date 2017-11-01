<?php
// Stores a list of global configurations

$gstRate =  0.15;
$shippingMethods = array('Standard', 'Express');
$fromPostcode = '2000';

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
		'internationalendpoint' => "/postage/parcel/international/calculate.json?",
		'internationalshipoptionendpoint' => "/postage/parcel/international/service.json?",
		'domesticshipoptionendpoint' => "/postage/parcel/domestic/service.json?"
		
);

$approvedResponseCodes = array("00","77"); //payment approved response codes
		
// Time zone
date_default_timezone_set ("Australia/Sydney");

?>
