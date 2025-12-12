<?php
require_once 'includes/config.php';
require_once 'includes/Database.php';

function test_api($url, $method = 'GET', $data = []) {
    $curl = curl_init();
    
    // Adjust URL to point to localhost
    $url = "http://localhost/OficinaDoCabeloSite/" . $url;

    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
    ];

    if ($method === 'POST') {
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = http_build_query($data);
    }

    curl_setopt_array($curl, $options);
    
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    curl_close($curl);
    
    echo "Testing $url ($method)...\n";
    echo "HTTP Code: $httpCode\n";
    echo "Response: $response\n\n";
}

echo "Starting Tests...\n\n";

// 1. Test Get Slots
test_api('index.php?route=api/slots&barber=Bruno%20Martins&date=' . date('Y-m-d'));

// 2. Test Book (Fail - Missing Fields)
test_api('index.php?route=api/book', 'POST', []);

echo "Note: Full booking test requires valid CSRF token which is hard to simulate here without session.\n";
echo "Manual testing via browser is recommended for full flow.\n";
?>
