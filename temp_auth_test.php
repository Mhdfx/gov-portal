<?php
function request($url, $method = 'GET', $data = null, $cookies = '', $headers = []) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    $redirects = 0;
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }
    
    if ($cookies) curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    if (!empty($headers)) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    
    $respHeaders = substr($result, 0, $headerSize);
    $body = substr($result, $headerSize);
    
    curl_close($ch);
    
    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $respHeaders, $matches);
    $newCookies = [];
    foreach($matches[1] as $item) {
        parse_str($item, $cookie);
        foreach ($cookie as $k => $v) $newCookies[$k] = $v;
    }
    
    return [
        'code' => $httpCode,
        'body' => $body,
        'cookies_parsed' => $newCookies,
        'headers' => $respHeaders
    ];
}

$baseUrl = 'https://goveportal.keromultiservice.com';
$resLogin = request("$baseUrl/login");
preg_match('/<meta name="csrf-token" content="([^"]+)">/', $resLogin['body'], $csrfMatch);
$csrfToken = $csrfMatch[1] ?? '';

$cookieStr = '';
foreach ($resLogin['cookies_parsed'] as $k => $v) $cookieStr .= "$k=$v; ";

// Login
$loginData = ['_token' => $csrfToken, 'username' => 'admin', 'password' => 'password'];
$resLoginPost = request("$baseUrl/login", 'POST', $loginData, $cookieStr);
foreach ($resLoginPost['cookies_parsed'] as $k => $v) $cookieStr .= "$k=$v; ";

// Check /admin/dashboard
$resDash = request("$baseUrl/admin/dashboard", 'GET', null, $cookieStr);
echo "Admin Dashboard code: " . $resDash['code'] . "\n";

// Check a form route as authenticated
$resForm = request("$baseUrl/forms/investment", 'GET', null, $cookieStr);
echo "Investment Form code: " . $resForm['code'] . "\n";
