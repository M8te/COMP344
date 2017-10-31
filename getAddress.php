<?php

require_once "./common_db.php";


function queryAllAddress($shopper){
# Queries the database for shipping details for provided user and returns these as a PDO
	
	// Create a query to retrieve the users address details
	//$query = 'select * from shaddr';
	$query = 'select';
    $query .= ' shaddr_id as "Address_ID",';
    $query .= ' shopper_id as "Shopper_Id",';
    $query .= ' sh_title as "Address_Title",';
    $query .= ' sh_firstname as "First_Name",';
    $query .= ' sh_familyname as "Last_Name",';
    $query .= ' sh_street1 as "Stree1_1",';
    $query .= ' sh_street2 as "Street_2",';
    $query .= ' sh_city as "City",';
    $query .= ' sh_state as "State",';
    $query .= ' sh_postcode as "Postcode",';
    $query .= ' sh_country as "Country"';
	$query .= ' from shaddr';
	$query .= ' where shopper_id = ' . $shopper;

	
	$queryOutput = run_db_query($query); // Run query on DB and store result
	return $queryOutput;// Return query results
}


function queryDefaultAddress($shopper_pk){
# Returns the pk of the default shipping address for the supplied user	

	$query = 'select sh_primaryaddressfk from shopper where shopper_id = ' . $shopper_pk; // SQL query
	$queryOutput = run_db_query($query); // Run query on DB and store result
	$row  = $queryOutput -> fetch(); // Get the first value, as only one row returned by query
	
	return $row[0]; // Return the first value as only one value returned by query
}

function queryOrderAddress($order){
# Returns the address fk for the address listed for this order

	$query = 'select order_shaddr from "Order" where order_id = ' . $order; // SQL query
	$queryOutput = run_db_query($query); // Run query on DB and store result
	$row  = $queryOutput -> fetch(); // Get the first value, as only one row returned by query

	return $row[0]; // Return the first value as only one value returned by query
}


function queryAdressPKs($shopper_pk){
# Returns an array of the addresses PKs a user hash

		$query = 'select shaddr_id from shaddr where shopper_id = ' . $shopper_pk;
		$queryOutput = run_db_query($query); // Run query on DB and store result
		$outputArray = array();
	
		while ($row_addresses = $queryOutput->fetch()){
			array_push($outputArray, $row_addresses[0]);
		}	
	
		return $outputArray;
}

function querySpecificAddress($address_pk){
	
//	echo($address_pk);
	
	$query = 'select';
    $query .= ' shaddr_id as "Address_ID",';
    $query .= ' shopper_id as "Shopper_Id",';
    $query .= ' sh_title as "Address_Title",';
    $query .= ' sh_firstname as "First_Name",';
    $query .= ' sh_familyname as "Last_Name",';
    $query .= ' sh_street1 as "Stree1_1",';
    $query .= ' sh_street2 as "Street_2",';
    $query .= ' sh_city as "City",';
    $query .= ' sh_state as "State",';
    $query .= ' sh_postcode as "Postcode",';
    $query .= ' sh_country as "Country"';
	$query .= ' from shaddr';
	$query .= ' where shaddr_id = ' . $address_pk;
	
	$queryOutput = run_db_query($query); // Run query on DB and store result
	$row  = $queryOutput -> fetch(); // Get the first value, as only one row returned by query

	return $row; // Return the first value as only one value returned by query
	
}


function updateOrderAddress($address, $order){

	$query = 'update "Order" set order_shaddr = ' . $address . ' where order_id = ' . $order;
	run_db_query($query);
}

function queryOrderShippingType($order_pk){
# Returns the shipping method set in the order

		$query = 'select order_shipping_type from "Order" where order_id = ' . $order_pk;
		$queryOutput = run_db_query($query); // Run query on DB and store result
		$row  = $queryOutput -> fetch(); // Get the first value, as only one row returned by query	
		return $row;
}

function updateOrderShippingType($shipping_type, $order){

	$query = 'update "Order" set order_shipping_type = \'' . $shipping_type . '\' where order_id = ' . $order;
	run_db_query($query);
}

?>