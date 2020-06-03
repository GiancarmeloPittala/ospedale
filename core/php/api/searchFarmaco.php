<?php

include './conn.php';

try {
  $json_str = file_get_contents('php://input');
  $queryValue = json_decode($json_str);

  $dati = $conn->query("SELECT * FROM farmaci where  barcode = '$queryValue' or qrcode = '$queryValue'");
  
  if($dati) $dati = $dati->fetchAll(PDO::FETCH_ASSOC);
  else $dati = [];
} catch(PDOException $e) {

  echo ("Connection failed: " . $e->getMessage());
  $dati = [];
}

// print_r($dati); 

$conn = null;
echo json_encode($dati);


?>