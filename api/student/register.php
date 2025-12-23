
<?php
require '../../config/firebase.php';
$data = json_decode(file_get_contents("php://input"), true);
$db->collection('users')->add([
 'name'=>$data['name'],
 'email'=>$data['email'],
 'role'=>'student',
 'createdAt'=>new DateTime()
]);
echo json_encode(['status'=>'student registered']);
