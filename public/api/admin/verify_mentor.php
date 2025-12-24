<?php
require '../../../config/firebase.php';

$mentorId = $_POST['mentor_id'] ?? null;

if (!$mentorId) {
    echo json_encode([
        'success' => false,
        'message' => 'Mentor ID required'
    ]);
    exit;
}

$database
    ->getReference("mentors/$mentorId")
    ->update([
        'verification_status' => 'verified',
        'verified_badge' => true
    ]);

echo json_encode([
    'success' => true,
    'message' => 'Mentor verified successfully'
]);
