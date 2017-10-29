<?php
/* Bank and SecurePay Response Codes */

$responseCodes = array(
		/* Bank response codes for credit card transactions */
		"00" => "Approved",
		"01" => "Refer to Card Issuer",
		"02" => "Refer to Issuer’s Special Conditions",
		"03" => "Invalid Merchant",
		"04" => "Pick up card",
		"05" => "Do Not Honour",
		"06" => "Error",
		"07" => "Pick Up Card, Special Conditions",
		"08" => "Approved",
		"09" => "Request in progress",
		"10" => "Partial amount approved",
		"11" => "Approved VIP",
		"12" => "Invalid transaction",
		"13" => "Invalid amount",
		"14" => "Invalid card number",
		"15" => "No such issuer",
		"16" => "Approved, update track 3",
		"17" => "Customer cancellation",
		"18" => "Customer dispute",
		"19" => "Re-enter transaction",
		"20" => "Invalid response",
		"21" => "No action taken",
		"22" => "Suspected malfunction",
		"23" => "Unacceptable transaction fee",
		"24" => "File update not supported by receiver",
		"25" => "Unable to locate record on file",
		"26" => "Duplicate file update record",
		"27" => "File update field edit error",
		"28" => "File update locked out",
		"29" => "File update not successful",
		"30" => "Format error",
		"31" => "Bank not supported by switch",
		"32" => "Completed partially",
		"33" => "Expired card - pick up",
		"34" => "Suspected fraud - pick up",
		"35" => "Contact acquirer - pick up",
		"36" => "Restricted card - pick up",
		"37" => "Call acquirer security - pick up",
		"38" => "Allowable PIN tries exceeded",
		"39" => "No CREDIT account",
		"40" => "Requested function not supported",
		"41" => "Lost card - pick up",
		"42" => "No universal amount",
		"43" => "Stolen card - pick up",
		"44" => "No investment account",
		"51" => "Insufficient funds",
		"52" => "No cheque account",
		"53" => "No savings account",
		"54" => "Expired card",
		"55" => "Incorrect PIN",
		"56" => "No card record",
		"57" => "Trans. not permitted to cardholder",
		"58" => "Transaction not permitted to terminal",
		"59" => "Suspected fraud",
		"60" => "Card acceptor contact acquirer",
		"61" => "Exceeds withdrawal amount limits",
		"62" => "Restricted card",
		"63" => "Security violation",
		"64" => "Original amount incorrect",
		"65" => "Exceeds withdrawal frequency limit",
		"66" => "Card acceptor call acquirer security",
		"67" => "Hard capture - pick up card at ATM",
		"68" => "response received too late",
		"75" => "Allowable PIN tries exceeded",
		"77" => "Approved (ANZ only)",
		"86" => "ATM malfunction",
		"87" => "No envelope inserted",
		"88" => "Unable to dispense",
		"89" => "Administration error",
		"90" => "Cut-off in progress",
		"91" => "Issuer or switch is inoperative",
		"92" => "Financial institution not found",
		"93" => "Trans cannot be completed",
		"94" => "Duplicate transmission",
		"95" => "Reconcile error",
		"96" => "System malfunction",
		"97" => "Reconciliation totals reset",
		"98" => "MAC Error",
		"99" => "Reserved for national use",
		
		/* SecurePay Payment Server Response Codes */
		"100" => "Invalid transaction amount",
		"101" => "Invalid card number",
		"102" => "Invalid expiry date format",
		"103" => "Invalid purchase order",
		"104" => "Invalid merchant ID",
		"106" => "Card type unsupported",
		"109" => "Invalid credit card CVV number format",
		"110" => "Unable to connect to server",
		"111" => "Server connection aborted during transaction",
		"112" => "Transaction timed out by client",
		"113" => "General database error",
		"114" => "Error loading properties file",
		"115" => "Fatal unknown server error",
		"116" => "Function unavailable through bank",
		"117" => "Message format error",
		"118" => "Unable to decrypt error",
		"119" => "Unable to encrypt error",
		"123" => "Gateway timeout",
		"124" => "Gateway connection aborted during transaction",
		"125" => "Unknown error code",
		"126" => "Unable to connect to gateway",
		"131" => "Invalid number format",
		"132" => "Invalid date format",
		"133" => "Transaction for refund not in database",
		"134" => "Transaction already fully refunded / Only \$x.xx available for refund",
		"135" => "Transaction for reversal not in database",
		"136" => "Transaction already reversed",
		"137" => "Pre-auth transaction not found in database",
		"138" => "Pre-auth already completed",
		"139" => "No authorisation code supplied",
		"140" => "Partially refunded, do refund to complete",
		"141" => "No transaction ID supplied",
		"142" => "Pre-auth was done for smaller amount",
		"143" => "Payment amount smaller than minimum",
		"144" => "Payment amount greater than maximum",
		"145" => "System maintenance in progress",
		"146" => "Duplicate payment found",
		"147" => "No valid MCC found",
		"148" => "Invalid track 2 data",
		"149" => "Track 2 data not supplied",
		"151" => "Invalid currency code",
		"152" => "Multi-currency not supported by bank",
		"153" => "External database error",
		"157" => "Fraud check passed",
		"158" => "Fraud check error",
		"159" => "Suspected fraud",
		"175" => "No action taken",
		"190" => "Merchant gateway not configured",
		"195" => "Merchant gateway disabled",
		"199" => "Merchant gateway discontinued",
		
		"000" => "Normal",
		
		"504" => "Invalid merchant ID",
		"505" => "Invalid URL",
		"510" => "Unable to connect to server",
		"511" => "Server connection aborted during transaction",
		"512" => "Transaction timed out by client",
		"513" => "General database error",
		"514" => "Error loading properties file",
		"515" => "Fatal unknown error",
		"516" => "Request type unavailable",
		"517" => "Message format error",
		"524" => "Response format error",
		"545" => "System maintenance in progress",
		"550" => "Invalid password",
		"575" => "Not implemented",
		"577" => "Too many records for processing",
		"580" => "Process method has not been called",
		"595" => "Merchant disabled",
		
		
		/* Bank Gateway Response Codes */
		"900" => "Invalid transaction amount",
		"901" => "Invalid credit card number",
		"902" => "Invalid expiry date format",
		"903" => "Invalid transaction number",
		"904" => "Invalid merchant/terminal ID",
		"905" => "Invalid email address",
		"906" => "Card unsupported",
		"907" => "Card expired",
		"908" => "Insufficient funds",
		"909" => "Credit card details unknown",
		"910" => "Unable to connect to bank",
		"913" => "Unable to update database",
		"914" => "Power failure",
		"915" => "Fatal unknown gateway error",
		"916" => "Invalid transaction type requested",
		"917" => "Invalid message format",
		"918" => "Encryption error",
		"919" => "Decryption error",
		"922" => "Bank is overloaded",
		"923" => "Bank timed out",
		"924" => "Transport error",
		"925" => "Unknown bank response code",
		"926" => "Gateway busy",
		"928" => "Invaid customer ID",
		"932" => "Invalid transaction date",
		"933" => "Transaction not found",
		"936" => "Transaction already reversed",
		"938" => "Pre-auth already completed",
		"941" => "Invalid transaction ID supplied",
		"960" => "Contact card issuer",
		"970" => "File access error",
		"971" => "Invalid flag set",
		"972" => "Pin-pad/gateway offline",
		"973" => "Invoice unavailable",
		"974" => "Gateway configuration error",
		"975" => "No action taken",
		"976" => "Unknown currency code",
		"977" => "Too many records for processing",
		"978" => "Merchant blocked"
);
?>