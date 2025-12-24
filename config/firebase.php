<?php
require __DIR__ . '/../vendor/autoload.php';

use Kreait\Firebase\Factory;

$firebaseJson = getenv('FIREBASE_SERVICE_ACCOUNT');

if (!$firebaseJson) {
    throw new RuntimeException('FIREBASE_SERVICE_ACCOUNT env variable not set');
}

$serviceAccount = json_decode($firebaseJson, true);

if (!$serviceAccount) {
    throw new RuntimeException('Invalid Firebase service account JSON');
}

$factory = (new Factory)
    ->withServiceAccount($serviceAccount);

$firebase = $factory->create();

$database = $firebase->getDatabase();
$auth = $firebase->getAuth();
