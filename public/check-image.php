<?php
// Simple PHP test untuk cek gambar
$imagePath = __DIR__ . '/../storage/app/public/gallery/Sh1wXXICIie9kqBjpKc2hDTsWOOI6KTvGGLgvG9e.jpg';
$publicPath = __DIR__ . '/storage/gallery/Sh1wXXICIie9kqBjpKc2hDTsWOOI6KTvGGLgvG9e.jpg';

echo "<h1>Image Check</h1>";

echo "<h2>1. File di Storage</h2>";
echo "Path: $imagePath<br>";
echo "Exists: " . (file_exists($imagePath) ? "YES" : "NO") . "<br>";
if (file_exists($imagePath)) {
    echo "Size: " . filesize($imagePath) . " bytes<br>";
    echo "Readable: " . (is_readable($imagePath) ? "YES" : "NO") . "<br>";
}

echo "<h2>2. File via Public Symlink</h2>";
echo "Path: $publicPath<br>";
echo "Exists: " . (file_exists($publicPath) ? "YES" : "NO") . "<br>";
if (file_exists($publicPath)) {
    echo "Size: " . filesize($publicPath) . " bytes<br>";
}

echo "<h2>3. Symlink Check</h2>";
$symlinkPath = __DIR__ . '/storage';
echo "Symlink path: $symlinkPath<br>";
echo "Exists: " . (file_exists($symlinkPath) ? "YES" : "NO") . "<br>";
echo "Is link: " . (is_link($symlinkPath) ? "YES" : "NO") . "<br>";
if (is_link($symlinkPath)) {
    echo "Target: " . readlink($symlinkPath) . "<br>";
}

echo "<h2>4. Test Display Image</h2>";
echo "<img src='/storage/gallery/Sh1wXXICIie9kqBjpKc2hDTsWOOI6KTvGGLgvG9e.jpg' style='max-width:500px' onerror='alert(\"Image failed to load!\")' onload='alert(\"Image loaded successfully!\")'>";

echo "<h2>5. Direct Image Link</h2>";
echo "<a href='/storage/gallery/Sh1wXXICIie9kqBjpKc2hDTsWOOI6KTvGGLgvG9e.jpg' target='_blank'>Click here to open image directly</a>";

echo "<h2>6. Server Info</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "<br>";
echo "Current Dir: " . __DIR__ . "<br>";
?>
