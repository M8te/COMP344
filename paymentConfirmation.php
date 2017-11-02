<?php

// Files to reference
include("config.php"); //Global config
include("getCart.php"); // Used to get details realting to the cart
include("getAddress.php"); // Used to get details relating to the users address

session_start();

// CHECK THIS
//$_SESSION["confirmation_id"]= $confirmationid;
//$_SESSION['order_id'] = $order_id;  //JR
//$_SESSION['current_user']=$current_user;  //JR
// CHECK

$current_user = 8; // Update to session user
$order_id = 1; // Update to session order ID

$invoice_costs = getAllCosts($order_id); // Costs
$query_order = queryOrder($order_id); // Stores a list of items in users cart
$order_address = querySpecificAddress(queryOrderAddress($order_id)); // Selected shipping values

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

	<?php
	$users_email = queryEmail($current_user);
	$email = "nicholas.mchugh@students.mq.edu.au";
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
	
	<div id = "address_form">
		<form action='checkout.php' method='post'>
			<div class = "row">
				<div class = "column">
					<h2>Invoice</h2>
					<b>Your order number is: <?php echo($order_id); ?></b><br><br>
					Thank you for shopping with Macquarie Uni Bookshop. An email has been sent to <?php echo($users_email); ?>. We'll let you know once your item(s) have been dispatched with an estimated delivery time. 
					

				
				</div>
				<div class = "column">
					<h2>Shipping information: </h2>
					
					Your order has been sent to your <?php echo($order_address[2]); ?> address:
					<?php echo("<br />"); ?>
					Street: <?php echo($order_address[5]); ?> 
					<?php echo("<br />"); ?>
					Postcode: <?php echo($order_address[9]); ?> 
					<?php echo("<br />"); ?>
					City: <?php echo($order_address[7]); ?>
					<?php echo("<br />"); ?>
					State: <?php echo($order_address[8]); ?> 
					<?php echo("<br />"); ?>
					Country: <?php echo($order_address[10]); ?> 
					<?php echo($order_address[6]); ?> 
				</div>
				</div>
	<br />		
	<br />
	<br />
				
			</form>  
			
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










