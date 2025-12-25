<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require __DIR__ . "/../../../config/firebase.php";

$uid = $_GET["uid"] ?? null;

if (!$uid) {
    echo json_encode(["success" => false]);
    exit;
}

$mentors = $db->getReference("mentors")->getValue() ?? [];

foreach ($mentors as $mentor) {
    if (isset($mentor["uid"]) && $mentor["uid"] === $uid) {
        echo json_encode(["success" => true, "data" => $mentor]);
        exit;
    }
}

echo json_encode(["success" => false]);
