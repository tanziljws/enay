<?php

/**
 * Script Test Jurusan/Majors
 * Jalankan: php test-jurusan.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST JURUSAN/MAJORS ===\n\n";

// Test 1: Check Majors
echo "1. Checking Majors...\n";
$majorsCount = \App\Models\Major::count();
echo "   Total majors: $majorsCount\n";

if ($majorsCount > 0) {
    $majors = \App\Models\Major::all();
    
    foreach ($majors as $major) {
        echo "\n   Major: {$major->name} ({$major->full_name})\n";
        echo "   Code: {$major->code}\n";
        echo "   Image: " . ($major->image ?? 'NULL') . "\n";
        
        if ($major->image) {
            $fullPath = storage_path('app/public/' . $major->image);
            $exists = file_exists($fullPath);
            echo "   File exists: " . ($exists ? "✓ YES" : "✗ NO") . "\n";
            echo "   Full path: $fullPath\n";
            
            if (!$exists) {
                echo "   ⚠ File not found! Admin needs to upload image.\n";
            }
        } else {
            echo "   ⚠ No image set. Admin needs to upload image.\n";
        }
    }
} else {
    echo "   ⚠ No majors found. Please add via admin dashboard.\n";
}

echo "\n";

// Test 2: Check Storage
echo "2. Checking Storage Directories...\n";
$storageDirs = [
    'storage/app/public' => storage_path('app/public'),
    'storage/app/public/majors' => storage_path('app/public/majors'),
    'public/storage' => public_path('storage'),
];

foreach ($storageDirs as $name => $path) {
    $exists = file_exists($path);
    echo "   $name: " . ($exists ? "✓ EXISTS" : "✗ NOT FOUND") . "\n";
}

echo "\n";

// Test 3: List Files in majors directory
echo "3. Files in storage/app/public/majors/...\n";
$majorsDir = storage_path('app/public/majors');
if (file_exists($majorsDir)) {
    $files = scandir($majorsDir);
    $imageFiles = array_filter($files, function($file) {
        return !in_array($file, ['.', '..']);
    });
    
    if (count($imageFiles) > 0) {
        foreach ($imageFiles as $file) {
            echo "   - $file\n";
        }
    } else {
        echo "   (empty directory)\n";
    }
} else {
    echo "   ⚠ Directory not found\n";
}

echo "\n";

// Test 4: Check URL Generation
echo "4. Testing URL Generation...\n";
if ($majorsCount > 0) {
    $major = \App\Models\Major::first();
    if ($major->image) {
        $url = asset('storage/' . $major->image);
        echo "   Image URL: $url\n";
        echo "   Expected path: storage/{$major->image}\n";
    }
}

echo "\n";

// Summary
echo "=== SUMMARY ===\n";
$issues = [];

if ($majorsCount === 0) {
    $issues[] = "No majors found - Add via admin dashboard";
}

$majorsWithoutImage = \App\Models\Major::whereNull('image')->orWhere('image', '')->count();
if ($majorsWithoutImage > 0) {
    $issues[] = "$majorsWithoutImage major(s) without image - Upload via admin dashboard";
}

if (!file_exists(public_path('storage'))) {
    $issues[] = "Storage link missing - Run: php artisan storage:link";
}

if (empty($issues)) {
    echo "✓ All checks passed!\n";
    echo "\nImages should display at: http://127.0.0.1:8000/jurusan\n";
} else {
    echo "⚠ Issues found:\n";
    foreach ($issues as $i => $issue) {
        echo ($i + 1) . ". $issue\n";
    }
}

echo "\n";
echo "=== TROUBLESHOOTING ===\n";
echo "If images still don't show:\n";
echo "1. Login as admin: http://127.0.0.1:8000/admin/login\n";
echo "2. Go to Majors menu\n";
echo "3. Edit each major and upload image\n";
echo "4. Make sure to save\n";
echo "5. Refresh /jurusan page\n";
echo "6. Check browser console (F12) for errors\n";

echo "\n";
