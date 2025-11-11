<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG GALLERY ===\n\n";

// Check all items
$allItems = \App\Models\GalleryItem::all();
echo "Total items in database: " . $allItems->count() . "\n\n";

foreach ($allItems as $item) {
    echo "ID: {$item->id}\n";
    echo "Title: {$item->title}\n";
    echo "Image: {$item->image}\n";
    echo "Status: {$item->status}\n";
    
    $fullPath = storage_path('app/public/' . $item->image);
    echo "File exists: " . (file_exists($fullPath) ? "YES" : "NO") . "\n";
    
    $url = asset('storage/' . $item->image);
    echo "URL: $url\n";
    echo "---\n\n";
}

// Check published only
$published = \App\Models\GalleryItem::where('status', 'published')->get();
echo "\nPublished items: " . $published->count() . "\n";

// Test URL generation
if ($published->count() > 0) {
    $first = $published->first();
    echo "\nFirst published item:\n";
    echo "Title: {$first->title}\n";
    echo "Image column: {$first->image}\n";
    echo "Full path: " . storage_path('app/public/' . $first->image) . "\n";
    echo "URL: " . asset('storage/' . $first->image) . "\n";
    echo "File exists: " . (file_exists(storage_path('app/public/' . $first->image)) ? "YES" : "NO") . "\n";
}
