<?php
// $createDB = file_get_contents( 'core/database/create.sql' );
// $servername = "localhost";
// $username = "root";
// $password = "";
$db = "heroku_aea42162b6df481";
$servername = "eu-cdbr-west-03.cleardb.net";
$username = "b4838df2bd8c99";
$password = "37af57a8";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$db;charset=utf8", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );   
  //$conn->query($createDB);

} catch(PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}


?>