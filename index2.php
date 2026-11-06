<?php

require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// 1. Конфигурация
// Секретный ключ (храните его в строго секрете, например, в .env)
$secret_key  = "YOUR_SUPER_SECRET_KEY_12345";
$issued_at   = time();
$expire_at   = $issued_at + 3600; // Токен сгорит через 1 час (3600 секунд)
$server_name = "yourdomain.com";  // Идентификатор вашего сервера (iss)

// ==========================================
// ЧАСТЬ 1: СИМУЛЯЦИЯ ВХОДА (ГЕНЕРАЦИЯ ТОКЕНА)
// ==========================================
echo '<body style="background:black;color:lime;font-size:1.5rem"';
echo "<h3>1. Авторизация пользователя и генерация JWT</h3>";

// Данные пользователя, которые мы якобы проверили в базе данных
$user_id  = 42;
$username = "ivan_ivanov";

// Полезная нагрузка (Payload) токена
$payload = [
    "iss"  => $server_name, // Кто выпустил токен
    "iat"  => $issued_at,   // Время выпуска токена
    "exp"  => $expire_at,   // Время окончания действия токена
    "data" => [             // Произвольные данные пользователя
        "id"       => $user_id,
        "username" => $username,
    ],
];

// Генерируем JWT токен (в PHP 7 используется алгоритм HS256)
$jwt = JWT::encode($payload, $secret_key, 'HS256');

echo "<strong>Сгенерированный JWT Токен:</strong><br><textarea rows='4' cols='80' readonly>" . $jwt . "</textarea><br><br>";

// ==========================================
// ЧАСТЬ 2: СИМУЛЯЦИЯ ЗАПРОСА К ЗАЩИЩЕННОЙ СТРАНИЦЕ
// ==========================================
echo "<h3>2. Проверка JWT токена при доступе к защищенным данным</h3>";

// Обычно клиент присылает токен в заголовке: Authorization: Bearer <token>
// Для примера просто возьмем созданную ранее переменную $jwt
$token_to_verify = $jwt;

try {
    // Декодируем и проверяем токен. 
    // Если токен изменен, просрочен или подписан другим ключом — выбросится Exception
    $decoded = JWT::decode($token_to_verify, new Key($secret_key, 'HS256'));

    // Если код дошел сюда — токен валиден!
    echo "<span style='color: green; font-weight: bold;'>Доступ разрешен!</span><br>";
    echo "Привет, " . $decoded->data->username . " (ID: " . $decoded->data->id . ")<br>";

    // Выведем полную структуру декодированного токена
    echo "<pre>";
    print_style_data($decoded);
    echo "</pre>";

} catch (Exception $e) {
    // Если токен невалиден/просрочен
    http_response_code(401);
    echo "<span style='color: red; font-weight: bold;'>Доступ запрещен:</span> " . $e->getMessage();
}

// Вспомогательная функция для красивого вывода
function print_style_data($data)
{
    echo "Содержимое JWT:\n";
    print_r($data);
}
