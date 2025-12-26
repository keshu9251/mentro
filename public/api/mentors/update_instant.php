<?php
require __DIR__ . "/../../config/firebase.php";

$data = json_decode(file_get_contents("php://input"), true);
$uid = $data["uid"] ?? null;
$status = $data["instant_available"] ?? false;

if (!$uid) {
  echo json_encode(["success" => false]);
  exit;
}

$db->getReference("mentors/$uid/instant_available")->set($status);

echo json_encode(["success" => true]);
