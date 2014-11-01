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
        $albums = getAlbumsFromDb();
        for ($i= 0; $i <= count($albums); $i++){
            echo "==== Sta ====== ". $albums[$i]['facebook_id'] ."\n";
            $PhotosOfAlbums = getPhotos($albums[$i]['facebook_id']) ; 
            // $PhotosOfAlbums = getPhotos("237847593033826") ; 
            // var_dump($PhotosOfAlbums);
            for($j=0; $j <= count($PhotosOfAlbums); $j++){
                if(!checkID($PhotosOfAlbums[$j]['id'])){
                    Insert($PhotosOfAlbums[$j]);
                }
            }
            UpadeAlbumsDB($albums[$i]['id']);
            echo "==== end ====== ". $albums[$i]['facebook_id'] . "\n";
            $check = getAlbumsFromDb();
            if (count($check)>0){
                get_start();
            }
        }
    }
    
    function getAlbumsFromDb(){
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
        
        $result= mysqli_query($conn, "SELECT * FROM ALBUMS WHERE isUploaded = 0 LIMIT 1");
        $result_array = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $result_array[] = $row;
        }
        mysqli_close($conn);
        return $result_array ;
    }
    
    function UpadeAlbumsDB($id){
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
    
        $result= mysqli_query($conn, "UPDATE ALBUMS SET isUploaded=1 WHERE id='$id'");
        mysqli_close($conn);
    }
    
    function getPhotos($id){
        $photos= array();
        $photos_single= array();
        $photos_single= FB_Chunk($id.'/photos');
        $photos= array_merge($photos, $photos_single);
        return $photos ;
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
    
        $id= mysqli_real_escape_string($conn, $array['id']);
        $created_time= mysqli_real_escape_string($conn, $array['created_time']);
        $comments= mysqli_real_escape_string($conn, $array['comments']);
        $from= mysqli_real_escape_string($conn, $array['from']);
        $height= mysqli_real_escape_string($conn, $array['height']);
        $width= mysqli_real_escape_string($conn, $array['width']);
        $icon= mysqli_real_escape_string($conn, $array['icon']);
        $source= mysqli_real_escape_string($conn, $array['source']);
        $images= mysqli_real_escape_string($conn, $array['images']);
        $picture= mysqli_real_escape_string($conn, $array['picture']);
        
        $sql='INSERT INTO photos (l_id, a_id, comments, created_time, height, width, icon, source, images, picture, photo_id) VALUES 
            ( 0, 0, \''.$comments.'\', \''.$created_time.'\', \''.$height.'\', \''.$width.'\', \''.$icon.'\', \''.$source.'\', \''.$images.'\', \''.$picture.'\', \''.$id.'\');' ;
        
        echo $sql ;
    
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
    
        $result= mysqli_query($conn, "SELECT 1 FROM photos WHERE photo_id='$id' LIMIT 1");
    
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
