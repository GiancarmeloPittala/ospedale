<?php

include './conn.php';

try {
  $json_str = file_get_contents('php://input');
  $queryValue = json_decode($json_str,true);

  $ultimoNumRicetta = $conn->query("SELECT num_pre from prescrizioni order by num_pre desc limit 1");
  
  $ultimoNumRicetta =  $ultimoNumRicetta ?  $ultimoNumRicetta->fetchAll(PDO::FETCH_ASSOC)[0]['num_pre'] + 1 : 1;


  foreach ($queryValue as $key => $value) {


    $stm = $conn->prepare("
      INSERT INTO prescrizioni(num_pre,id_farmaco,id_paziente,id_medico,dose_assunzione,note,qta,tempi_assunzione)
      VALUES (:num_pre,:idFarmaco,:idPaziente,:idMedico,:dose,:note,:qta,:tempi) ");
      
      $stm->bindParam(":num_pre",$ultimoNumRicetta, PDO::PARAM_INT);
      $stm->bindParam(":idFarmaco",$value['idFarmaco'], PDO::PARAM_STR);
      $stm->bindParam(":idPaziente",$value['idPaziente'], PDO::PARAM_STR);
      $stm->bindParam(":idMedico",$value['idMedico'], PDO::PARAM_STR);
      $stm->bindParam(":dose",$value['doseAssunzione'], PDO::PARAM_STR);
      $stm->bindParam(":note",$value['Note'], PDO::PARAM_STR);
      $stm->bindParam(":qta",$value['qta'], PDO::PARAM_STR);
      $stm->bindParam(":tempi",$value['tempiAssunzione'], PDO::PARAM_STR);
  
      $stm->execute();
  }

  // if($dati) $dati = $dati->fetchAll(PDO::FETCH_ASSOC);
  // else $dati = [];
} catch(PDOException $e) {

  echo ("Connection failed: " . $e->getMessage());

}






?>