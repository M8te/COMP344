<?php

require_once('getShipmentCosts.php');

function getPostageCost($selected_shipping_address, $order, $selected_shipping_type){
# Return the postage cost for the given order being sent to the given address	
	
	// Contains an array with the different shipping details
	// Fields in order of indexing: shaddr_id, shopper_id, sh_title, sh_firstname, sh_familyname, sh_street1, sh_street2, sh_city, sh_state, sh_postcode, sh_country
	$order_for_postage = querySpecificAddress($selected_shipping_address);
	
	global $fromPostcode; // Postcode we are sending from
	$toPostcode = $order_for_postage[9]; // Postcode we are sending to
	$country = $order_for_postage[10]; // Country we are sending to
	
	if ($country == "Australia"){ // Domestic shipping
		$country = ''; // Aus post uses blank country for Australia
		
		if ($selected_shipping_type == "Standard"){ // Standard domestic shipping
			$serviceType = "AUS_PARCEL_REGULAR";
		}
		
		else if ($selected_shipping_type == "Express"){ // Express domestic shipping
			$serviceType = "AUS_PARCEL_EXPRESS";
		}
	}
	
	else { // International shipping
		if ($selected_shipping_type == "Standard"){ // Standard international shipping
			$serviceType = 'INT_PARCEL_STD_OWN_PACKAGING';
		}
		
		else if ($selected_shipping_type == "Express"){ // Express international shipping
			$serviceType = 'INT_PARCEL_EXP_OWN_PACKAGING';
		}
	}
	
	$itemDimensions = getItemDimensions($order); // Returns physical dimensions items in the cart and quantity
	$total_shipping_cost = 0; // Tracks total cost
	
	// Aus post API needs to be called for each item in cart. Therefore iterate through each row in cart, total order and increment total by this.
	
	while ($row_details = $itemDimensions->fetch()){ 
	
		$rowQuantity = $row_details[0]; // Place values in their own containers for ease of reference.
		$lenghtCM = $row_details[5];
		$widthCM = $row_details[6];
		$heighCM = $row_details[7];
		$weightKG = $row_details[4];
		
		$costs = new CalculateShipment(); // Create object used to call API
		$responseCode = $costs->determinecosts($fromPostcode, $toPostcode, $lenghtCM, $widthCM, $heighCM, $weightKG, $serviceType, $country); // Get output from Auspost
		$line_cost = $costs->getItemShipmentCost() * $row_details[1]; // Get the cost of item(s) on this row
		$total_shipping_cost = $total_shipping_cost + $line_cost; // Update total cost with this row
	}
	
	return $total_shipping_cost; // Return total cost of all items iterated through

}


?>