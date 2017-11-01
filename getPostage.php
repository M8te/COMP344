<?php

require_once('getShipmentCosts.php');

function getPostageCost($selected_shipping_address, $order, $selected_shipping_type){
# Return the postage cost for the given order being sent to the given address	
	
	// Contains an array with the different shipping details
	// Fields in order of indexing: shaddr_id, shopper_id, sh_title, sh_firstname, sh_familyname, sh_street1, sh_street2, sh_city, sh_state, sh_postcode, sh_country
	$order_for_postage = querySpecificAddress($selected_shipping_address);
	
	// Contains an array of rows representing items in the DB
	// Each row has the dimensions fields for that item: product, quantity, attribute, attribute_value, weight, length, width, height
	$itemDimensions = getItemDimensions($order);
	

	global $fromPostcode; // Postcode we are sending from
	$toPostcode = $order_for_postage[9]; // Postcode we are sending to
	$country = $order_for_postage[10]; // Country we are sending to
	
	// REMOVE HARD CODING
	$lenghtCM= 22;
	$widthCM= 16;
	$heighCM= 7.7;
	$weightKG= 1.5;
	// REMOVE HARD CODING

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

	//Only set these for domestic
	//$serviceType = 'AUS_PARCEL_REGULAR';
	

	//Onluy set these for international
	//$serviceType = 'INT_PARCEL_STD_OWN_PACKAGING';
	//$country = 'NZ';


	$costs = new CalculateShipment();

	$responseCode = $costs->determinecosts($fromPostcode, $toPostcode, $lenghtCM, $widthCM, $heighCM, $weightKG, $serviceType, $country);

	// echo "<br> Delivery Time: " . $costs->getDeliveryTimeMessage() . "<br>";
	//echo "<br> Item Costs: " . $costs->getItemShipmentCost() . "<br>";
	
	
	
	// UNCOMMENT THIS CODE TO VIEW A TABLE OF THE PRODUCTS
	
	// echo("<table>");
	// while ($row_order = $itemDimensions->fetch()){ // Iterate trough each row in the order
		// echo("<tr>");
		// for ($i = 0; $i < $itemDimensions->columnCount(); $i++){ // Iterate through each value in the order row and place these into the table
			// echo("<td>".$row_order[$i]."</td>");
			// }
		// echo("</tr>");
	// };
	// echo("</table>");


	return $costs->getItemShipmentCost();

}


?>