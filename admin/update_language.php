<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $language = $_POST['language'];
    $language_code = $_POST['language_code'];

    // Check if a language with the same code already exists
    $stmt = $conn->prepare("SELECT * FROM languages WHERE language_code = ? AND id != ?");
    $stmt->bind_param("si", $language_code, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die('Language with this code already exists');
    }

    // Update the language in the database
    $stmt = $conn->prepare("UPDATE languages SET language = ?, language_code = ? WHERE id = ?");
    $stmt->bind_param("ssi", $language, $language_code, $id);
    $stmt->execute();

    header("Location: manage_languages.php");
    exit();
}
?>