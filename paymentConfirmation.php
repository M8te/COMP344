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
	<h1>This is your invoice</h1>
		
	May need to email invoice if not already done.

	
	<div id = "address_form">
		<form action='checkout.php' method='post'>
			<div class = "row">
			
				<div class = "column">
					<h2>Add shipping information: </h2>
					Add details here from $order_address.
					
				</div>
				
				<div class = "column">
					<h2>Add Any other information</h2>
					Putting shipping type or anything else here.

				
				</div>
				</div>
				
	<br />		
	<br />
	<br />
				
			</form>  
			
				<br />
	<div id = "order"><h2>Items ordered:</h2>
	
			Need to update the costs with the values from $invoice_costs. Look at the queries/compare to the checkout.php if your not sure how as this has all be coded in one form or another already.
	
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
				<td><?php echo($cost_subtotal) ?></td>
			</tr>
			<tr>
				<td colspan = 4 align="right"><b>GST: </b></td>
				<td><?php echo($cost_gst) ?></td>
			</tr>
			<tr>
				<td colspan = 4 align="right"><b>Shipping: </b></td>
				<td><?php echo($cost_shipping) ?></td>
			</tr>
			<tr>
				<td colspan = 4 align="right"><b>Total Cost: </b></td>
				<td><?php echo($cost_total) ?></td>
			</tr>

		</table>
	</div>
	<br>

	</div>
	
  </body>
  <footer id="footerAJAX"></footer>
</html>










