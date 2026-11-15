<?php
// Simple contact form handler for the Marketish site.
// Sends submissions by email without exposing the recipient address publicly.

header('Content-Type: application/json');

$recipient = "yashsh435@gmail.com";

function clean($value) {
    $value = trim($value);
    $value = str_replace(["\r", "\n"], '', $value); // header injection guard
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$name    = isset($_POST['name']) ? clean($_POST['name']) : '';
$email   = isset($_POST['email']) ? trim($_POST['email']) : '';
$company = isset($_POST['company']) ? clean($_POST['company']) : '';
$message = isset($_POST['message']) ? clean($_POST['message']) : '';

if (!$name || !$email || !$message || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

$subject = "New inquiry from Marketish site — $name";
$body = "Name: $name\n" .
        "Email: $email\n" .
        "Company: " . ($company ?: '-') . "\n\n" .
        "Message:\n$message\n";

$headers = "From: Marketish Website <no-reply@marketish.in>\r\n" .
           "Reply-To: " . filter_var($email, FILTER_SANITIZE_EMAIL) . "\r\n" .
           "Content-Type: text/plain; charset=UTF-8\r\n";

$sent = @mail($recipient, $subject, $body, $headers);

if ($sent) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Could not send message"]);
}
