<?php

require_once "./common_db.php";


function queryOrder($order_pk){
# Returns all the items on the order for the provided order_pk
	
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
	$query .= ' where "Order".order_id = ?';
	
	$dbo = db_connect(); // Connect to DB and run query
	$statement = $dbo->prepare($query);
	$statement->execute(array($order_pk));
	
	return $statement;// Return query results
}


function getSubtotal($order_pk){
# Returns the total cost of all the items on the order	

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
	$query .= ' where orderproduct.op_order_id = ?';
	
	
	$dbo = db_connect(); // Connect to DB and run query
	$statement = $dbo->prepare($query);
	$statement->execute(array($order_pk));
	
	$subtotal = 0;
	while ($row_order = $statement ->fetch()){ // Iterate through each line from SQL query and total cost
		$subtotal = $subtotal + $row_order[2];
	}			
	
	return $subtotal; // Return total of all line costs
}


function getGST($cost_subtotal, $order_country){
#Returns the GST amount required for payment

	global $gstRate; // Value is defined in config.php

	if ($order_country == "Australia"){ // If Australia, apply GST
		return $cost_subtotal * $gstRate;
	}

	return 0; // If not Australia, do not apply GST
}


function getItemDimensions($order_pk){
# Returns the item dimensions required for shipping the order

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
	$query .= ' where "Order".order_id = ?';
	
	$dbo = db_connect(); // Connect to DB and run query
	$statement = $dbo->prepare($query);
	$statement->execute(array($order_pk));
	
	return $statement; // Return query results
}	

	
function updateOrderCosts($order_pk, $cost_shipping, $cost_gst, $cost_subtotal, $cost_total){
# Update the order fields in the DB

	$query = 'update "Order" set ORDER_SHIPPINGAMOUNT = ?, ORDER_TAXAMOUNT = ?, ORDER_PRODUCTAMOUNT = ?, ORDER_TOTAL = ? where order_id = ?';
	
	$dbo = db_connect(); // Connect to DB and run query
	$statement = $dbo->prepare($query);
	$statement->execute(array($order_pk, $cost_shipping, $cost_gst, $cost_subtotal, $cost_total));
}	


function updatePaymentConfirmation($order_pk, $confirmation_id) {
# Update the order with payment confirmation id. 
	
	$query = 'update "Order" set ORDER_PAYMENT_CONFIRMATION_ID = ? where order_id = ?';
	
	$dbo = db_connect(); // Connect to DB and run query
	$statement = $dbo->prepare($query);
	$statement->execute(array($order_pk, $confirmation_id));
}


function getOrderTotal($order_pk){
# Returns the total order cost
	
	$query = 'select order_total as "total" from "Order" where order_id = ?';
	
	$dbo = db_connect(); // Connect to DB and run query
	$statement = $dbo->prepare($query);
	$statement->execute(array($order_pk));
	
	$ordertotal = array(); //initialize array
	$ordertotal = $statement->fetch(PDO::FETCH_ASSOC);
	
	return $ordertotal['total'];
}


function getAllCosts($order_pk){
# Returns all the cost values in an order

	$query = 'select ORDER_SHIPPINGAMOUNT, ORDER_TAXAMOUNT, ORDER_PRODUCTAMOUNT, ORDER_TOTAL from "Order" where order_id = ?';
	
	$dbo = db_connect(); // Connect to DB and run query
	$statement = $dbo->prepare($query);
	$statement->execute(array($order_pk));	
	
	return $statement->fetch(); // Return first row as only a single row returned from query
}

?>
