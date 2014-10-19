<?php
require '../src/facebook.php';
require '../parse/parse.php';

ini_set('memory_limit', '-1');

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '740582579287017',
  'secret' => 'd524957637569247b690fdebcdea53d4',
  'cookie' => true
));

// Get User ID
$new_access_token = "CAAKhjlGQsZBkBAGBpdvQIZCU83FuqEXzZCCcmLpn5Oo6zVD2s3GFboYIKNOQzy4GJq7fSw6bklNh9HOGNPACx8UWqjfCLuB2f832ELM4NM8eyQ2CvnPv9Mihb1036rHICupNVY5LSdtPQszOezfmxhpVUMRskne1AyiZAK6J2NOh0ZCZCTglZAsvn0zFWEtqQZBnL2aY2KCWT03Ku9AgwhZC7";
$facebook->setAccessToken($new_access_token);
$user = $facebook->getUser();
$access_token = $facebook->getAccessToken();
 // echo 'This is token: '.$access_token ;

//CAAKhjlGQsZBkBAJnXh6sG6mZAne69fLxm4mVQsFDwMBzesYksGyRvut8gQOJeX8ri3FXnDqZCLhINA1TXPmvqB6Q6zqdOnqBU4lDGOzogHPAjA1JslVuLtTZB8usX2b2NJdeqeOojStGwtcMIabGTVja1bGCCTwtUE431liuDNkn0l9XjrmH

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

// if ($user) {
//   try {
//     // Proceed knowing you have a logged in user who's authenticated.
//     $user_profile = $facebook->api('/me/likes?limit=5000');
//   } catch (FacebookApiException $e) {
//     error_log($e);
//     $user = null;
//   }
// }

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $statusUrl = $facebook->getLoginStatusUrl();
  $loginUrl = $facebook->getLoginUrl();
}

$user_like = array();

// $user_like = array_merge($user_like, chunk_results(array(),'/me/likes?limit=5000'));
$user_like = FB_Chunk('/me/likes');
$images = getAlbums($user_like);
// echo json_encode($images);


for ($i=0; $i < count($images) ; $i++) { 

		echo "========================= Total images : " . count($images) . " ====================";

        $parseObject = new parseObject('photos_cmd');
        $parseObject->photo_id = $images[$i]['id'] ;
        $parseObject->created_time = $images[$i]['created_time'];
        $parseObject->from = $images[$i]['from'];
        $parseObject->height = $images[$i]['height'];
        $parseObject->width = $images[$i]['width'];
        $parseObject->icon = $images[$i]['icon'];
        $parseObject->images = $images[$i]['images'];
        $parseObject->picture = $images[$i]['picture'];
        $parseObject->source = $images[$i]['source'];
        $parseObject->updated_time = $images[$i]['updated_time'];
        $parseObject->comments = $images[$i]['comments'];
        $parseObject->likes = $images[$i]['likes'];
        
        // $parseSpeaker->sitesCollection = array('__op' => 'AddUnique', 'objects' => $s['sites']);
        $result = $parseObject->save();
        // echo "<img src=".$images[$i]['picture']." alt="nm" height=".$images[$i]['height']." width=".$images[$i]['width']."> ";
         print_r($result);
        echo "<br></br>";
 
}

// 	$arrayName = array('user_profile' =>$user_profile );
//    // echo json_encode($arrayName);
// for ($i=0; $i < 1 ; $i++) { 
// 	// for ($i=0; $i < count($user_profile['data']) ; $i++) { 
//         // $parseObject = new parseObject('user_like');
//         // $parseObject->category = $user_profile['data'][$i]['category'];
//         // $parseObject->name = $user_profile['data'][$i]['name'];
//         // $parseObject->created_time = $user_profile['data'][$i]['created_time'];
//         // $parseObject->fb_id = $user_profile['data'][$i]['id'];
//         //array('__op' => 'AddUnique', 'objects' => $s['sites']);
//   // $parseSpeaker->sitesCollection = array('__op' => 'AddUnique', 'objects' => $s['sites']);
//         // $result = $parseObject->save();
//         // echo "RESULT: ";
//         // print_r($result);
//         // echo "<br></br>";
//         // usleep(300000);
//         $id = $user_profile['data'][$i]['id'];
//         // echo $id;
//          $albums = '';
//         try {
//           // Proceed knowing you have a logged in user who's authenticated.
//           $albums = $facebook->api($id.'/albums?limit=5000');
//         } catch (FacebookApiException $e) {
//         error_log($e);
//         $user = null;
//       }
    
//     // 627026714010112

