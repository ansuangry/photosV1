<?php 

require '../src/facebook.php';
require '../parse/parse.php';

$parseQuery = new parseQuery();
var_dump($parseQuery);

$parseObject = new parseObject('fb_photos');
var_dump($parseObject);
?>