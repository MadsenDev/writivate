<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$username = $conn->real_escape_string($_POST['username']);
	$password = $conn->real_escape_string($_POST['password']);
	$email = $conn->real_escape_string($_POST['email']);
	$rank_id = $conn->real_escape_string($_POST['rank_id']);

	$hashed_password = password_hash($password, PASSWORD_DEFAULT);

	$stmt = $conn->prepare("INSERT INTO users (username, password, email, rank_id) VALUES (?, ?, ?, ?)");
	$stmt->bind_param("sssi", $username, $hashed_password, $email, $rank_id);

	if ($stmt->execute()) {
		header("Location: manage_users.php");
		exit();
	} else {
		echo "Error: " . $stmt->error;
	}

	$stmt->close();
	$conn->close();
}
?>