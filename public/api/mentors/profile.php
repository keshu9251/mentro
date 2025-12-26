<?php
// ================= CORS =================
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
// =======================================

require __DIR__ . "/../../../config/firebase.php";

$uid = $_GET['uid'] ?? null;

if (!$uid) {
    echo json_encode(["success" => false, "message" => "UID required"]);
    exit;
}

$mentors = $db->getReference("mentors")->getValue();

if (!$mentors) {
    echo json_encode(["success" => false]);
    exit;
}

foreach ($mentors as $mentor) {
    if (isset($mentor["uid"]) && $mentor["uid"] === $uid) {
        echo json_encode([
            "success" => true,
            "data" => $mentor
        ]);
        exit;
    }
}

echo json_encode(["success" => false]);
