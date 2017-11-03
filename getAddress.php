<?php

require_once "./common_db.php";


function queryAllAddress($shopper_pk){
# Queries the database for shipping details for provided user and returns these as a PDO
	
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
	$query .= ' where shopper_id = ?';
	
	$dbo = db_connect(); // Connect to DB and run query
	$statement = $dbo->prepare($query);
	$statement->execute(array($shopper_pk));
	
	return $statement;// Return query results
}


function queryDefaultAddress($shopper_pk){
# Returns the pk of the default shipping address for the supplied user	

	$query = 'select sh_primaryaddressfk from shopper where shopper_id = ?';
	
	$dbo = db_connect(); // Connect to DB and run query
	$statement = $dbo->prepare($query);
	$statement->execute(array($shopper_pk));
	
	$row  = $statement -> fetch(); // Get the first value, as only one row returned by query
	return $row[0]; // Return the first value as only one value returned by query
}


function queryOrderAddress($order_pk){
# Returns the address fk for the address listed for this order

	$query = 'select order_shaddr from "Order" where order_id = ?';
	
	$dbo = db_connect(); // Connect to DB and run query
	$statement = $dbo->prepare($query);
	$statement->execute(array($order_pk));

	$row  = $statement -> fetch(); // Get the first value, as only one row returned by query
	return $row[0]; // Return the first value as only one value returned by query
}


function queryAdressPKs($shopper_pk){
# Returns an array of the addresses PKs a user has
# This is used for cross checking what valid addresses a user has, therefore only the address_pk is required

		$query = 'select shaddr_id from shaddr where shopper_id = ?';

		$dbo = db_connect(); // Connect to DB and run query
		$statement = $dbo->prepare($query);
		$statement->execute(array($shopper_pk)); 

		$outputArray = array();
		while ($row_addresses = $statement->fetch()){
			array_push($outputArray, $row_addresses[0]); // Add just the address_pk to the array
		}	
	
		return $outputArray;
}


function querySpecificAddress($address_pk){
# Returns an array relating to the address values for the specified address_pk
	
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
	$query .= ' where shaddr_id = ?';
	
	$dbo = db_connect(); // Connect to DB and run query
	$statement = $dbo->prepare($query);
	$statement->execute(array($address_pk));

	return $statement -> fetch(); // Return the first value as only one value returned by query
}


function updateOrderAddress($address_pk, $order_pk){
# Updates the address value in a given order to the provided address_pk

	$query = 'update "Order" set order_shaddr = ? where order_id = ?';
	
	$dbo = db_connect(); // Connect to DB and run query
	$statement = $dbo->prepare($query);
	$statement->execute(array($address_pk, $order_pk));
}


function queryOrderShippingType($order_pk){
# Returns the shipping method set in the order

	$query = 'select order_shipping_type from "Order" where order_id = ?';
	
	$dbo = db_connect(); // Connect to DB and run query
	$statement = $dbo->prepare($query);
	$statement->execute(array($order_pk));
		
	return  $statement -> fetch(); // Return the first value, as only one row returned by query	
}


function updateOrderShippingType($shipping_type, $order_pk){

	$query = 'update "Order" set order_shipping_type = ? where order_id = ?';
	
	$dbo = db_connect(); // Connect to DB and run query
	$statement = $dbo->prepare($query);
	$statement->execute(array($shipping_type, $order_pk));
}


function queryEmail($shopper_pk){
	
	$query = 'select sh_email from shopper where shopper_id = ?'; 
	
	$dbo = db_connect(); // Connect to DB and run query
	$statement = $dbo->prepare($query);
	$statement->execute(array($shopper_pk));
	
	$row  = $statement -> fetch(); // Get the first value, as only one row returned by query	
	return $row[0]; // Return the first value as only single string in output
}

?>