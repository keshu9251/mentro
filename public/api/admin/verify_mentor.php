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

$db->collection('mentors')
   ->document($mentorId)
   ->update([
       ['path' => 'verification_status', 'value' => 'verified'],
       ['path' => 'verified_badge', 'value' => true]
   ]);

echo json_encode([
    'success' => true,
    'message' => 'Mentor verified successfully'
]);
