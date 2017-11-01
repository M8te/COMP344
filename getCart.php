<?php

require_once "./common_db.php";


function queryOrder($order_pk){
# Returns all the items on the order for the provided order_pk
	
	# Build the SQL query
	$query = 'select';
	$query .= ' product.prod_name as "product",';
	$query .= ' attribute.name || \':\' || attributevalue.attrval_value as "type",';
	$query .= ' orderproduct.op_qty as "quantity",';
	$query .= ' attributevalue.attrval_price as "price",';
	$query .= ' orderproduct.op_qty * attributevalue.attrval_price as "line_price"';
	$query .= ' from "Order"';
	$query .= ' inner join orderproduct';
	$query .= ' on "Order".order_id = orderproduct.op_order_id';
	$query .= ' inner join product';
	$query .= ' on orderproduct.op_prod_id = product.prod_id';
	$query .= ' inner join orderproductattributevalues';
	$query .= ' on orderproduct.op_id = orderproductattributevalues.opattr_op_id';
	$query .= ' inner join attribute';
	$query .= ' on orderproductattributevalues.opattr_attr_id = attribute.id';
	$query .= ' inner join attributevalue';
	$query .= ' on orderproductattributevalues.opattr_attrval_id = attributevalue.attrval_id';
	$query .= ' where "Order".order_id = ' . $order_pk;
	
	$queryOutput = run_db_query($query); // Run query on DB and store result
	return $queryOutput;// Return query results
	
}

function getSubtotal($order_pk){
# Returns the total cost of all the items on the order	

	// Build query to get all items/quantities/costs on the order
	$query = 'select';
	$query .= ' orderproduct.op_qty as "quantity",';
	$query .= ' attributevalue.attrval_price as "price",';
	$query .= ' orderproduct.op_qty * attributevalue.attrval_price as "line_price"';
	$query .= ' from orderproduct';
	$query .= ' inner join orderproductattributevalues';
	$query .= ' on orderproduct.op_id = orderproductattributevalues.opattr_op_id';
	$query .= ' inner join attribute';
	$query .= ' on orderproductattributevalues.opattr_attr_id = attribute.id';
	$query .= ' inner join attributevalue';
	$query .= ' on orderproductattributevalues.opattr_attrval_id = attributevalue.attrval_id';
	$query .= ' where orderproduct.op_order_id = ' . $order_pk;
	
	
	$queryOutput = run_db_query($query); // Run query on DB and store result
	$subtotal = 0;

	while ($row_order = $queryOutput ->fetch()){ // Iterate through each line from SQL query and total cost
		$subtotal = $subtotal + $row_order[2];
	}			
	
	return $subtotal; // Return total of all line costs

}

function getGST($cost_subtotal, $order_country){
#Returns the GST amount required for payment

	global $gstRate;

	if ($order_country == "Australia"){
		return $cost_subtotal * $gstRate;
	}

	return 0;

}

function getItemDimensions($order){

	$query = ' select';
	$query .= ' product.prod_name as "product",';
	$query .= ' orderproduct.op_qty as "quantity",';
	$query .= ' attribute.name as "attribute",';
	$query .= ' attributevalue.attrval_value as "attribute_value",';
	$query .= ' prod_weight as "weight",';
	$query .= ' prod_l as "length",';
	$query .= ' prod_w as "width",';
	$query .= ' prod_h as "height"';
	$query .= ' from "Order"';
	$query .= ' inner join orderproduct';
	$query .= ' on "Order".order_id = orderproduct.op_order_id';
	$query .= ' inner join product';
	$query .= ' on orderproduct.op_prod_id = product.prod_id';
	$query .= ' inner join orderproductattributevalues';
	$query .= ' on orderproduct.op_id = orderproductattributevalues.opattr_op_id';
	$query .= ' inner join attribute';
	$query .= ' on orderproductattributevalues.opattr_attr_id = attribute.id';
	$query .= ' inner join attributevalue';
	$query .= ' on orderproductattributevalues.opattr_attrval_id = attributevalue.attrval_id';
	$query .= ' where "Order".order_id = ' . $order;
	
	$queryOutput = run_db_query($query); // Run query on DB and store result
	return $queryOutput; // Return query results
	
	}
	
function updateOrderCosts($order_id, $cost_shipping, $cost_gst, $cost_subtotal, $cost_total){

	$query = 'update "Order" set ORDER_SHIPPINGAMOUNT = ' . $cost_shipping . ', ORDER_TAXAMOUNT = ' . $cost_gst;
	$query .= ', ORDER_PRODUCTAMOUNT = ' .  $cost_subtotal . ', ORDER_TOTAL = ' . $cost_total . ' where order_id = ' . $order_id;
	run_db_query($query);
}	

?>
