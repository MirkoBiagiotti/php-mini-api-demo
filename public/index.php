<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
use App\DB;
use App\ItemRepository;

header('Content-Type: application/json');

// Poor-man's router
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path   = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

$db = DB::conn();
$repo = new ItemRepository($db);

function send($code, $data) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($path === '/health') {
    send(200, ['ok'=>true]);
}

if ($path === '/items' && $method === 'GET') {
    send(200, $repo->all());
}

if ($path === '/items' && $method === 'POST') {
    $body = json_decode(file_get_contents('php://input') ?: '[]', true);
    if (!is_array($body) || !isset($body['title']) || trim($body['title'])==='') {
        send(400, ['error'=>'invalid_body']);
    }
    $id = $repo->create((string)$body['title']);
    send(201, ['id'=>$id, 'title'=>$body['title']]);
}

if ($path === '/invoices' && $method === 'GET') {
    // mock invoices
    send(200, [
        ['id'=>1,'number'=>'2025/0001','total'=>123.45,'currency'=>'EUR'],
        ['id'=>2,'number'=>'2025/0002','total'=>67.89,'currency'=>'EUR'],
    ]);
}

// 404
send(404, ['error'=>'not_found']);