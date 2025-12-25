<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require __DIR__ . "/../../config/firebase.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid JSON"]);
    exit;
}

if (
    empty($data["name"]) ||
    empty($data["email"]) ||
    empty($data["industry"]) ||
    empty($data["domain"]) ||
    empty($data["role"])
) {
    echo json_encode(["success" => false, "message" => "Required fields missing"]);
    exit;
}

$mentorId = uniqid("mentor_");

$db->getReference("mentors/$mentorId")->set([
    "mentor_id" => $mentorId,
    "name" => $data["name"],
    "email" => $data["email"],
    "industry" => $data["industry"],
    "domain" => $data["domain"],
    "role" => $data["role"],
    "techStack" => $data["techStack"] ?? "",
    "experience" => $data["experience"] ?? "",
    "company" => $data["company"] ?? "",
    "availability" => [
        "days" => $data["days"] ?? [],
        "time_from" => $data["timeFrom"] ?? "",
        "time_to" => $data["timeTo"] ?? ""
    ],
    "pricing" => [
        "min15" => $data["price15"] ?? 0,
        "min30" => $data["price30"] ?? 0,
        "min60" => $data["price60"] ?? 0
    ],
    "linkedin" => $data["linkedin"] ?? "",
    "bio" => $data["bio"] ?? "",
    "verified" => false,
    "created_at" => date("Y-m-d H:i:s")
]);

echo json_encode([
    "success" => true,
    "mentor_id" => $mentorId,
    "message" => "Mentor application submitted"
]);
