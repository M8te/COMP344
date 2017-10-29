<?php

function selectionShippingType(){
# Checks if a postage shipping type has been posted and if so, validate this and return it.
# If invalid or no posted, return default (Standard)	
	
	if(!empty($_POST["shipping_type"])){ // If value posted
		$get_shipping_type = filter_var($_POST['shipping_type'], FILTER_SANITIZE_STRING); // Sanatize input and save as a variable

		if (in_array($get_shipping_type, array('Standard', 'Registered', 'Express'), true)){ // Validate that it is a valid postage method
			return $get_shipping_type; // Valid so return posted value
		}
	}

	return 'Standard'; // No shipping type has been posted, so return default value (standard shipping)
}



function selectionDefaultShipping($user, $order){
	

	$defaultAddress = queryDefaultAddress($user); // PK of address listed as primary/default for user
	$orderAddress = queryOrderAddress($order); // Address listed in the order, if one exists
	
	if(!empty($_POST["shipping_address"])){ 
		$get_shipping_address = filter_var($_POST['shipping_address'], FILTER_SANITIZE_STRING); // Sanatize input and save as a variable
		
		$queryAddressArray = queryAdressPKs($user);
		if (in_array($get_shipping_address, $queryAddressArray)){ // Check posted address is valid for user
			
			if (isSet($orderAddress) && $orderAddress == $get_shipping_address){ // Address listed for order is posted address
				return $orderAddress;
			}
			
			else {
				updateOrderAddress($get_shipping_address, $order);
				return $get_shipping_address;
			}
		}
		
		// Either no post value or invalid post, therefore use existing value or default value
		
		if(isSet($orderAddress)){ // Order has an address listed so return this
			return $orderAddress;
		}
		
		else{ // No address for order - update this with default value and return default value
			updateOrderAddress($defaultAddress, $order);
			return $defaultAddress;
		}
	}	

	return $defaultAddress;
}


?>