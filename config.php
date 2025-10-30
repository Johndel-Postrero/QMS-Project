<?php
// Start an output buffer to prevent accidental output from breaking JSON responses
if (function_exists('ob_get_level') && ob_get_level() === 0) { ob_start(); }
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
if (function_exists('mysqli_report')) { mysqli_report(MYSQLI_REPORT_OFF); }

$host = "sql204.byethost5.com";
$username = "b5_40277518";  
$password = "euwen123"; 
$database = "b5_40277518_QMS"; 

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_errno) {
	http_response_code(500);
	// Ensure no prior output leaks into the JSON error
	if (function_exists('ob_get_level')) { while (ob_get_level() > 0) { ob_end_clean(); } }
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode([
		'success' => false,
		'error' => 'Database connection failed: ' . $conn->connect_error
	]);
	exit;
}

$conn->set_charset('utf8mb4');

