<?php
// common_db.php - database connection using PHP::PDO ***
// Code provided by lecturer Les as part of week 10 resources on iLearn

// Allow switching between local and MQ servers. Leaving incase of server issues so code must be demonstrated locally.
$dbloc = "mq"; // Use "local", "mq" or add others as appropriate
if ($dbloc == "mq") {
	$dbhost = 'animatrix.science.mq.edu.au';
	$sid = "one";
	$dbusername = '';
	$dbuserpassword = '';
	$oraDB  = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=animatrix.science.mq.edu.au)";
	$oraDB .= "(PORT=1521)))(CONNECT_DATA=(SID=one)))";
}
else if ($dbloc == "local") {
	$dbhost = 'localhost';
	$dbusername = '<insert the PHP username>';
	$dbuserpassword = '<insert the PHP password>';
	$default_dbname = 'store';
}

// Use PDO to connect to the database; return the PDO object
function db_connect() {
    global $dbloc, $dbhost, $dbusername, $dbuserpassword, $default_dbname, $oraDB, $sid;
    // Set a default exception handler, so that we don't spill our guts if a query fails.
    set_exception_handler("store_exception_handler");
    
    // Oracle Connection
    if ($dbloc == "mq") {
	    $db = new PDO("oci:dbname=".$oraDB, $dbusername, $dbuserpassword);
    }
    else {
    	// MySQL Connection
    	$db = new PDO("mysql:host=$dbhost;dbname=$default_dbname;charset=utf8", $dbusername, $dbuserpassword);
    }
    
    // $db = new mysqli($dbhost, $dbusername, $dbuserpassword, $default_dbname);
    $db->setAttribute(PDO::ATTR_PERSISTENT, true);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
	return $db;
}

function store_exception_handler(RuntimeException $ex) {
	$debug = true;		// If true, report to screen; otherwise silently log and die.
	if(get_class($ex) == "PDOException") {
		if ($debug == true)
			echo "PDO Exception in file " . basename($ex->getFile()) . ", line " . $ex->getLine() . ":<br/>Code " . $ex->getCode() . " - " . $ex->getMessage();
		 else 
			error_log("PDO Exception in file " . basename($ex->getFile()) . ", line " . $ex->getLine() . ": Code " . $ex->getCode() . " - " . $ex->getMessage());
	}
	else {
		error_log("Unhandled Exception in file " . basename($ex->getFile()) . ", line " . $ex->getLine() . ": Code " . $ex->getCode() . " - " . $ex->getMessage());
	// Any other unhandled exceptions will wind up at the store home page, for safety
	header("Location: index.php");
	}
}

function run_db_query($query){
	
	$dbo = db_connect(); // Connect to DB

	try {
		$queryOutput = $dbo->query($query);
	}

	# Provide the exception handler - in this case, just print an error message and die,
	# but see the provided default exception handler in common_db.php, which logs to the Apache error log
	catch (PDOException $ex) {
		echo $ex->getMessage();
		die ("Invalid query");
	}
	
	return($queryOutput);
}

?>