<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $suggestion = $_POST['suggestion'];

  // Verify the reCAPTCHA response
  $recaptcha_secret_key = '6LdW04glAAAAAO2uwUvzhnpPWIHKFqwW1Hx0m0l9';
  $recaptcha_response = $_POST['g-recaptcha-response'];
  $url = 'https://www.google.com/recaptcha/api/siteverify';
  $data = [
    'secret' => $recaptcha_secret_key,
    'response' => $recaptcha_response
  ];

  $options = [
    'http' => [
      'method' => 'POST',
      'header' => 'Content-type: application/x-www-form-urlencoded',
      'content' => http_build_query($data)
    ]
  ];

  $context = stream_context_create($options);
  $recaptcha_verify = file_get_contents($url, false, $context);
  $result = json_decode($recaptcha_verify);

  // If reCAPTCHA is successful, insert the suggestion into the database
  if ($result->success) {
    $stmt = $conn->prepare("INSERT INTO suggestions (name, email, suggestion, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $name, $email, $suggestion);
    $stmt->execute();

    header("Location: suggestions.php?success=1");
    exit();
  } else {
    header("Location: suggestions.php?error=1");
    exit();
  }
}
?>