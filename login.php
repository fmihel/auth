<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once 'config.php';
use Firebase\JWT\JWT;

// Имитируем получение POST-данных (например, из JSON-запроса)
$input_data = json_decode(file_get_contents("php://input"), true);

$username = $input_data['username'] ?? null;
$password = $input_data['password'] ?? null;

// Имитация проверки пользователя (в реальности здесь будет запрос к БД)
if ($username === 'admin' && $password === 'secret123') {

    $issued_at = time();
    $expire_at = $issued_at + JWT_EXPIRE;

    // Формируем payload (полезную нагрузку)
    $payload = [
        "iss"  => JWT_ISSUER,
        "iat"  => $issued_at,
        "exp"  => $expire_at,
        "data" => [
            "id"       => 1,
            "username" => $username,
            "role"     => "administrator",
        ],
    ];

    // Генерируем токен
    $jwt = JWT::encode($payload, JWT_SECRET, 'HS256');

    // Отправляем успешный ответ с токеном
    http_response_code(200);
    echo json_encode([
        "success"  => true,
        "message"  => "Успешный вход.",
        "token"    => $jwt,
        "expireAt" => $expire_at,
    ]);

} else {
    // Если логин или пароль не подошли
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Неверный логин или пароль.",
    ]);
}
