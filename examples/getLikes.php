    <?php
    
    require '../src/facebook.php';
    
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
    
    // Login or logout url will be needed depending on current user state.
    if ($user) {
      $logoutUrl = $facebook->getLogoutUrl();
    } else {
      $statusUrl = $facebook->getLoginStatusUrl();
      $loginUrl = $facebook->getLoginUrl();
    }
    
    $user_like = array();
    
    $user_like = FB_Chunk('/me/likes');
    // var_dump($user_like);
    for ($i = 0; $i <= count($user_like); $i++){
        if(!checkID($user_like[$i]['id'])){
            Insert($user_like[$i]);
        }
    }
    function Insert($array){
        $servername = getenv('IP');
        $username = getenv('C9_USER');
        $password = '';
        $dbname = "c9";
    
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // $id = mysql_real_escape_string($id);  // SECURITY!
        //mysqli_query($conn, $sql);
        //INSERT INTO `likes` (`facebook_id`, `category`, `name`, `isUploaded`) VALUES (19982, 'magazine', 'picbody', 0);
        $facebook_id = mysqli_real_escape_string($conn, $array['id']);
        $category = mysqli_real_escape_string($conn, $array['category']);
        $name = mysqli_real_escape_string($conn, $array['name']);
        $sql='INSERT INTO LIKES (facebook_id, category, name, isUploaded) VALUES ( '.$facebook_id.', \''.$category.'\', \''.$name.'\', 0);' ;
        $result = mysqli_query($conn, $sql);
        // if($result){
        //     echo "".$facebook_id ." : successfully \n";
        // }else{
        //     echo "sdfsdfsd";
        // }
        
        if ($result) {
            echo 'Successful inserts: ' . mysqli_affected_rows($conn).'\n';
        } else {
            echo 'query failed: ' . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
    
    
    function checkID($id){
        // echo $id ;
        
        $servername = getenv('IP');
        $username = getenv('C9_USER');
        $password = '';
        $dbname = "c9";
    
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // $id = mysql_real_escape_string($id);  // SECURITY!
        //mysqli_query($conn, $sql);
        $result = mysqli_query($conn, "SELECT 1 FROM LIKES WHERE facebook_id='$id' LIMIT 1");
    
        if (mysqli_fetch_row($result) > 0) {
            mysqli_close($conn);
            return true;
        } else {
            mysqli_close($conn);
            return false;
        }
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
    		  //  	echo "\r\n query : ".$query." count : ". count($photos_data);
                 } 
    		catch (FacebookApiException $e) { 
    				echo $e ;
         	 }
        return $photos_data;
    }
    
    
    
    
    
    
    
    
    // $servername = getenv('IP');
    // $username = getenv('C9_USER');
    // $password = '';
    // $dbname = "c9";
    
    // // Create connection
    // $conn = new mysqli($servername, $username, $password, $dbname);
    // // Check connection
    // if ($conn->connect_error) {
    //     die("Connection failed: " . $conn->connect_error);
    // } 
    
    
    
    
    
    
    
    
    
    
    
    
    
    // $retval = mysqli_query($conn, $sql);
    // if(! $retval )
    // {
    //   die('Could not create table: ' . mysql_error());
    // }
    // echo "Table photos created successfully\n";
    
    // mysqli_close($conn);
    
    
    ?>