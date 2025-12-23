
<?php
require '../../config/firebase.php';
$docs=$db->collection('users')->where('role','=','mentor')->documents();
foreach($docs as $doc){
 echo $doc->id().' : '.$doc['name'].'<br>';
}
