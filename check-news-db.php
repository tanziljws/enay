<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "===========================================\n";
echo "CEK DATA BERITA DI DATABASE\n";
echo "===========================================\n\n";

$news = \App\Models\News::select('id', 'title', 'image', 'published_at')
    ->orderBy('published_at', 'desc')
    ->get();

echo "Total berita: " . $news->count() . "\n\n";

foreach ($news as $item) {
    echo "ID: " . $item->id . "\n";
    echo "Title: " . $item->title . "\n";
    echo "Image: " . ($item->image ?? 'NULL') . "\n";
    echo "Full Path: " . ($item->image ? asset('storage/' . $item->image) : 'N/A') . "\n";
    echo "File exists in storage: " . (file_exists(storage_path('app/public/' . $item->image)) ? 'YES' : 'NO') . "\n";
    echo "File exists in public: " . (file_exists(public_path('storage/' . $item->image)) ? 'YES' : 'NO') . "\n";
    echo "-------------------------------------------\n";
}

echo "\n===========================================\n";
echo "CEK SELESAI\n";
echo "===========================================\n";
