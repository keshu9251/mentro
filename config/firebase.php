<?php
require __DIR__ . '/../vendor/autoload.php';

use Kreait\Firebase\Factory;

$firebaseJson = getenv('FIREBASE_SERVICE_ACCOUNT');

if (!$firebaseJson) {
    throw new RuntimeException('FIREBASE_SERVICE_ACCOUNT not set');
}

$serviceAccount = json_decode($firebaseJson, true);

$factory = (new Factory)->withServiceAccount($serviceAccount);

// Firestore
$firestore = $factory->createFirestore();
$db = $firestore->database();

// Auth (for later)
$auth = $factory->createAuth();
