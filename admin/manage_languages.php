<!DOCTYPE html>
<html>
<head>
    <title>Wiki - Manage Languages</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
<?php include 'admin_sidebar.php'; ?>
<?php
include '../config.php';

if (isset($_GET['delete_language'])) {
    $language_id = $_GET['language_id'];

    $stmt = $conn->prepare("DELETE FROM languages WHERE id = ?");
    $stmt->bind_param("i", $language_id);
    $stmt->execute();

    header('Location: manage_languages.php');
}

$stmt = $conn->prepare("SELECT * FROM languages");
$stmt->execute();
$result = $stmt->get_result();
?>

<main>
    <div class="content">
        <h1>Manage Languages <a href="add_language.php">Add Language</a></h1>

        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Language</th>
                <th>Language Code</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $language_id = $row['id'];
                    $language_name = $row['language'];
                    $language_code = $row['language_code'];

                    echo "<tr>";
                    echo "<td>$language_id</td>";
                    echo "<td>$language_name</td>";
                    echo "<td>$language_code</td>";
                    echo "<td><a href=\"edit_language.php?id=$language_id\">Edit</a> | <a href=\"manage_languages.php?delete_language=1&language_id=$language_id\" onclick=\"return confirm('Are you sure you want to delete this language?')\">Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "No languages found.";
            }
            ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>