<?php
session_start();
include '../config.php';

if (isset($_GET['id'])) {
	$user_id = $conn->real_escape_string($_GET['id']);

	$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
	$stmt->bind_param("i", $user_id);

	if ($stmt->execute()) {
		header("Location: manage_users.php");
		exit();
	} else {
		echo "Error: " . $stmt->error;
	}

	$stmt->close();
	$conn->close();
} else {
	header("Location: manage_users.php");
	exit();
}
?>