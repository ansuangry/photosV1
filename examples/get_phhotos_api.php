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

// <h1>php-sdk</h1>

//     <?php if ($user): ?>
//       <a href="<?php echo $logoutUrl; ?>">Logout</a>
//     <?php else: ?>
//       <div>
//         Check the login status using OAuth 2.0 handled by the PHP SDK:
//         <a href="<?php echo $statusUrl; ?>">Check the login status</a>
//       </div>
//       <div>
//         Login using OAuth 2.0 handled by the PHP SDK:
//         <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
//       </div>
//     <?php endif ?>  