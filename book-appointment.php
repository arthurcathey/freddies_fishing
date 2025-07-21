<?php

$date = htmlspecialchars(trim($_POST['selectedDate'] ?? ''));
$time = htmlspecialchars(trim($_POST['selectedTime'] ?? ''));

if (empty($date) || empty($time)) {
  http_response_code(400);
  echo "Missing date or time.";
  exit;
}

$to = 'arthur.cathey@gmail.com'; 
$subject = 'New Fishing Appointment Booking';
$message = "You have a new booking request:\n\nDate: $date\nTime: $time";
$headers = "From: bookings@yourdomain.com";

$success = mail($to, $subject, $message, $headers);

if ($success) {
  echo "Booking received!";
} else {
  http_response_code(500);
  echo "Failed to send booking.";
}
?>
