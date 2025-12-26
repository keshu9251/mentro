<?php
// ================== CORS ==================
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
// ==========================================

require __DIR__ . "/../../../config/firebase.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data["uid"]) || empty($data["name"])) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid mentor data"
    ]);
    exit;
}

// Create mentor object
$mentorData = [
    "uid" => $data["uid"],
    "name" => $data["name"],
    "email" => $data["email"] ?? "",
    "industry" => $data["industry"] ?? "",
    "domain" => $data["domain"] ?? "",
    "role" => $data["role"] ?? "",
    "company" => $data["company"] ?? "",

    "availability" => [
        "days" => $data["availability"]["days"] ?? [],
        "time_from" => $data["availability"]["time_from"] ?? "",
        "time_to" => $data["availability"]["time_to"] ?? ""
    ],

    "pricing" => [
        "min15" => $data["pricing"]["min15"] ?? 0,
        "min30" => $data["pricing"]["min30"] ?? 0,
        "min60" => $data["pricing"]["min60"] ?? 0
    ],

    "verified" => false,
    "created_at" => time()
];

// Save mentor using UID as key
$db->getReference("mentors/" . $data["uid"])->set($mentorData);

echo json_encode([
    "success" => true,
    "message" => "Mentor registration submitted"
]);
