<?php
// ================== CORS ==================
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// ✅ Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
// ==========================================

require __DIR__ . "/../../../config/firebase.php";

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data["uid"]) || empty($data["role"])) {
    echo json_encode([
        "success" => false,
        "message" => "UID and role are required"
    ]);
    exit;
}

// Save role under users/{uid}
$db->getReference("users/" . $data["uid"])->set([
    "role" => $data["role"]
]);

echo json_encode([
    "success" => true,
    "message" => "Role saved successfully"
]);
