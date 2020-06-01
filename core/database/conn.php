<?php
$createDB = file_get_contents( 'core/database/create.sql' );
$servername = "localhost";
$username = "root";
$password = "";

try {
  $conn = new PDO("mysql:host=$servername;charset=utf8", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );   
  $conn->query($createDB);

} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
  die();
}


?>