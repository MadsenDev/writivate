<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $language = $_POST['language'];
    $language_code = $_POST['language_code'];

    // Check if a language with the same code already exists
    $stmt = $conn->prepare("SELECT * FROM languages WHERE language_code = ?");
    $stmt->bind_param("s", $language_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        die('Language with this code already exists');
    }

    // Insert the language into the database
    $stmt = $conn->prepare("INSERT INTO languages (language, language_code) VALUES (?, ?)");
    $stmt->bind_param("ss", $language, $language_code);
    $stmt->execute();

    header("Location: manage_languages.php");
    exit();
}
?>