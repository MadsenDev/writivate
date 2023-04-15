<?php
  include 'config.php';
  include 'functions.php';
  $site_name = get_setting_value($conn, 'site_name');
?>

<head>
    <title><?php echo $site_name; ?></title>
    <link rel="icon" type="image/png" href="public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="public/styles/main.css">
    <link rel="stylesheet" type="text/css" href="public/styles/header.css">
</head>