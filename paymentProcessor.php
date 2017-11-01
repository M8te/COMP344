<?php
require_once ('processPayment.php');
require_once ('config.php');
require_once ('getCart.php');

global $debug;
$debug = false;


session_start();
$error = false; //initiallize error variable

//determine if user is logged in.
if (!isset($_SESSION['current_user'])){ 
	// Error as no user is logged in
	$error = TRUE;
	$_SESSION["payment_error"] = 'User has not been logged in.';
}


// Process mandatory fields

//check credit card details are provided
if (!isset($_POST['cc_number']) || !isset($_POST['cc_expiry_month']) || !isset($_POST['cc_expiry_year']) || !isset($_POST['cc_ccv']))
{
	$error = TRUE;
	$_SESSION["payment_error"] = 'Cannot process payment. Missing Credit Card details';
} 
else 
{	
	$cc_no = $_POST['cc_number'];
	$cc_exp = $_POST['cc_expiry_month'] . "/" . $_POST['cc_expiry_year'];
	$cc_ccv = $_POST['cc_ccv'];
}

//validate orderid
if(!isset($_SESSION['order_id']))
{
	$error = TRUE;
	$_SESSION["payment_error"] = 'Cannot process payment. Invalid order ID.';
}
else 
{
	$orderid = $_SESSION['order_id'];
}

//validate payment amount

//retrieve total cost of order from database and prepare for api request
$total_cost = str_replace(".","",urlencode(getOrderTotal($orderid)));


//$total_cost = str_replace(".","",urlencode(100.00)); use this line for successful cc payment.

if($debug)
	echo "<br/> Total cost from db: " . $total_cost . "<br/>";

//determine if value is valid
if($total_cost <= 0)
{
	$error = TRUE;
	$_SESSION["payment_error"] = 'Cannot process payment. Payment value must be greater then 0.';
}


// process optional fields
$request_type ='';
$transaction_type = '';
$currency='';
$preauth_id='';

//retrieve request type if passed
if(isset($_POST['request_type']))
	$request_type = $_POST['request_type'];

//retrieve transaction type if passed
if(isset($_POST['transaction_type']))
	$transaction_type= $_POST['transaction_type'];

// retireve currency if passed
if(isset($_POST['currency']))
	$currency= $_POST['currency'];

//retrieve per authorization id if passed.
if(isset($_POST['preauth_id']))
	$preauth_id= $_POST['preauth_id'];

// If no validation errors attempt payment
if (!$error)
{
	$paytransaction = new SecurePayTransaction();
	$response = $paytransaction->approve($total_cost, $request_type, $transaction_type, $orderid, $currency, $preauth_id, $cc_no, $cc_ccv, $cc_exp);
	
	//if response code is of approval
	if(in_array($response, $approvedResponseCodes))
	{
		
		//retrieve payment confirmation id
		$confirmationid = $paytransaction->getConfirmationID();
		
		//update order with payment confirmation
		updatePaymentConfirmation($orderid, $confirmationid);
		
		//set successful payment status
		$_SESSION["payment_successful"]=TRUE;
		
		//return the payment confirmation id
		$_SESSION["confirmation_id"]= $confirmationid;
		
		//payment success = display confirmation page.
		header('Location: paymentConfirmation.php');
	}
	else //if not approved
	{
		//set unsuccessful payment status
		$_SESSION["payment_successful"]=FALSE;
		
		// return error code and descriptions from payment gateway
		$_SESSION["payment_error"]= "Error processing payment. Error Code: " . $paytransaction->getLastResponseCode() . ". Error Description: " . $paytransaction->getLastResponseText();
		
		//back to checkout
		//header('Location: checkout.php');
	}
	
}
else //if validation errors identified
{
	//set unsuccessful payment status
	$_SESSION["payment_successful"]=FALSE;
	
	//back to checkout
	//header('Location: checkout.php');
}


if ($debug) {
	echo "total cost: " . $total_cost . "<br>"; 
	echo "request type: " . $request_type . "<br>";
	echo "transaction type: " . $transaction_type . "<br>";
	echo "order id: " . $orderid . "<br>";
	echo "currency: " . $currency . "<br>";
	echo "preauth id: " . $preauth_id . "<br>";
	echo "cc no: " . $cc_no . "<br>";
	echo "cc ccv: " . $cc_ccv . "<br>";
	echo "cc exp " . $cc_exp . "<br>";
	echo "current user: " . $_SESSION['current_user'] . "<br>";
	echo "payment error (if any): " . $_SESSION["payment_error"] . "<br>";
	echo "payment success?: " . $_SESSION["payment_successful"] . "<br>";
	echo "Confirmation ID:" . $_SESSION["confirmation_id"] . "<br>";
}

	
?>