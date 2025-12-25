<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

require __DIR__ . "/../../../config/firebase.php";

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data["uid"]) || empty($data["role"])) {
    echo json_encode(["success" => false, "message" => "UID and role required"]);
    exit;
}

$db->getReference("users/" . $data["uid"])->set([
    "role" => $data["role"]
]);

echo json_encode(["success" => true]);
