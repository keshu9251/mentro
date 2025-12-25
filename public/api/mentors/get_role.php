<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require __DIR__ . "/../../../config/firebase.php";

$uid = $_GET["uid"] ?? null;

if (!$uid) {
    echo json_encode(["success" => false]);
    exit;
}

$user = $db->getReference("users/$uid")->getValue();

if (!$user || !isset($user["role"])) {
    echo json_encode(["success" => false]);
    exit;
}

echo json_encode([
    "success" => true,
    "role" => $user["role"]
]);
