<?php

session_start();  // start sessions 

// Check user is logged in before displaying page
if(isset($_SESSION['user_id'])){
	$current_user = $_SESSION['user_id']; // If login detected, display details for logged in user
}
else { // No login, Redirect to login page
	header('Location: https://spider.science.mq.edu.au/mqauth/44542291/assignment2_stage2/login.php?error=not_loggedin'); 
}

// Check user has an order before proceeding
if(isset($_SESSION['order_id'])){
	$order_id = $_SESSION['order_id']; // If login detected, display details for logged in user
}
else { // Redirect to login page for this assignment only. Would usually redirect to catalogue or whichever page was most appropriate
	header('Location: https://spider.science.mq.edu.au/mqauth/44542291/assignment2_stage2/login.php?error=no_order'); 
}


// Files to reference
include("config.php"); //Global config
include("getCart.php"); // Used to get details realting to the cart
include("getAddress.php"); // Used to get details relating to the users address


// Data to display
$invoice_costs = getAllCosts($order_id); // Costs
$query_order = queryOrder($order_id); // Stores a list of items in users cart
$order_address = querySpecificAddress(queryOrderAddress($order_id)); // Selected shipping values


	$users_email = queryEmail($current_user);
	$email = "ben.woods@students.mq.edu.au";
	$subject = "Account Registration";
	$txt = "Thank you for making a purchase with the Macqaurie Uni Bookshop. We hope you enjoy your purchase and come back to shop with us in the future. 
Order number is: $order_id
Name: $order_address[3] $order_address[4]
Shipping Information:
Street: $order_address[5]
Postcode: $order_address[9]
City: $order_address[7]
State: $order_address[8]
Country: $order_address[10]
Cost:
Subtotal: $$invoice_costs[2]
GST: $$invoice_costs[1]
Shipping: $$invoice_costs[0]
Total Cost: $$invoice_costs[3]
";
	$headers = "From: NO-REPLY@BOOKSHOP.COM";  
	mail($email,$subject,$txt,$headers);
	
	
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>eCommerce Checkout</title>
    <meta name="author" content="Nick McHugh, Cameron Bendall, Ben Woods, Jose Ribeiro">
	<!-- Mobile Specific Metas –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSS –––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<link rel="stylesheet" type="text/css" href="resources/checkout.css"/>
	<!-- jQuery –––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- FontAwesome Icon CSS –––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<!-- Bootstrap CSS –––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<!-- Google Fonts –––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,200,300,400,500,700,900" rel="stylesheet">
	
</head>

<body>
	<nav id="navAJAX" class="navbar navbar-inverse"></nav>
	<script>
		/* Ajax Menu and Footer
		–––––––––––––––––––––––––––––––––––––––––––––––––– */
		$(document).ready(function() {
	        $("#navAJAX").load("Resources/header.html");
			$("#footerAJAX").load("Resources/footer.html");
	    });
	</script>



	<div id = "invoice_details" style="padding:10px;">
			
		<h1>Your order has been successfully placed!</h1>
		<p><b>Thank you for shopping with Macquarie Uni Bookshop. Your order number is: #</b><?php echo($order_id); ?></p>

		<p>An email has been sent to <?php echo($users_email); ?>. Your order will be sent to your <?php echo($order_address[2]); ?> address, listed below. We'll let you know once your item(s) have been dispatched with an estimated delivery time.</p>
		
		<br />
		
		<table style="width:20%;">
			<tr>
				<td><b>Name:</b></td> 
				<td><?php echo($order_address[3] . " " . $order_address[4]); ?> </td> 
			</tr>
			<tr>
				<td><b>Street:</b></td> 
				<td><?php echo($order_address[5]); ?> </td> 
			</tr>
			<tr>
				<td><b>Postcode:</b></td>
				<td><?php echo($order_address[9]); ?> </td>
			</tr>			
			
			<tr>
				<td><b>City:</b></td>
				<td><?php echo($order_address[7]); ?></td>
			</tr>
			
			<tr>
				<td><b>State:</b></td>
				<td><?php echo($order_address[8]); ?></td>
			</tr>
			
			<tr>
				<td><b>Postcode:</b></td>
				<td><?php echo($order_address[9]); ?></td>
			</tr>
			
			<tr>
				<td><b>Country: </b></td>
				<td><?php echo($order_address[10]); ?></td>
			</tr>

		</table>
		
		<?php echo($order_address[6]); ?> 

	<br />
				
			
				<br />
	<div id = "order"><h2>Items ordered:</h2>

	<table border = 1>
	
			<tr>
				<?php 
				$header_cart = array("Product", "Type", "Quantity", "Price", "Total"); // Headers for each of the columns
				
				for ($i = 0; $i < $query_order->columnCount(); $i++){ // Iterate through the headers and set the table headers to these
					echo("<th>".$header_cart[$i]."</th>");
				
				} ?>
			</tr>	
			
			<?php 
				while ($row_order = $query_order->fetch()){ // Iterate trough each row in the order
					echo("<tr>");
					for ($i = 0; $i < $query_order->columnCount(); $i++){ // Iterate through each value in the order row and place these into the table
						echo("<td>".$row_order[$i]."</td>");
					}
					echo("</tr>");
				};
			?>

			<tr > 
				<td colspan = 4 align="right"><b>Subtotal: </b></td>
				<td><?php echo($invoice_costs[2]); ?></td>
			</tr>
			<tr>
				<td colspan = 4 align="right"><b>GST: </b></td>
				<td><?php echo($invoice_costs[1]); ?></td>
			</tr>
			<tr>
				<td colspan = 4 align="right"><b>Shipping: </b></td>
				<td><?php echo($invoice_costs[0]); ?></td>
			</tr>
			<tr>
				<td colspan = 4 align="right"><b>Total Cost: </b></td>
				<td><?php echo($invoice_costs[3]); ?></td>
			</tr>

		</table>
	</div>
	<br>

	</div>
	
  </body>
  <footer id="footerAJAX"></footer>
</html>