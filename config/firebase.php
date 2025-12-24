
<?php
require __DIR__ . '/../vendor/autoload.php';
use Kreait\Firebase\Factory;
$factory = (new Factory)->withServiceAccount(getenv('GOOGLE_APPLICATION_CREDENTIALS'));
$firestore = $factory->createFirestore();
$db = $firestore->database();
