<?php
global $debug;
$debug = true;
session_start();


if($debug)
{
	if (isset($_SESSION["payment_successful"])) echo "<br/> Success Status: " . $_SESSION["payment_successful"] . "<br/>";
	if (isset($_SESSION["confirmation_id"])) echo "Confirmation ID:" . $_SESSION["confirmation_id"] . "<br/>";
	if (isset($_SESSION["payment_error"])) echo "Payment Error: " . $_SESSION["payment_error"] . "<br/>";
	if (isset($_SESSION['test'])) echo $_SESSION['test'];
}

session_destroy();

?>