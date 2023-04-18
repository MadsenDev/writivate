<!DOCTYPE html>
<html>
<head>
    <title>Wiki - Edit Language</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
<?php include 'admin_sidebar.php'; ?>
<?php
include '../config.php';

$language_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM languages WHERE id = ?");
$stmt->bind_param("i", $language_id);
$stmt->execute();
$result = $stmt->get_result();
$language_data = $result->fetch_assoc();
?>
<main>
    <div class="content">
        <h1>Edit Language</h1>
        <form method="POST" action="update_language.php">
            <input type="hidden" name="id" value="<?php echo $language_data['id']; ?>">
            <div class="form-group">
                <label for="language">Language:</label>
                <input type="text" id="language" name="language" class="form-control" value="<?php echo $language_data['language']; ?>">
            </div>
            <div class="form-group">
                <label for="language_code">Language Code:</label>
                <input type="text" id="language_code" name="language_code" class="form-control" value="<?php echo $language_data['language_code']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Language</button>
        </form>
    </div>
</main>
</body>
</html>