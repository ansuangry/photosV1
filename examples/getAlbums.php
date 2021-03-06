<?php
    
    require '../src/facebook.php';
    
    ini_set('memory_limit', '-1');
    
    // Create our Application instance (replace this with your appId and secret).
    $facebook= new Facebook(array(
    'appId'=> '740582579287017',
    'secret'=> 'd524957637569247b690fdebcdea53d4',
    'cookie'=> true
    ));
    
    // Get User ID
    $new_access_token= "CAAKhjlGQsZBkBAGBpdvQIZCU83FuqEXzZCCcmLpn5Oo6zVD2s3GFboYIKNOQzy4GJq7fSw6bklNh9HOGNPACx8UWqjfCLuB2f832ELM4NM8eyQ2CvnPv9Mihb1036rHICupNVY5LSdtPQszOezfmxhpVUMRskne1AyiZAK6J2NOh0ZCZCTglZAsvn0zFWEtqQZBnL2aY2KCWT03Ku9AgwhZC7";
    $facebook->setAccessToken($new_access_token);
    $user= $facebook->getUser();
    $access_token= $facebook->getAccessToken();
    
    // Login or logout url will be needed depending on current user state.
    if ($user) {
    $logoutUrl= $facebook->getLogoutUrl();
    } else {
    $statusUrl= $facebook->getLoginStatusUrl();
    $loginUrl= $facebook->getLoginUrl();
    }
    
    // for ($i= 0; $i <= count($user_like); $i++){
    //     if(!checkID($user_like[$i]['id'])){
    //         Insert($user_like[$i]);
    //     }
    // }
    
    get_start();
    
    function get_start(){
        $user_like = getLikeFromDb();
        
        for ($i= 0; $i <= count($user_like); $i++){
            echo "==== Start ====== ". $user_like[$i]['facebook_id'] ."\n";
            $AlbumsOfLike = getAlbums($user_like[$i]['facebook_id']) ;
            for($j=0; $j <= count($AlbumsOfLike); $j++){
                if(!checkID($AlbumsOfLike[$j]['id'])){
                    Insert($AlbumsOfLike[$j]);
                }
            }
            UpadeLikeDB($user_like[$i]['id']);
            echo "==== end ====== ". $user_like[$i]['facebook_id'] . "\n";
            $check = getLikeFromDb();;
            if (count($check)>0){
                get_start();
            }
        }
    }
    
    function getLikeFromDb(){
        $servername= getenv('IP');
        $username= getenv('C9_USER');
        $password= '';
        $dbname= "c9";
    
        // Create connection
        $conn= new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $result= mysqli_query($conn, "SELECT * FROM LIKES WHERE isUploaded = 0 LIMIT 1");
        $result_array = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $result_array[] = $row;
        }
        mysqli_close($conn);
        return $result_array ;
    }
    
    function UpadeLikeDB($id){
        $servername= getenv('IP');
        $username= getenv('C9_USER');
        $password= '';
        $dbname= "c9";
    
        // Create connection
        $conn= new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        $result= mysqli_query($conn, "UPDATE LIKES SET isUploaded=1 WHERE id='$id'");
        mysqli_close($conn);
    }
    
    function getAlbums($id){
        $albums= array();
        $albums_single= array();
        $albums_single= FB_Chunk($id.'/albums');
        $albums= array_merge($albums, $albums_single); 
        return $albums ;
    }
    
    function Insert($array){
        $servername= getenv('IP');
        $username= getenv('C9_USER');
        $password= '';
        $dbname= "c9";
        
            // Create connection
        $conn= new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
            }
    
        $facebook_id= mysqli_real_escape_string($conn, $array['id']);
        $type= mysqli_real_escape_string($conn, $array['type']);
        $name= mysqli_real_escape_string($conn, $array['name']);
        $count= mysqli_real_escape_string($conn, $array['count']);
        //INSERT INTO `albums` (`id`, `facebook_id`, `type`, `name`, `count`) VALUES (1, 180942772043153, 'wall', 'Timeline Photos', 2414);
        $sql='INSERT INTO ALBUMS (facebook_id, type, name, count, isUploaded) VALUES ( '.$facebook_id.', \''.$type.'\', \''.$name.'\',\''.$count.'\', 0);' ;
        $result= mysqli_query($conn, $sql);
        
        if ($result) {
            
            echo 'Successful inserts: ' . mysqli_affected_rows($conn) . "\n";
        } else {
            echo 'query failed: ' . mysqli_error($conn). "\n";
        }
        mysqli_close($conn);
    }
    
    
    function checkID($id){
        $servername= getenv('IP');
        $username= getenv('C9_USER');
        $password= '';
        $dbname= "c9";
        
            // Create connection
        $conn= new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        $result= mysqli_query($conn, "SELECT 1 FROM ALBUMS WHERE facebook_id='$id' LIMIT 1");
    
        if (mysqli_fetch_row($result) > 0) {
            mysqli_close($conn);
            return true;
        } else {
            mysqli_close($conn);
            return false;
        }
    }
    
    function FB_Chunk($query) {
        $photos_data= array();
        $offset= 0;
        $limit= 500;
    // echo $query."?limit=$limit&offset=$offset" ;
    	try {           
            $data= $GLOBALS["facebook"]->api($query."?limit=$limit&offset=$offset",'GET');
            $photos_data= array_merge($photos_data, $data["data"]);
            while(isset($data['paging']['next']) && !empty($data['paging']['next'])) {
            // while(in_array("paging", $data) && array_key_exists("next", $data["paging"])) {
                echo "in while \n" ;
                $offset += $limit;
                $data= $GLOBALS["facebook"]->api($query."?limit=$limit&offset=$offset",'GET');
                $photos_data= array_merge($photos_data, $data["data"]);
        		}
     	    echo "query: ".$query." count : ". count($photos_data) ."\n";
        } 
    	catch (FacebookApiException $e) { 
    		echo $e ;
         }
        return $photos_data;
    }

?>
