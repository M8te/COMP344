<?php

function getPostageCost($selected_shipping_address, $order){
# Return the postage cost for the given order being sent to the given address	
	
	// Contains an array with the different shipping details
	// Fields in order of indexing: shaddr_id, shopper_id, sh_title, sh_firstname, sh_familyname, sh_street1, sh_street2, sh_city, sh_state, sh_postcode, sh_country
	$order_for_postage = querySpecificAddress($selected_shipping_address);
	
	// Contains an array of rows representing items in the DB
	// Each row has the dimensions fields for that item: product, quantity, attribute, attribute_value, weight, length, width, height
	$itemDimensions = getItemDimensions($order);
	
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


	return 0; // Update this to return cost for shipping

}


?>