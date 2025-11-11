<?php

/**
 * Script Test Galeri & Fitur Interaksi
 * Jalankan: php test-galeri.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST GALERI & FITUR INTERAKSI ===\n\n";

// Test 1: Check Gallery Items
echo "1. Checking Gallery Items...\n";
$itemsCount = \App\Models\GalleryItem::count();
echo "   Total items: $itemsCount\n";

if ($itemsCount > 0) {
    $firstItem = \App\Models\GalleryItem::first();
    echo "   First item: {$firstItem->title}\n";
    echo "   Image path: {$firstItem->image}\n";
    
    $fullPath = storage_path('app/public/' . $firstItem->image);
    $exists = file_exists($fullPath);
    echo "   File exists: " . ($exists ? "✓ YES" : "✗ NO") . "\n";
    echo "   Full path: $fullPath\n";
} else {
    echo "   ⚠ No gallery items found. Please upload via admin dashboard.\n";
}

echo "\n";

// Test 2: Check Storage Link
echo "2. Checking Storage Link...\n";
$publicStorage = public_path('storage');
$storageExists = file_exists($publicStorage);
echo "   Storage link exists: " . ($storageExists ? "✓ YES" : "✗ NO") . "\n";
if (!$storageExists) {
    echo "   ⚠ Run: php artisan storage:link\n";
}

echo "\n";

// Test 3: Check Tables
echo "3. Checking Database Tables...\n";
$tables = [
    'gallery_reactions' => \App\Models\GalleryReaction::class,
    'gallery_user_comments' => \App\Models\GalleryUserComment::class,
    'users' => \App\Models\User::class,
    'downloads' => \App\Models\Download::class,
];

foreach ($tables as $tableName => $model) {
    try {
        $count = $model::count();
        echo "   $tableName: ✓ EXISTS ($count records)\n";
    } catch (\Exception $e) {
        echo "   $tableName: ✗ ERROR - " . $e->getMessage() . "\n";
    }
}

echo "\n";

// Test 4: Check Users
echo "4. Checking Users...\n";
$adminCount = \App\Models\User::where('role', 'admin')->count();
$userCount = \App\Models\User::where('role', 'user')->count();
echo "   Admins: $adminCount\n";
echo "   Users: $userCount\n";

if ($adminCount === 0) {
    echo "   ⚠ No admin found. Run: php artisan db:seed --class=AdminSeeder\n";
}

echo "\n";

// Test 5: Check Routes
echo "5. Checking Routes...\n";
$routes = [
    'gallery.reaction',
    'gallery.comment',
    'gallery.comments',
    'gallery.download',
];

foreach ($routes as $routeName) {
    try {
        $url = route($routeName, 1);
        echo "   $routeName: ✓ EXISTS\n";
    } catch (\Exception $e) {
        echo "   $routeName: ✗ NOT FOUND\n";
    }
}

echo "\n";

// Test 6: Check Config
echo "6. Checking Configuration...\n";
$recaptchaSiteKey = config('recaptcha.site_key');
$recaptchaSkipLocal = config('recaptcha.skip_local');
echo "   reCAPTCHA Site Key: " . ($recaptchaSiteKey ? "✓ SET" : "✗ NOT SET") . "\n";
echo "   Skip CAPTCHA in Local: " . ($recaptchaSkipLocal ? "✓ YES" : "✗ NO") . "\n";

if (!$recaptchaSiteKey && !$recaptchaSkipLocal) {
    echo "   ⚠ Set RECAPTCHA_SKIP_LOCAL=true in .env for local development\n";
}

echo "\n";

// Test 7: Sample Data
echo "7. Sample Data...\n";
if ($itemsCount > 0) {
    $item = \App\Models\GalleryItem::first();
    $likes = \App\Models\GalleryReaction::where('gallery_item_id', $item->id)
        ->where('type', 'like')->count();
    $dislikes = \App\Models\GalleryReaction::where('gallery_item_id', $item->id)
        ->where('type', 'dislike')->count();
    $comments = \App\Models\GalleryUserComment::where('gallery_item_id', $item->id)->count();
    
    echo "   Item: {$item->title}\n";
    echo "   Likes: $likes\n";
    echo "   Dislikes: $dislikes\n";
    echo "   Comments: $comments\n";
}

echo "\n";

// Summary
echo "=== SUMMARY ===\n";
$issues = [];

if ($itemsCount === 0) {
    $issues[] = "No gallery items - Upload via admin dashboard";
}

if (!$storageExists) {
    $issues[] = "Storage link missing - Run: php artisan storage:link";
}

if ($adminCount === 0) {
    $issues[] = "No admin account - Run: php artisan db:seed --class=AdminSeeder";
}

if (!$recaptchaSiteKey && !$recaptchaSkipLocal) {
    $issues[] = "CAPTCHA not configured - Set RECAPTCHA_SKIP_LOCAL=true in .env";
}

if (empty($issues)) {
    echo "✓ All checks passed! Gallery should work properly.\n";
    echo "\nNext steps:\n";
    echo "1. Start server: php artisan serve\n";
    echo "2. Open: http://127.0.0.1:8000/galeri\n";
    echo "3. Register user: http://127.0.0.1:8000/register\n";
    echo "4. Test like, comment, download features\n";
} else {
    echo "⚠ Issues found:\n";
    foreach ($issues as $i => $issue) {
        echo ($i + 1) . ". $issue\n";
    }
}

echo "\n";
