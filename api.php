<?php
// ================= CONFIG =================
$GAS_URL = "https://script.google.com/macros/s/AKfycby1MStXa-uSwCyW-w6PBmzpLzIwuQHOy6YGSxPP9JRgDvVmBTbAR7CE7N0EfJKwQZ85Kg/exec";
$SECRET_KEY = "UJIAN_2025";

// ================= HEADER =================
header("Content-Type: application/json");

// ================= TOKEN CHECK =================
$headers = getallheaders();
$token = $headers['X-APP-KEY'] ?? '';

$action = $_REQUEST['action'] ?? '';

$adminActions = ['login','add','update','updateById'];

if (in_array($action, $adminActions)) {
    if ($token !== $SECRET_KEY) {
        http_response_code(403);
        echo json_encode([
            'status'=>false,
            'message'=>'Akses ditolak (invalid token)'
        ]);
        exit;
    }
}

// ================= FORWARD =================
$params = $_REQUEST;
$query = http_build_query($params);

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $GAS_URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => ($_SERVER['REQUEST_METHOD']==='POST'),
    CURLOPT_POSTFIELDS => $query,
    CURLOPT_TIMEOUT => 15
]);

$response = curl_exec($ch);

if ($response === false) {
    echo json_encode([
        'status'=>false,
        'message'=>'Gagal menghubungi server ujian'
    ]);
    exit;
}

curl_close($ch);
echo $response;
