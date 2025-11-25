<?php
/**
 * Test CAPTCHA API Script
 * 
 * Usage: php test-captcha-api.php
 * 
 * This script tests:
 * 1. Generate CAPTCHA (should return same CAPTCHA if called multiple times without refresh)
 * 2. Verify CAPTCHA with correct input
 * 3. Verify CAPTCHA with wrong input
 * 4. Test session persistence
 */

$baseUrl = 'https://enay-production.up.railway.app';
// Or use local: $baseUrl = 'http://localhost:8000';

echo "🧪 Testing CAPTCHA API\n";
echo "====================\n\n";

// Initialize cURL session
$cookieFile = sys_get_temp_dir() . '/captcha_test_cookies_' . uniqid() . '.txt';
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// Test 1: Generate CAPTCHA (first time)
echo "📝 Test 1: Generate CAPTCHA (first time)\n";
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/captcha/generate');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $headerSize);
$body = substr($response, $headerSize);

echo "HTTP Code: $httpCode\n";
$data = json_decode($body, true);
if ($data) {
    echo "CAPTCHA: " . $data['captcha'] . "\n";
    echo "Cached: " . ($data['cached'] ?? 'false') . "\n";
    $captchaCode = $data['captcha'];
} else {
    echo "❌ Error: Invalid JSON response\n";
    echo "Response: $body\n";
    exit(1);
}
echo "\n";

// Test 2: Generate CAPTCHA again (should return same CAPTCHA)
echo "📝 Test 2: Generate CAPTCHA again (should return SAME CAPTCHA)\n";
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/captcha/generate');
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$body = substr($response, $headerSize);

echo "HTTP Code: $httpCode\n";
$data = json_decode($body, true);
if ($data) {
    echo "CAPTCHA: " . $data['captcha'] . "\n";
    echo "Cached: " . ($data['cached'] ?? 'false') . "\n";
    if ($data['captcha'] === $captchaCode) {
        echo "✅ PASS: Same CAPTCHA returned (session preserved)\n";
    } else {
        echo "❌ FAIL: Different CAPTCHA returned (session was reset!)\n";
    }
} else {
    echo "❌ Error: Invalid JSON response\n";
}
echo "\n";

// Test 3: Verify CAPTCHA with CORRECT input
echo "📝 Test 3: Verify CAPTCHA with CORRECT input\n";
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/captcha/verify');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['captcha' => $captchaCode]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$body = substr($response, $headerSize);

echo "HTTP Code: $httpCode\n";
$data = json_decode($body, true);
if ($data) {
    if ($data['success'] ?? false) {
        echo "✅ PASS: CAPTCHA verified successfully\n";
    } else {
        echo "❌ FAIL: CAPTCHA verification failed\n";
        echo "Message: " . ($data['message'] ?? 'Unknown error') . "\n";
    }
} else {
    echo "❌ Error: Invalid JSON response\n";
    echo "Response: $body\n";
}
echo "\n";

// Test 4: Verify CAPTCHA with WRONG input (should fail)
echo "📝 Test 4: Verify CAPTCHA with WRONG input (should fail)\n";
// Generate new CAPTCHA first
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/captcha/generate?refresh=true');
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);
$response = curl_exec($ch);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$body = substr($response, $headerSize);
$data = json_decode($body, true);
$newCaptchaCode = $data['captcha'] ?? '';

// Try to verify with wrong code
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/captcha/verify');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['captcha' => 'WRONG']));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$body = substr($response, $headerSize);

echo "HTTP Code: $httpCode\n";
$data = json_decode($body, true);
if ($data) {
    if (!($data['success'] ?? false)) {
        echo "✅ PASS: CAPTCHA correctly rejected wrong input\n";
        echo "Message: " . ($data['message'] ?? 'Unknown error') . "\n";
    } else {
        echo "❌ FAIL: CAPTCHA should have been rejected\n";
    }
} else {
    echo "❌ Error: Invalid JSON response\n";
}
echo "\n";

// Test 5: Check cookies
echo "📝 Test 5: Check cookies\n";
if (file_exists($cookieFile)) {
    $cookies = file_get_contents($cookieFile);
    if (strpos($cookies, 'laravel_session') !== false || strpos($cookies, 'galeri-sekolah-enay-session') !== false || strpos($cookies, 'XSRF-TOKEN') !== false) {
        echo "✅ PASS: Session cookie found\n";
        echo "Cookie file: $cookieFile\n";
    } else {
        echo "❌ FAIL: No session cookie found!\n";
        echo "Cookie file: $cookieFile\n";
        echo "Cookies file content:\n$cookies\n";
    }
} else {
    echo "❌ FAIL: Cookie file not created!\n";
    echo "This means cookies are NOT being set by the server.\n";
    echo "Check:\n";
    echo "  1. SESSION_SECURE_COOKIE=true in Railway\n";
    echo "  2. SESSION_SAME_SITE=lax in Railway\n";
    echo "  3. APP_URL=https://... in Railway\n";
}
echo "\n";

// Cleanup
if (file_exists($cookieFile)) {
    unlink($cookieFile);
}

curl_close($ch);

echo "====================\n";
echo "✅ Test completed!\n";

