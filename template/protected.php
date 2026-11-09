<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'config.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// --- 1. ПРОВЕРКА JWT ТОКЕНА ---
$auth_header = $_SERVER['HTTP_AUTHORIZATION'] ?? getallheaders()['Authorization'] ?? getallheaders()['authorization'] ?? null;

if (! $auth_header || ! preg_match('/Bearer\s(\S+)/', $auth_header, $matches)) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Доступ запрещен. Токен отсутствует или некорректен."]);
    exit();
}

try {
    $token = $matches[1];
    // Декодируем токен. Если он валиден, выполнение продолжается
    $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
    $user_id       = $decoded_token->data->id; // Получаем ID пользователя из токена
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Ошибка токена: " . $e->getMessage()]);
    exit();
}

// --- 2. ОБРАБОТКА ДАННЫХ В ЗАВИСИМОСТИ ОТ МЕТОДА ---
$method         = $_SERVER['REQUEST_METHOD'];
$client_payload = [];

if ($method === 'GET') {
    // Для GET данные берем из суперглобального массива $_GET (из URL-строки)
    $client_payload = $_GET;

    // Какая-то бизнес-логика (например, поиск товара по ID)
    $itemId = $client_payload['item_id'] ?? 'не указан';

    echo json_encode([
        "success"          => true,
        "method"           => "GET",
        "message"          => "Сервер обработал ваш GET-запрос.",
        "user_id_from_jwt" => $user_id,
        "processed_result" => "Вы запросили информацию о товаре №" . htmlspecialchars($itemId),
    ]);

} elseif ($method === 'POST') {
    // Для POST (JSON) читаем сырой поток ввода php://input
    $raw_input      = file_get_contents("php://input");
    $client_payload = json_decode($raw_input, true) ?? [];

    // Какая-то бизнес-логика (например, создание новой записи)
    $itemName  = $client_payload['item_name'] ?? 'Без названия';
    $itemPrice = $client_payload['price'] ?? 0;

    echo json_encode([
        "success"          => true,
        "method"           => "POST",
        "message"          => "Сервер обработал ваш POST-запрос и сохранил данные.",
        "user_id_from_jwt" => $user_id,
        "processed_result" => [
            "status"         => "Создано",
            "item"           => htmlspecialchars($itemName),
            "price_with_tax" => $itemPrice * 1.2, // Пример обработки на сервере
        ],
    ]);
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Метод не поддерживается."]);
}
