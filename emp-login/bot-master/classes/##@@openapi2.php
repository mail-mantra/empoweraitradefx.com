<?php
$apiKey = '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'; // Replace with your actual API key
$apiUrl = 'https://api.openai.com/v1/chat/completions';

$content = "You are a helpful assistant."; // System message
$message = "Tell me a joke."; // User message

$messages = [
    ["role" => "system", "content" => $content],
    ["role" => "user", "content" => $message]
];

$data = [
    "model" => "gpt-3.5-turbo", // or "gpt-3.5-turbo"
    "messages" => $messages,
    "temperature" => 0.7
];

$headers = [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    $decoded = json_decode($response, true);
    echo "AI Response:\n";
    echo $decoded['choices'][0]['message']['content'];
}

curl_close($ch);
?>
