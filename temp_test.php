<?php

function request($url, $method = 'GET', $data = null, $cookies = '', $headers = []) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
    }
    
    if ($cookies) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    }
    
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    
    $respHeaders = substr($result, 0, $headerSize);
    $body = substr($result, $headerSize);
    
    curl_close($ch);
    
    // Extract cookies
    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $respHeaders, $matches);
    $newCookies = [];
    foreach($matches[1] as $item) {
        parse_str($item, $cookie);
        foreach ($cookie as $k => $v) {
            $newCookies[$k] = $v;
        }
    }
    
    return [
        'code' => $httpCode,
        'body' => $body,
        'cookies_parsed' => $newCookies,
        'headers' => $respHeaders
    ];
}

$baseUrl = 'https://goveportal.keromultiservice.com';
$results = [];

echo "Starting automated tests for: $baseUrl\n";
echo "--------------------------------------------------------\n";

// 1. Check Homepage
echo "1. Checking Homepage (/)\n";
$res = request("$baseUrl/");
$results['Homepage'] = $res['code'] === 200 ? 'SUCCESS' : "FAILED (Code: {$res['code']})";
if (strpos($res['body'], 'Whoops') !== false || strpos($res['body'], 'Exception') !== false) {
    $results['Homepage'] .= ' - Contains Error Text';
}

// 2. Extrac CSRF and Cookies from Login Page
echo "2. Fetching Login Page to extract CSRF\n";
$res = request("$baseUrl/login");
$results['Login_Page_Load'] = $res['code'] === 200 ? 'SUCCESS' : "FAILED (Code: {$res['code']})";

preg_match('/<meta name="csrf-token" content="([^"]+)">/', $res['body'], $csrfMatch);
$csrfToken = $csrfMatch[1] ?? null;

$cookieStr = '';
foreach ($res['cookies_parsed'] as $k => $v) {
    $cookieStr .= "$k=$v; ";
}

if (!$csrfToken) {
    echo "Failed to get CSRF token!\n";
} else {
    echo "CSRF Token found.\n";
}

// 3. Attempt Login
echo "3. Attempting Login\n";
$loginData = [
    '_token' => $csrfToken,
    'username' => 'admin',
    'password' => 'password'
];

$resLogin = request("$baseUrl/login", 'POST', $loginData, $cookieStr);
$results['Login_POST'] = in_array($resLogin['code'], [302, 200]) ? 'SUCCESS' : "FAILED (Code: {$resLogin['code']})";

// Merge new session cookies
foreach ($resLogin['cookies_parsed'] as $k => $v) {
    $cookieStr .= "$k=$v; ";
}

// 4. Access Dashboard
echo "4. Checking Dashboard (/dashboard)\n";
$resDash = request("$baseUrl/dashboard", 'GET', null, $cookieStr);
$results['Dashboard_Access'] = $resDash['code'] === 200 ? 'SUCCESS' : "FAILED (Code: {$resDash['code']})";
if ($resDash['code'] === 302) {
    $results['Dashboard_Access'] = 'FAILED (Redirected to login usually means auth failed)';
}

// 5. Test specific forms and pages
$pagesToTest = [
    '/forms/investment' => 'Investment Form',
    '/forms/project-carrier' => 'Project Carrier Form',
    '/forms/auto-entrepreneur' => 'Auto-Entrepreneur Form',
    '/about' => 'About Page',
    '/register' => 'Register Page'
];

foreach ($pagesToTest as $path => $name) {
    echo "Testing $name ($path)\n";
    $resPage = request("$baseUrl$path", 'GET', null, $cookieStr);
    
    $status = $resPage['code'] === 200 ? 'SUCCESS' : "FAILED (Code: {$resPage['code']})";
    
    if ($resPage['code'] === 500) {
        preg_match('/<title>(.*?)<\/title>/is', $resPage['body'], $titleMatch);
        $title = trim(strip_tags($titleMatch[1] ?? '500 Server Error'));
        $status .= " - $title";
    }
    
    $results[$name] = $status;
}

echo "--------------------------------------------------------\n";
echo "TEST RESULTS:\n";
foreach ($results as $name => $status) {
    echo str_pad($name, 25) . " : " . $status . "\n";
}
echo "--------------------------------------------------------\n";
