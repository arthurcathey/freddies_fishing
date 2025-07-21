<?php

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  http_response_code(405);
  echo json_encode(['success' => false, 'message' => 'Only POST requests are allowed.']);
  exit;
}

// Optional honeypot (invisible field to catch bots)
if (!empty($_POST['nickname'])) {
  http_response_code(403);
  echo json_encode(['success' => false, 'message' => 'Spam detected.']);
  exit;
}

// Sanitize and validate input
$name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

// Validate required fields
if (!$name || !$email || !$message) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
  exit;
}

// Email configuration
$to = "";
$subject = "New message from $name via website contact form";
$body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
$headers = "From: $email\r\nReply-To: $email\r\n";

// Send the email
if (mail($to, $subject, $body, $headers)) {
  echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
} else {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Failed to send message.']);
}
