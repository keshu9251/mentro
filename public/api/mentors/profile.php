<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require __DIR__ . "/../../config/firebase.php";

$uid = $_GET["uid"] ?? null;

if (!$uid) {
    echo json_encode([
        "success" => false,
        "message" => "Mentor UID missing"
    ]);
    exit;
}

try {
    // Fetch mentor profile
    $snapshot = $db->getReference("mentors/$uid")->getSnapshot();

    if (!$snapshot->exists()) {
        echo json_encode([
            "success" => false,
            "message" => "Mentor profile not found"
        ]);
        exit;
    }

    $mentor = $snapshot->getValue();

    // ✅ SAFE RESPONSE (EVERY FIELD GUARANTEED)
    $response = [
        "uid" => $uid,
        "name" => $mentor["name"] ?? "",
        "email" => $mentor["email"] ?? "",
        "company" => $mentor["company"] ?? "",

        // 🔥 IMAGE FIX (MOST IMPORTANT)
        "photo" => isset($mentor["photo"]) && is_string($mentor["photo"])
            ? $mentor["photo"]
            : "",

        "industry" => $mentor["industry"] ?? "",
        "track" => $mentor["track"] ?? "",
        "role" => $mentor["role"] ?? "",
        "skill" => $mentor["skill"] ?? "",

        // Availability
        "availability" => [
            "days" => $mentor["availability"]["days"] ?? [],
            "time_from" => $mentor["availability"]["time_from"] ?? "",
            "time_to" => $mentor["availability"]["time_to"] ?? ""
        ],

        // Pricing
        "pricing" => [
            "min15" => $mentor["pricing"]["min15"] ?? 0,
            "min30" => $mentor["pricing"]["min30"] ?? 0,
            "min60" => $mentor["pricing"]["min60"] ?? 0
        ],

        // Instant availability
        "instant_available" => $mentor["instant_available"] ?? false,

        // Sessions (future-safe)
        "upcoming_sessions" => $mentor["upcoming_sessions"] ?? [],
        "completed_sessions" => $mentor["completed_sessions"] ?? []
    ];

    echo json_encode([
        "success" => true,
        "data" => $response
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Server error",
        "error" => $e->getMessage()
    ]);
}
