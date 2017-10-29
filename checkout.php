<?php

// NOTES
// 1) Session tracking
// 4) Query address SQL
// 5) Update GST for Aus only

//TO CHANGE
$order_id = 1; // Update to session order ID
$current_user = 8; // Update to session user
//END TO CHANGE

// Files to reference
include("config.php"); //Global config
include("getCart.php"); // Used to get details realting to the cart
include("getAddress.php"); // Used to get details relating to the users address
include("getSelections.php"); // Used to set default checked values for the forms
include("getPostage.php");


// SQL queries
$query_order = queryOrder($order_id); // Stores a list of items in users cart
$query_address = queryAllAddress($current_user); // Stores a list of all addresses for current user

// Selected shipping values
$selected_shipping_type = selectionShippingType(); // Used to set the checked value for shipping type, based on default or posted value
$selected_shipping_address = selectionDefaultShipping($current_user, $order_id); // Used to set the checked type for shipping address, based on default type or posted value
$order_address = querySpecificAddress($selected_shipping_address);

// Costs
$cost_subtotal = getSubtotal($order_id); // Cost of all items in cart 
$cost_gst = $cost_subtotal * $gstRate; // GST cost of items in cart
$cost_shipping = getPostageCost($selected_shipping_address, $order_id); // Shipping cost, determined by contacting Aus post
$cost_total = $cost_subtotal + $cost_gst + $cost_shipping; // Total cost - subtotal + GST + shipping



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>eCommerce Checkout</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">

</head>
  
<body>
	<h1>Checkout System</h1>
	
	<h2>Please select address and shipping type:</h2>
	
	<div id = "address_form" style="width:80%">
		<form action='checkout.php' method='post'>
			<div id =select_address" style="width: 60%; display:inline; float:left;">
				
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
						$line_selector .= " >" . $line_text . "<br />";
						
						echo($line_selector); // Display the formatted address value
						echo("<br />");
						
					}
				?>
			</div>
			
			<div id = "select_shipping_method" style="width: 40%; float:right; " >
				
				<input type = 'radio' name = 'shipping_type' value = "Standard" <?php if ($selected_shipping_type == 'Standard')echo(" checked"); ?>>Standard<br />
				<input type = 'radio' name = 'shipping_type' value = "Registered" <?php if ($selected_shipping_type == 'Registered')echo(" checked"); ?>>Registered<br />
				<input type = 'radio' name = 'shipping_type' value = "Express" <?php if ($selected_shipping_type == 'Express')echo(" checked"); ?>>Express<br />
				
			</div>
		
			<div id = "shipping_button" style="display:inline; width:100%; float:left">
				
				<input type='submit' name='Submit' value='Update Shipping Information' /><br /><br />
				
			</div>
			
		</form>
	</div>
	

	<div id = "order" style = "width:80%; display:inline; float:left;">
	
	<table border = 1 style="width:80%;">

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
	
	
	
	<div style="width: 100%; float:left; margin-top:20px;">
		<b>Shipping To:</b> <?php echo($order_address[3] . ' ' . $order_address[4] . ', ' . $order_address[5] . ' ' . $order_address[6] . ', ' . $order_address[7]); ?>
		<br />
		<b>Shipping Method:</b> <?php echo($selected_shipping_type); ?>
	</div>
	
	
	

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	
  </body>
</html>