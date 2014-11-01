<?php 
//SELECT * FROM ALBUMS ORDER BY id DESC LIMIT 1
//SELECT * FROM LIKES ORDER BY id DESC LIMIT 1
//SELECT * FROM photos ORDER BY id DESC LIMIT 1 ;

$Albums_count = getAlbumsCount();
$Like_count = getLikesCount();
$Photos_count = getPhotoCount();
echo "Albums : ".$Albums_count.'<br></br>';
echo "Likes : ".$Like_count.'<br></br>';
echo "Photos : ".$Photos_count.'<br></br>';

function getAlbumsCount(){
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
    
        $result= mysqli_query($conn, "SELECT * FROM ALBUMS ORDER BY id DESC LIMIT 1;");
        $result_array = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $result_array[] = $row;
        }
        // print_r($result_array);
        mysqli_close($conn);
        return $result_array[0]['id'];
    }
    
    function getLikesCount(){
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
    
        $result= mysqli_query($conn, "SELECT * FROM LIKES ORDER BY id DESC LIMIT 1;");
         $result_array = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $result_array[] = $row;
        }
        // print_r($result_array);
        mysqli_close($conn);
        return $result_array[0]['id'];
    }
    
    function getPhotoCount(){
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
    
        $result= mysqli_query($conn, "SELECT * FROM photos ORDER BY id DESC LIMIT 1;");
         $result_array = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $result_array[] = $row;
        }
        // print_r($result_array);
        mysqli_close($conn);
        return $result_array[0]['id'];
    }


?>