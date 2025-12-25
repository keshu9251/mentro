<?php
require __DIR__ . '/../vendor/autoload.php';

use Kreait\Firebase\Factory;

// Path to service account JSON (Render env var)
$credentialsPath = getenv('GOOGLE_APPLICATION_CREDENTIALS');

if (!$credentialsPath || !file_exists($credentialsPath)) {
    throw new RuntimeException('Firebase credentials file not found');
}

// 🔥 IMPORTANT: SET DATABASE URI EXPLICITLY
$factory = (new Factory)
    ->withServiceAccount($credentialsPath)
    ->withDatabaseUri('https://mentro-a8f2d-default-rtdb.firebaseio.com');

// ✅ USE REALTIME DATABASE
$db = $factory->createDatabase();
