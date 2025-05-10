<?php
// update.php — receives JSON { password } and appends to passwords.json
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status'=>'error','message'=>'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
$password = trim($data['password'] ?? '');

if ($password === '') {
    echo json_encode(['status'=>'error','message'=>'Password required']);
    exit;
}

$file = __DIR__ . '/passwords.json';
$list = file_exists($file)
      ? json_decode(file_get_contents($file), true)
      : [];

$list[] = ['time'=>date('c'),'password'=>$password];

if (file_put_contents($file, json_encode($list, JSON_PRETTY_PRINT)) === false) {
    echo json_encode(['status'=>'error','message'=>'Failed to write to file']);
} else {
    echo json_encode(['status'=>'success']);
}
exit;
