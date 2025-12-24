<?php

require __DIR__ . '/../vendor/autoload.php';

use Kreait\Firebase\Factory;

// Get credentials path from environment variable (Render Free compatible)
$credentialsPath = getenv('GOOGLE_APPLICATION_CREDENTIALS');

if (!$credentialsPath || !file_exists($credentialsPath)) {
    throw new RuntimeException('Firebase credentials file not found');
}

// Create Firebase instance
$factory = (new Factory)->withServiceAccount($credentialsPath);

$firebase = $factory->createFirestore();
$db = $firebase->database();
