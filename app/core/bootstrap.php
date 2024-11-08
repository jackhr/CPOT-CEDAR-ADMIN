<?php

// Autoload Classes
spl_autoload_register(function ($namespace) {
    // Split the namespace by backslashes to get each part
    $parts = explode('\\', $namespace);

    // Iterate over parts and convert them to lowercase unless they are the last part
    $lastKey = array_key_last($parts);
    $parts = array_map(function ($part, $idx) use ($lastKey) {
        return $idx !== $lastKey ? strtolower($part) : $part;
    }, $parts, array_keys($parts));

    // Remove the 'app' keyword from the parts as we will add it explicitly
    if ($parts[0] === 'app') array_shift($parts);

    // Prefix with the base directory
    $baseDir = dirname(__DIR__);

    // Create the file path
    $filePath = $baseDir . '/' . implode('/', $parts) . '.php';

    // Require the file if it exists
    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        error_log("Autoloader could not find file: " . $filePath);
    }
});

// Load Environment Variables (Using a Simple Implementation)
if (file_exists(__DIR__ . '/../config/.env')) {
    $lines = file(__DIR__ . '/../config/.env');
    foreach ($lines as $line) {
        if (trim($line) && strpos($line, '=') !== false) {
            putenv(trim($line));
        }
    }
}
