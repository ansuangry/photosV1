<?php
require 'src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '740582579287017',
  'secret' => '4596befe119e7f98bdd18b6681b92568',
));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $statusUrl = $facebook->getLoginStatusUrl();
  $loginUrl = $facebook->getLoginUrl(array(
    'scope' => 'user_status,publish_stream,user_photos,user_photo_video_tags'
));
}

// This call will always work since we are fetching public data.
$naitik = $facebook->api('/naitik');

	mysql_connect('127.0.0.1', 'root', '');
	mysql_select_db('facebook');
	
	# We have an active session; let's check if we've already registered the user
$query = mysql_query("SELECT * FROM users WHERE oauth_provider = 'facebook' AND user_id = ". $user_profile['id']);
$result = mysql_fetch_array($query);
 
# If not, let's add it to the database
if(empty($result)){
    $query = mysql_query("INSERT INTO users (oauth_provider, user_id, username, code) VALUES ('facebook', {$user_profile['id']}, '{$user_profile['name']}', '{$user_profile['link']}')");
    $query = mySql_query("SELECT * FROM users WHERE id = " . mysql_insert_id());
    $result = mysql_fetch_array($query);
	}
	
	
	
if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $photos = $facebook->api('me?fields=albums.fields(photos.fields(picture))');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}	
	
	
	
	
?>


<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>Login</title>
    <style>
      body {
        font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
      }
      h1 a {
        text-decoration: none;
        color: #3b5998;
      }
      h1 a:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body>
    <h1>Login</h1>
	<div>
        Login:
        <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
      </div>
    <h3>PHP Session</h3>
    <pre><?php print_r($_SESSION); ?></pre>
	<!-- <pre><?php //print_r($user_profile); ?></pre> -->
    
	<pre><?php print_r($photos); ?></pre>
  </body>
</html>