// try {
//           // Proceed knowing you have a logged in user who's authenticated.
//           $photos = $facebook->api('627026714010112/photos?limit=5000');
//         } catch (FacebookApiException $e) {
//         error_log($e);
//         $user = null;
//       }
// 	}

// echo json_encode(chunk_results(array(),"/100005459800019/posts?fields=message&limit=5000"));

function getAlbums($user_like){
  $images = array();
  $albums = array();
 for ($i=0; $i <count($user_like) ; $i++) {
  // for ($i=8; $i <9 ; $i++) { 
    $id = $user_like[$i]['id'];
    $albums_single = array();
    $albums_single = FB_Chunk($id.'/albums');
    $albums = array_merge($albums, $albums_single); 
  }
   for ($i=0; $i < count($albums); $i++) { 
    echo "\r\n albums : ". $i ;
  // for ($i=2; $i < count($albums); $i++) { 
   $id = $albums[$i]['id'];
     $images = array_merge($images, FB_Chunk($id.'/photos'));  
  }
return $images ;
}

function FB_Chunk($query) {
    $photos_data = array();
    $offset = 0;
    $limit = 500;
// echo $query."?limit=$limit&offset=$offset" ;
		try {           
				$data = $GLOBALS["facebook"]->api($query."?limit=$limit&offset=$offset",'GET');
    			$photos_data = array_merge($photos_data, $data["data"]);

    			while(in_array("paging", $data) && array_key_exists("next", $data["paging"])) {
       				$offset += $limit;
        			$data = $GLOBALS["facebook"]->api("/$user_id/photos?limit=$limit&offset=$offset",'GET');
			        $photos_data = array_merge($photos_data, $data["data"]);
    			}
		    	echo "\r\n query : ".$query." count : ". count($photos_data);
             } 
		catch (FacebookApiException $e) { 
				echo $e ;
     	 }
    return $photos_data;
}


function FB_GetUserTaggedPhotos($user_id, $fields="source,id") {
    $photos_data = array();
    $offset = 0;
    $limit = 500;

    $data = $GLOBALS["facebook"]->api("/$user_id/photos?limit=$limit&offset=$offset&fields=$fields",'GET');
    $photos_data = array_merge($photos_data, $data["data"]);

    while(in_array("paging", $data) && array_key_exists("next", $data["paging"])) {
        $offset += $limit;
        $data = $GLOBALS["facebook"]->api("/$user_id/photos?limit=$limit&offset=$offset&fields=$fields",'GET');
        $photos_data = array_merge($photos_data, $data["data"]);
    }

    return $photos_data;
}




function chunk_results($arr,$query)
{  
    $count = 0 ;
    global $facebook;
    $chunk = $facebook->api($query);
    if($chunk['data']==null)
    {
        return $arr;
    }   
    else
    {
        foreach($chunk['data'] as $dato)
            $arr['data'][] = $dato;
          
        return chunk_results($arr,substr($chunk['paging']['next'],26));
    }
}


/*
try {
    $facebook = new Facebook(array(
      'appId'  => '<removed>',
      'secret' => '<removed>',
    ));
    $access_token = $facebook->getAccessToken();

    $events_data = array();
    $offset = 0;
    $limit = 5000;  
    $params = array('access_token' => $access_token);

    //fetch events from Facebook API
    $data = $facebook->api("$fid/events/?limit=$limit&offset=$offset", $params);
    $events_data = array_merge($events_data, $data["data"]);

    //loop through pages to return all results
    while(in_array("paging", $data) && array_key_exists("next", $data["paging"])) {
        $offset += $limit;
        $data = $facebook->api("$fid/events/?limit=$limit&offset=$offset", $params);
        $events_data = array_merge($events_data, $data["data"]);
    }}




//loop through pages to return all results
while(in_array("paging", $data) && array_key_exists("next", $data["paging"])) {
    $offset += $limit;
    $data = $facebook->api("$fid/events/?limit=$limit&offset=$offset", $params);
    // make sure we do not merge with an empty array
    if (count($data["data"]) > 0){
        $events_data = array_merge($events_data, $data["data"]);
    } else {
        // if the data entry is empty, we have reached the end, exit the while loop
        break;
    }
}}





*/


?>
<!--
<h1>php-sdk</h1>

    <?php if ($user): ?>
      <a href="<?php echo $logoutUrl; ?>">Logout</a>
    <?php else: ?>
      <div>
        Check the login status using OAuth 2.0 handled by the PHP SDK:
        <a href="<?php echo $statusUrl; ?>">Check the login status</a>
      </div>
      <div>
        Login using OAuth 2.0 handled by the PHP SDK:
        <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
      </div>
    <?php endif ?>   -->