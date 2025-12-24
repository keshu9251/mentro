
<?php
require '../../config/firebase.php';
$data=json_decode(file_get_contents("php://input"),true);
$db->collection('sessions')->add([
 'mentorId'=>$data['mentorId'],
 'studentId'=>$data['studentId'],
 'time'=>$data['time'],
 'status'=>'booked'
]);
echo json_encode(['status'=>'session booked']);
