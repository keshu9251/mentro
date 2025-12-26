<?php
// ===================== CORS =====================
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
// ===============================================

require __DIR__ . "/../../../config/firebase.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data["uid"])) {
    echo json_encode([
        "success" => false,
        "message" => "UID is required"
    ]);
    exit;
}

$uid = $data["uid"];

// 🔹 Fetch existing mentor
$mentorRef = $db->getReference("mentors/" . $uid);
$existingMentor = $mentorRef->getValue();

if (!$existingMentor) {
    echo json_encode([
        "success" => false,
        "message" => "Mentor not found"
    ]);
    exit;
}

// 🔹 Build updated mentor object (merge-safe)
$updatedMentor = [
    "uid" => $uid,
    "name" => $data["name"] ?? $existingMentor["name"] ?? "",
    "email" => $existingMentor["email"] ?? "",
    "company" => $data["company"] ?? $existingMentor["company"] ?? "",
    "industry" => $data["industry"] ?? $existingMentor["industry"] ?? "",
    "domain" => $data["domain"] ?? $existingMentor["domain"] ?? "",
    "verified" => $existingMentor["verified"] ?? false,
    "created_at" => $existingMentor["created_at"] ?? time(),

    // 🔥 Instant Availability (USP)
    "instant_available" => $data["instant_available"] ?? $existingMentor["instant_available"] ?? false,

    // 🔹 Availability
    "availability" => [
        "days" => $data["availability"]["days"] ?? $existingMentor["availability"]["days"] ?? [],
        "time_from" => $data["availability"]["time_from"] ?? $existingMentor["availability"]["time_from"] ?? "",
        "time_to" => $data["availability"]["time_to"] ?? $existingMentor["availability"]["time_to"] ?? ""
    ],

    // 🔹 Pricing
    "pricing" => [
        "min15" => $data["pricing"]["min15"] ?? $existingMentor["pricing"]["min15"] ?? 0,
        "min30" => $data["pricing"]["min30"] ?? $existingMentor["pricing"]["min30"] ?? 0,
        "min60" => $data["pricing"]["min60"] ?? $existingMentor["pricing"]["min60"] ?? 0
    ]
];

// 🔹 Save merged data
$mentorRef->set($updatedMentor);

echo json_encode([
    "success" => true,
    "message" => "Mentor profile updated successfully"
]);
