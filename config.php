<?php
// Database configuration
$host = 'localhost';
$db_user = 'root';
$db_password = 'cold1234';
$db_name = 'wiki';

// Create a connection
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}