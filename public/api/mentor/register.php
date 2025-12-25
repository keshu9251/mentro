<?php
// ================= CORS (MUST BE ON TOP) =================
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
// ========================================================

// Firebase config
require __DIR__ . "/../../config/firebase.php";

// Read JSON body
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid JSON payload"
    ]);
    exit;
}

// ================= REQUIRED FIELD VALIDATION =================
$required = ["uid", "name", "email", "industry", "domain", "role"];

foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode([
            "success" => false,
            "message" => "Missing required field: $field"
        ]);
        exit;
    }
}

// ================= CREATE MENTOR OBJECT =================
$mentorId = uniqid("mentor_");

$mentorData = [
    "mentor_id" => $mentorId,

    // 🔑 Firebase Auth Link
    "uid" => $data["uid"],

    // Basic info
    "name" => trim($data["name"]),
    "email" => trim($data["email"]),

    // Professional info
    "industry" => $data["industry"],
    "domain" => $data["domain"],
    "role" => $data["role"],
    "techStack" => $data["techStack"] ?? "",

    "experience" => $data["experience"] ?? "",
    "company" => $data["company"] ?? "",

    // Availability
    "availability" => [
        "days" => $data["days"] ?? [],
        "time_from" => $data["timeFrom"] ?? "",
        "time_to" => $data["timeTo"] ?? ""
    ],

    // Pricing
    "pricing" => [
        "min15" => isset($data["price15"]) ? (int)$data["price15"] : 0,
        "min30" => isset($data["price30"]) ? (int)$data["price30"] : 0,
        "min60" => isset($data["price60"]) ? (int)$data["price60"] : 0
    ],

    // Profile
    "linkedin" => $data["linkedin"] ?? "",
    "bio" => $data["bio"] ?? "",

    // Platform control
    "verified" => false,
    "status" => "pending",

    // Meta
    "created_at" => date("Y-m-d H:i:s")
];

// ================= PREVENT DUPLICATE REGISTRATION =================
$snapshot = $db->getReference("mentors")->getSnapshot();
$mentors = $snapshot->getValue() ?? [];

foreach ($mentors as $m) {
    if (isset($m["uid"]) && $m["uid"] === $data["uid"]) {
        echo json_encode([
            "success" => false,
            "message" => "Mentor profile already exists"
        ]);
        exit;
    }
}

// ================= SAVE TO DATABASE =================
$db->getReference("mentors/$mentorId")->set($mentorData);

// ================= RESPONSE =================
echo json_encode([
    "success" => true,
    "mentor_id" => $mentorId,
    "message" => "Mentor registration successful. Verification pending."
]);
