<?php
// Database configuration
$host = 'localhost';
$db_user = 'madsensd_madsen';
$db_password = 'data2023';
$db_name = 'madsensd_tech_support_wiki';

// Create a connection
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}