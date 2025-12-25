<?php
require __DIR__ . '/../vendor/autoload.php';

use Kreait\Firebase\Factory;

// Render Free compatible: read JSON from env
$serviceAccountJson = getenv('FIREBASE_SERVICE_ACCOUNT');

if (!$serviceAccountJson) {
    throw new RuntimeException('Firebase service account not found in env');
}

// Write to temp file
$tempFile = sys_get_temp_dir() . '/firebase_key.json';
file_put_contents($tempFile, $serviceAccountJson);

// ✅ CORRECT DATABASE URL (asia-southeast1)
$factory = (new Factory)
    ->withServiceAccount($tempFile)
    ->withDatabaseUri('https://mentro-a8f2d-default-rtdb.asia-southeast1.firebasedatabase.app');

// Realtime Database instance
$db = $factory->createDatabase();
