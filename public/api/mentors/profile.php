<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require __DIR__ . "/../../config/firebase.php";

$uid = $_GET["uid"] ?? null;

if (!$uid) {
    echo json_encode(["success" => false, "message" => "UID required"]);
    exit;
}

$snapshot = $db->getReference("mentors")->getSnapshot();
$mentors = $snapshot->getValue() ?? [];

foreach ($mentors as $mentor) {
    if (isset($mentor["uid"]) && $mentor["uid"] === $uid) {
        echo json_encode([
            "success" => true,
            "data" => $mentor
        ]);
        exit;
    }
}

echo json_encode([
    "success" => false,
    "message" => "Mentor profile not found"
]);
