<?php
include '../config.php';

// Specify the upload directory
$upload_dir = '../uploads/';

// Check if the file is sent
if(isset($_FILES['image'])) {
    $file = $_FILES['image'];

    // Get file info
    $name = $file['name'];
    $tmp_name = $file['tmp_name'];
    $error = $file['error'];

    // Extract file extension
    $tempExtension = explode('.', $name);
    $file_extension = end($tempExtension);

    // Rename the file to avoid overwriting
    $name = uniqid() . '.' . $file_extension;
    $url = 'uploads/' . $name;

    if ($error === 0) {
        if (move_uploaded_file($tmp_name, $upload_dir . $name)) {
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO uploads (name, filename, file_extension, uploaded_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $name, $name, $file_extension);
            $stmt->execute();
            
            // Output to inline-attachment
            echo json_encode(["uploaded" => 1, "filename" => $url]);
        } else {
            echo json_encode(["uploaded" => 0, "error" => "Could not save file to server."]);
        }
    } else {
        echo json_encode(["uploaded" => 0, "error" => "File upload error."]);
    }
}
?>