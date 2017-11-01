<?php

require_once "./common_db.php";

function getOrderTotal($order_id){
	# Returns the total order cost
	
	// Build query to return order cost
	$query = 'select';
	$query .= ' order_total as "total"';
	$query .= ' from "Order"';
	$query .= ' where order_id = ' . $order_id;
	
	$ordertotal=array(); //initialize
	
	$queryOutput = run_db_query($query); // Run query
	
	$ordertotal = $queryOutput->fetch(PDO::FETCH_ASSOC);
	
	return $ordertotal['total'];
	
}

function updatePaymentConfirmation($order_id, $confirmation_id) {
	$query = "update \"Order\" set ORDER_PAYMENT_CONFIRMATION_ID = '" . $confirmation_id ."'";
	$query .= ' where order_id = ' . $order_id;
	run_db_query($query);
}	


updatePaymentConfirmation(1, 'BC');



?>