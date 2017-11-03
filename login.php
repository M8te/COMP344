<?php

session_start();  // start sessions 

if(!empty($_GET["error"])){
	if ($_GET["error"] == "not_loggedin"){
		
		echo("Not logged in. Please login to continue");
		echo("<br /> <br />");
	}
	
	if ($_GET["error"] == "no_order"){
		
		echo("No active order to checkout.");
		echo("<br /> <br />");
	}
}

$_SESSION['user_id'] = 8;
$_SESSION['order_id'] = 1;

?>

<!DOCTYPE html>
<html lang="en">
<head>

</head>
<body>

<p>Session has been set</p>

<p><a href = "checkout.php">Checkout</a></p>

</body>
</html>
