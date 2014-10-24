<?php 
//  require_once __DIR__ . '/db_connect.php';
 
//     // connecting to db
//     $db = new DB_CONNECT();
    
//     // Check connection
// if (!$db) {
//     die("Connection failed: " . mysql_connect_error());
// }


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

// // sql to create table ALBUMS
// $sql = 'CREATE TABLE IF NOT EXISTS ALBUMS (
//   id int(20) NOT NULL AUTO_INCREMENT,
//   facebook_id bigint(20) DEFAULT NULL,
//   type varchar(50) DEFAULT NULL,
//   name varchar(50) DEFAULT NULL,
//   count int(10) DEFAULT NULL,
//   isUploaded tinyint(1) unsigned zerofill NOT NULL,
//   PRIMARY KEY (`id`)
// ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;';

//sql to create table likes
// $sql= 'CREATE TABLE IF NOT EXISTS `LIKES` (
//   `id` int(20) NOT NULL AUTO_INCREMENT,
//   `facebook_id` bigint(20) DEFAULT NULL,
//   `category` varchar(50) DEFAULT NULL,
//   `name` varchar(50) DEFAULT NULL,
//   `isUploaded` tinyint(1) unsigned zerofill NOT NULL,
//   PRIMARY KEY (`id`)
// ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;';


//sql to create table photos
$sql = 'CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `l_id` int(10) DEFAULT NULL,
  `a_id` int(10) DEFAULT NULL,
  `comments` text,
  `created_time` varchar(50) DEFAULT NULL,
  `from` text,
  `height` int(10) DEFAULT NULL,
  `width` int(10) DEFAULT NULL,
  `icon` text,
  `source` text,
  `images` text,
  `picture` text,
  `photo_id` bigint(20) DEFAULT NULL,
  `createAt` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updateAt` time DEFAULT NULL,
  `isUploaded` tinyint(1) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;';

$retval = mysqli_query($conn, $sql);
if(! $retval )
{
  die('Could not create table: ' . mysql_error());
}
echo "Table photos created successfully\n";

mysqli_close($conn);


// if (mysql_query($db, $sql)) {
//     echo "Table MyGuests created successfully";
// } else {
//     echo "Error creating table: " . mysql_error($db);
// }
    
    
    
?>