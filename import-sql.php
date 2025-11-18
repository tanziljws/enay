<?php

// Database configuration
$host = 'shinkansen.proxy.rlwy.net';
$port = 50793;
$database = 'railway';
$username = 'root';
$password = 'BZkZuCZeZfWIGLRTFxmupNAYWkvqaOdb';

// SQL file path
$sqlFile = '/Users/tanziljws/Downloads/ujikomkom-nana (3) (1).sql';

try {
    // Connect to database
    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "Connected to database successfully!\n";
    
    // Read SQL file
    if (!file_exists($sqlFile)) {
        die("SQL file not found: {$sqlFile}\n");
    }
    
    echo "Reading SQL file...\n";
    $sql = file_get_contents($sqlFile);
    
    // Remove comments and split by semicolon
    $sql = preg_replace('/--.*$/m', '', $sql);
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
    
    // Split into individual statements
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($stmt) {
            return !empty($stmt) && strlen($stmt) > 10;
        }
    );
    
    echo "Found " . count($statements) . " SQL statements to execute.\n";
    echo "Starting import...\n\n";
    
    $executed = 0;
    $errors = 0;
    
    foreach ($statements as $index => $statement) {
        try {
            $pdo->exec($statement);
            $executed++;
            
            if (($index + 1) % 50 == 0) {
                echo "Progress: " . ($index + 1) . " statements executed...\n";
            }
        } catch (PDOException $e) {
            $errors++;
            // Skip errors for DROP/CREATE if table exists, or duplicate key errors
            if (strpos($e->getMessage(), 'already exists') === false && 
                strpos($e->getMessage(), 'Duplicate entry') === false &&
                strpos($e->getMessage(), 'Unknown table') === false) {
                echo "Error in statement " . ($index + 1) . ": " . $e->getMessage() . "\n";
                echo "Statement: " . substr($statement, 0, 100) . "...\n\n";
            }
        }
    }
    
    echo "\n========================================\n";
    echo "Import completed!\n";
    echo "Executed: {$executed} statements\n";
    echo "Errors (skipped): {$errors}\n";
    echo "========================================\n";
    
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage() . "\n");
}

