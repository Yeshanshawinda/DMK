<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $botToken = '8346980291:AAFnq9490mZUPFINCz6h4avp2zUWj4sV0g4';
    $chatId = '1192272463';

    $message = $data['message'];

    $url = "https://api.telegram.org/bot$botToken/sendMessage?" . http_build_query([
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'Markdown'
    ]);

    $opts = [
        'http' => [
            'method' => 'GET',
            'timeout' => 10
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
        ]
    ];

    $context = stream_context_create($opts);
    $response = @file_get_contents($url, false, $context);

    $responseData = json_decode($response, true);
    if ($response !== false && isset($responseData['ok']) && $responseData['ok'] === true) {
        echo json_encode(['success' => true]);
    } else {
        $errorMsg = isset($responseData['description']) ? $responseData['description'] : 'Telegram API request failed';
        echo json_encode(['success' => false, 'error' => $errorMsg]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
