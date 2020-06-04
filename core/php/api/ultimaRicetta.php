<?php

include './conn.php';

try {
  $json_str = file_get_contents('php://input');
  $queryValue = json_decode($json_str);

  $stm = $conn->prepare("SELECT f.num_pre, count(f.id_farmaco) as numeroFarmaci, sum(f.qta) as TotalQta, (
                            select sum(f.qta * fa.prezzo) from prescrizioni f left join farmaci fa on f.id_farmaco = fa.id WHERE f.id_medico = :id group by f.num_pre order by f.num_pre desc limit 1
                          ) as totalPrezzo 
                        from prescrizioni f 
                        left join farmaci fa on f.id_farmaco = fa.id 
                        left join pazienti p on f.id_paziente = p.id 
                        left join medici m on f.id_medico = m.id 
                        WHERE f.id_medico = :id group by f.num_pre order by f.num_pre desc limit 1;");
      
     
      $stm->bindParam(":id",$queryValue, PDO::PARAM_INT);
      
      $result = $stm->execute();

      $dati = $result ? $stm->fetchAll(PDO::FETCH_ASSOC) : array();

      $dati = $dati[0];
} catch(PDOException $e) {

  echo ("Connection failed: " . $e->getMessage());
  $dati = array();
}

$conn = null;
echo json_encode($dati);

?>