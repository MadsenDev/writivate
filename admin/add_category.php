<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    file_put_contents("debug_log.txt", "[" . date("Y-m-d H:i:s") . "] " . "POST request detected on add_category.php" . PHP_EOL, FILE_APPEND);
    file_put_contents("debug_log.txt", "[" . date("Y-m-d H:i:s") . "] " . "POST data: " . json_encode($_POST) . PHP_EOL, FILE_APPEND);
}

include '../config.php';

function logToFile($message) {
    $logFile = 'add_category_log.txt';
    $currentDate = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$currentDate] $message" . PHP_EOL, FILE_APPEND);
}

ini_set('log_errors', 1);
ini_set('error_log', 'add_category_log.txt');
error_reporting(E_ALL);

logToFile("Script started");

if (isset($_POST['add_category'])) {
    logToFile("add_category POST variable detected");
    
    // Add new category
    $category_name = $_POST['category_name'];
    $parent_id = $_POST['parent_id'] ?? null;

    if ($parent_id === "") {
        $parent_id = NULL;
    }

    $stmt = $conn->prepare("INSERT INTO categories (name, parent_id) VALUES (?, ?)");
    if (!$stmt) {
        logToFile("Error preparing the statement: " . $conn->error);
        exit();
    }

    $bind_result = $stmt->bind_param("si", $category_name, $parent_id);
    if (!$bind_result) {
        logToFile("Error binding parameters: " . $stmt->error);
        exit();
    }

    $execute_result = $stmt->execute();
    if (!$execute_result) {
        logToFile("Error executing the statement: " . $stmt->error);
        exit();
    }

    $stmt->close();
    logToFile("Record added successfully");
    header('Location: manage_categories.php');
} else {
    logToFile("add_category POST variable not detected");
}
?>