<?php
// ================= CORS =================
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
// ========================================

require __DIR__ . "/../../../config/firebase.php";


$snapshot = $db->getReference("mentors")->getSnapshot();
$mentors = $snapshot->getValue() ?? [];

$result = [];

foreach ($mentors as $mentor) {
    if (isset($mentor["verified"]) && $mentor["verified"] === true) {
        $result[] = $mentor;
    }
}

echo json_encode([
    "success" => true,
    "data" => array_values($result)
]);
