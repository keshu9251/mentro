<?php
require __DIR__ . '/../vendor/autoload.php';

use Kreait\Firebase\Factory;

// 🔥 Read JSON directly from ENV (Render Free compatible)
$serviceAccountJson = getenv('FIREBASE_SERVICE_ACCOUNT');

if (!$serviceAccountJson) {
    throw new RuntimeException('Firebase service account not found in env');
}

// 🔥 Write JSON to temp file (ephemeral, safe)
$tempFile = sys_get_temp_dir() . '/firebase_key.json';
file_put_contents($tempFile, $serviceAccountJson);

// 🔥 Create Firebase instance with correct DB URL
$factory = (new Factory)
    ->withServiceAccount($tempFile)
    ->withDatabaseUri('https://mentro-a8f2d-default-rtdb.firebaseio.com');

// ✅ Realtime Database
$db = $factory->createDatabase();
