
<?php
require '../../config/firebase.php';
$data=json_decode(file_get_contents("php://input"),true);
$db->collection('availability')->add($data);
echo json_encode(['status'=>'availability saved']);
