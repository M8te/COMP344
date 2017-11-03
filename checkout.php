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
include("getSelections.php"); // Used to set default checked values for the forms
include("getPostage.php"); // Used to get postage information

// SQL queries
$query_order = queryOrder($order_id); // Stores a list of items in users cart
$query_address = queryAllAddress($current_user); // Stores a list of all addresses for current user

// Selected shipping values
$selected_shipping_type = selectionShippingType($order_id); // Used to set the checked value for shipping type, based on default or posted value
$selected_shipping_address = selectionDefaultShipping($current_user, $order_id); // Used to set the checked type for shipping address, based on default type or posted value
$order_address = querySpecificAddress($selected_shipping_address); // Address of the user
$order_country = $order_address[10]; // Country of user, stored seperatly as needs to be referned for GST checking

// Costs
$cost_subtotal = getSubtotal($order_id); // Cost of all items in cart 
$cost_gst = getGST($cost_subtotal, $order_country); // GST cost of items in cart
$cost_shipping = getPostageCost($selected_shipping_address, $order_id, $selected_shipping_type); // Shipping cost, determined by contacting Aus post
$cost_total = $cost_subtotal + $cost_gst + $cost_shipping; // Total cost - subtotal + GST + shipping

updateOrderCosts($order_id, $cost_shipping, $cost_gst, $cost_subtotal, $cost_total); // Update the DB with the users

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
	
	<div style="padding:10px;">
	
	<h1>Checkout System</h1>
	
	<div id = "checkout_error">
		<?php
			if (isset($_SESSION['payment_error'])){ 
				echo($_SESSION['payment_error']);
			}
		?>
	</div>
	
	<div id = "address_form">
		<form action='checkout.php' method='post'>
			<div class = "row">
			
				<div class = "column">
					<h2>Select Address: </h2>
					<?php 
					
						while ($row_address = $query_address->fetch()){ // Iterate through all addresses stored for user
							
							$line_text = "<b>" . $row_address[2] . ":</b><br />"; // Format address
							$line_text .= $row_address[3] . " " . $row_address[4] . "<br />";
							$line_text .= $row_address[5] . ", " ; 

							if ($row_address[6] != ""){ // Only display the second street address if this exists
								$line_text .= $row_address[5] . ", ";
							}
							$line_text .= $row_address[7] . ", " . $row_address[8] . ", " . $row_address[9] . ", " . $row_address[10];
							
							$line_selector = "<input type = 'radio' name = 'shipping_address' value = " . $row_address[0]; // Format the radio button
							if ($row_address[0] == $selected_shipping_address){ // If this is selected value, mark it as checked
								$line_selector .= " checked";
							}
							$line_selector .= " onclick='displayShippingCountry(\"" . $row_address[10] ."\")' >" . $line_text . "<br />";
							
							echo($line_selector); // Display the formatted address value
							echo("<br />");
							
						}
					?>
					
					<a href="http://35.201.5.30/COMP344/"><button>Update addresses</button></a> <!-- link is to registration subsystem -->
					
				</div>
			
				<div class = "column">
				
					<div id = "shipping_choice" float="center">
						<h2>Select Shipping Method:</h2>
						<input type = 'radio' name = 'shipping_type' value = "Standard" <?php if ($selected_shipping_type == 'Standard')echo(" checked='true'"); ?>>Standard<br />
						<input type = 'radio' name = 'shipping_type' value = "Express" <?php if ($selected_shipping_type == 'Express')echo(" checked='true' "); ?>>Express<br />
					</div>
					
				<br />
				
				<div id = "shipping_button">
					<input type='submit' name='Submit' value='Update Shipping Information' /><br /><br />
				</div>
				
				<b>Shipping To:</b> <?php echo($order_address[3] . ' ' . $order_address[4] . ', ' . $order_address[5] . ' ' . $order_address[6] . ', ' . $order_address[7]); ?>
				<br />
				<b>Shipping Method:</b> <?php echo($selected_shipping_type); ?>
				<br />
				<br />
			</div>
		</form> 
			
		<div class = "column">
		
			<h2>Payment Information:</h2>
			
			<!-- JR added default test card. Remove when moving to prod.-->			
			<form name="payment" action="paymentProcessor.php" method="post">
				
				<label for="cc_number">Credit Card Number:</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" id="cc_number" name="cc_number" value = "4444333322221111" placeholder="1234567812345678"><br> 
					
					
				<label for="cc_expiry_month">Credit Card Expiry:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span>Month:</span>
						<select id="cc_expiry_month" name="cc_expiry_month">
							<option value="01">01</option>
							<option value="02">02</option>
							<option value="03" selected>03</option>
							<option value="04">04</option>
							<option value="05">05</option>
							<option value="06">06</option>
							<option value="07">07</option>
							<option value="08">08</option>
							<option value="09">09</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
						</select>	
		
				<span>Year:</span>
					<select id="cc_expiry_year" name="cc_expiry_year">
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23" selected>23</option>
					</select>
				<!--
				<label for="cc_expiry_month">Credit Card Expiry Month (MM):</label>
				 <input type="text" id="cc_expiry_month" name="cc_expiry_month" value= "09" placeholder="08"> <br>
					
				<label for="cc_expiry_year">Credit Card Expiry Year (YY):</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" id="cc_expiry_year" name="cc_expiry_year" value= "23" placeholder="17"><br>
				-->
				<br />
				<label for="cc_ccv">Credit Card CCV:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" id="cc_ccv" name="cc_ccv" value = "123" placeholder="123"><br>
				<br />
				
				<input type="submit" value="Make Payment">
				<a href="http://spider.science.mq.edu.au/mqauth/43715494/ass2/login/"><button>Return to Cart</button></a> <!-- link is to cart subsystem -->
				
				
				<?php
					global $debug;
					$debug=false;
					
					if($debug)
					{
						if (isset($_SESSION["payment_successful"])) echo $_SESSION["payment_successful"];
						if (isset($_SESSION["confirmation_id"])) echo $_SESSION["confirmation_id"];
						if (isset($_SESSION["payment_error"])) echo $_SESSION["payment_error"];
					}
				?>
				
			</form>
		</div>
	</div>
		
	<br />
	
	<div id = "order">

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

			<tr> 
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
	</div>

	<br />
	<br />
	<footer id="footerAJAX"></footer>
	
</body>

</html>