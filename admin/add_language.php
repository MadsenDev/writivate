<!DOCTYPE html>
<html>
<head>
    <title>Wiki - Add Language</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
<?php include 'admin_sidebar.php'; ?>
<main>
    <div class="content">
        <h1>Add Language</h1>
        <form method="POST" action="save_language.php">
            <div class="form-group">
                <label for="language-name">Language:</label>
                <input type="text" id="language-name" name="language" class="form-control">
            </div>
            <div class="form-group">
                <label for="language-code">Language Code:</label>
                <input type="text" id="language-code" name="language_code" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Save Language</button>
        </form>
    </div>
</main>
</body>
</html>