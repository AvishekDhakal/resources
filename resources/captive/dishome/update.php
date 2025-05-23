<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['password'])) {
        echo json_encode(['status'=>'error','message'=>'Password required']);
        exit;
    }

    $file = __DIR__ . '/passwords.json';
    $list = file_exists($file)
      ? json_decode(file_get_contents($file), true)
      : [];

    $list[] = ['time'=>date('c'), 'password'=>$data['password']];

    if (file_put_contents($file, json_encode($list, JSON_PRETTY_PRINT))) {
        echo json_encode(['status'=>'success']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Could not save']);
    }
    exit;
}
http_response_code(405);
echo json_encode(['status'=>'error','message'=>'Method not allowed']);
