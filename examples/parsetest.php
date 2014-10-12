<?php

require '../parse/parse.php';

//This example is a sample video upload stored in parse

//     function storeInParseDB ($message, $unit) {
//         $parse = new parseQuery('testing');
//         $parse->name = $unit ;
//         $parse->userid = array("__type" => "Pointer", "className" => "_User", "objectId" => '0');
//         $result = $parse->find();

//         echo "RESULT: ";
//         print_r($result);
// }
// try {
// storeInParseDB('hi', 'test');
// } catch (ParseLibraryException $e) {
//     echo 'Caught parse exception: ',  $e->getMessage(), "\n";
// }
$parseObject = new parseObject('testing');
		// $parseObject->score = 1111;
		$parseObject->name = 'zkDfk;dzkfl';
		// $parseObject->mode = 'cheat';
		$result = $parseObject->save();
echo "RESULT: ";
        print_r($result);

?>