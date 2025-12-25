<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require __DIR__ . "/../../config/firebase.php";

$snapshot = $db->getReference("mentors")->getSnapshot();
$mentors = $snapshot->getValue() ?? [];

$result = [];

foreach ($mentors as $mentor) {
    if (isset($mentor["verified"]) && $mentor["verified"] === true) {
        $result[] = $mentor;
    }
}

echo json_encode([
    "success" => true,
    "data" => array_values($result)
]);
