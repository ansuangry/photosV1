    <?php

        //  $database = new mysqli('instanceNo.db.xeround.com', 'username', 'password', 'databasename', 'port');

        //  if($database->connect_errno > 0){
        //      die ('Database Error' . $database->connect_error); 
        //  }else{
        //      die ('Connected!');
        //  }
        
    $ip =  getenv("REMOTE_ADDR");
    $port = "3306";
    $user = getenv('C9_USER');
    $DB = "C9";
    
     // Create connection
    $con=mysqli_connect($ip, $user, "", "C9");

    //mysqli_connect(host,username,password,dbname); << guideline

    // Check connection
    if (mysqli_connect_errno()) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    // echo "-1" ;
    // $conn = mysql_connect($ip, $user, '', $db, $port)or die(mysql_error());
    // echo "-2" ;
    // mysql_select_db('$db','$conn')or die(mysql_error());
    // echo "-3" ;
    // mysql_query("select * from YourTableName",'$conn')or die(mysql_error());    
    // echo "-4" ;
    // var_dump($conn);
    // echo "-5" ;
?>