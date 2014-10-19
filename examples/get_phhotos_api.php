<?php 

require '../src/facebook.php';
require '../parse/parse.php';

// $parseQuery = new parseQuery('fb_photos');
// // var_dump($parseQuery);

// // $result = $parseQuer->find();

// echo "RESULT: ";
// print_r($parseQuery);


$params = array(
    'className' => 'fb_photos',
    'objectId' => 'Ed1nuqPvcm'
);

$request = $parse->get($params);


?>