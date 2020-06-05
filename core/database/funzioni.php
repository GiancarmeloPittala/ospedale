<?php 

function registrazione($conn,$nome,$tipo,$email,$pass,$reparto = null){

  //$pass = password_hash($pass, PASSWORD_DEFAULT);
  try {
  $sql = "INSERT INTO medici (nome, ruolo, email, pass,id_reparto) VALUES (:nome,:tipo,:email,:pass,:reparto) ";
  $stm = $conn->prepare($sql);


  $stm->bindParam(":nome",$nome, PDO::PARAM_STR);
  $stm->bindParam(":tipo",$tipo, PDO::PARAM_STR);
  $stm->bindParam(":email",$email, PDO::PARAM_STR);
  $stm->bindParam(":pass",$pass, PDO::PARAM_STR);
  $stm->bindParam(":reparto",$reparto, PDO::PARAM_STR);

  $result = $stm->execute();

    return true;
  } catch(PDOException $e) {
    //echo "query failed: " . $e->getMessage();
    return false;
  }

}
function login($conn,$email,$pass){
  
  try {
    $sql = "SELECT email,pass,nome,ruolo,id from medici where email = :email ";
    $stm = $conn->prepare($sql);
    $stm->bindParam(":email",$email, PDO::PARAM_STR);
    $stm->execute();
  
    if( $stm->rowCount() == 0){//email inesistente
      return array("error" => true, "mess" => "Account non trovato");
    }
  
    $medico = $stm->fetchAll();

    $medico = $medico[0];
    if ($pass == $medico['pass'] ) {
      $_SESSION['login_name'] = $medico['nome'];
      $_SESSION['login_ruolo'] = $medico['ruolo'];
      $_SESSION['login_id'] = $medico['id'];

      return array("error" => false, "mess" => "Login effettuato");
  } else {
    return array("error" => true, "mess" => "password errata" );
  }
   
  } catch(PDOException $e) {
      //echo "query failed: " . $e->getMessage();
      return array("error" => true, "mess" => "query fallita");
  }

}
function getColumnsOf($conn,$tableName){

  try {
    return $conn->query("SHOW COLUMNS FROM $tableName;")->fetchAll(PDO::FETCH_ASSOC);

  } catch(PDOException $e) {
    //echo "query failed: " . $e->getMessage();
    return false;
  }

}
function getAllOf($conn,$tableName,$where = false){

    try {
      $w= "";
      if($where) {
        $w ="WHERE 1=1 ";
        foreach ($where as $key => $value) {
          $w .= "AND $key = '$value'"; 
        }
      }
      return $conn->query("select * FROM $tableName $w order by id desc;")->fetchAll(PDO::FETCH_ASSOC);
  
    } catch(PDOException $e) {
      //echo "query failed: " . $e->getMessage();
      return false;
    }
}
function addTableRow($conn,$valori){
  try {

    $nomeTabella = $valori['tableName'];
    unset($valori['tableName']);
    unset($valori['submit']);

    $nomeCampi = "";
    foreach ($valori as $key => $value) {
      $nomeCampi .= "$key,";
    }
    
    $nomeCampi = substr($nomeCampi,0,strlen($nomeCampi) -1);
    $valoreCampi = "";

    foreach ($valori as $key => $value) {
      $valoreCampi .= "'$valori[$key]',";
    }

    $valoreCampi = substr($valoreCampi,0,strlen($valoreCampi) -1);
    

    $sql = "INSERT INTO $nomeTabella ($nomeCampi) VALUES($valoreCampi)";
    $conn->query($sql);

    return true;

  } catch(PDOException $e) {
    echo "query failed: " . $e->getMessage();
    return false;
  }
}
function editTableRow($conn,$valori){
  try {

    
    $sql = "UPDATE $valori[tableName] SET ";
    
    /**rimuovo gli indici che non mi servono */
    unset($valori['tableName']);
    unset($valori['submit']);

    foreach ($valori as $key => $value) {
      if(strlen($value) != 0 )
      $sql .="$key = '$value', ";
    }
    //tolgo l'ultima virgola
    $sql = substr($sql,0,strlen($sql) - 2);
    
    $sql .= " where id = $valori[id];";
    
    $stm = $conn->query($sql);

    return true;

  } catch(PDOException $e) {
    echo "query failed: " . $e->getMessage();
    return false;
  }
}
function deleteTableRow($conn,$valori){
  try {

    $sql = "DELETE FROM $valori[tableName] where id = :id ";
    $stm = $conn->prepare($sql);
    $stm->bindParam(":id",$valori['id'], PDO::PARAM_INT);
    $stm->execute();

    return true;

  } catch(PDOException $e) {
    echo "query failed: " . $e->getMessage();
    return false;
  }
}
function getTableName($conn){
  try {
    
    return $conn->query("show tables;")->fetchAll(PDO::FETCH_ASSOC);

  } catch(PDOException $e) {
    echo "query failed: " . $e->getMessage();
    return false;
  }
}
function getAllRicettyByReparto($conn,$idreparto){
  try {
    $sql = "select 
    pr.id,
    pr.num_pre,  
    fa.nome as nome_farmaco,
    fa.prezzo as prezzo_farmaco,
    pr.qta,
    pr.qta * fa.prezzo as tot_prezzo,
    pr.dose_assunzione,
    pr.tempi_assunzione,
    pr.note,
    m.nome as nome_medico,
    fa.qrcode,
    fa.barcode,
    p.nome as nome_paziente, 
    p.cognome as cognome_paziente
    from prescrizioni pr 
    left join farmaci fa on pr.id_farmaco = fa.id 
    left join pazienti p on pr.id_paziente = p.id 
    left join medici m on pr.id_medico = m.id 
    where m.id_reparto = :id_reparto and pr.accettata = 0; ";

    $stm = $conn->prepare($sql);
    $stm->bindParam(":id_reparto",$idreparto, PDO::PARAM_INT);
    $result =  $stm->execute();
  
    $data = $result ? $stm->fetchAll(PDO::FETCH_ASSOC) : array();

    return $data;

  } catch(PDOException $e) {
      //echo "query failed: " . $e->getMessage();
      return false;
  }
}
function accettaRicetta($conn,$id,$id_reparto){
  try {

    $sql = "select num_pre from prescrizioni where id = :id";
    $stm = $conn->prepare($sql);
    $stm->bindParam(":id",$id, PDO::PARAM_INT);
    $result = $stm->execute();

    $num_pre = $stm->rowCount() > 0 ? $stm->fetchAll(PDO::FETCH_ASSOC) : null;
    $num_pre = $num_pre ? $num_pre[0]['num_pre'] : $num_pre;

    $sql = "  UPDATE prescrizioni set accettata = 1 where num_pre = $num_pre ";   

    $stm = $conn->query($sql);
  
    /**aggiorno la quantià dei prodotti nel pagazzino per quel reparto */

    $sql = "select id_farmaco, qta from prescrizioni where num_pre = (SELECT num_pre FROM prescrizioni WHERE id = $id);";
    $res = $conn->query($sql);

    
    if(!$res) return false; 
    
    $res = $res->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($res as $key => $value) {
      $sql = "UPDATE magazzino SET qta = qta - $value[qta] 
              where id_farmaco = $value[id_farmaco]
              and id_reparto = $id_reparto";
      $res = $conn->query($sql);
    }

    return true;

  } catch(PDOException $e) {
      echo "query failed: " . $e->getMessage();
      return false;
  }
}
function getFarmaciSottoLaSoglia($conn,$reparto){
    try {
      $sql = "SELECT *, (max_qta - (max_qta / 100 * 90)) as minQta  
      from magazzino m
      left join reparti r on r.id = m.id_reparto  
      left join farmaci f on f.id = m.id_farmaco 
      where (max_qta - (max_qta / 100 * 90)) > qta and id_reparto = :id_reparto;";
  
      $stm = $conn->prepare($sql);
      $stm->bindParam(":id_reparto",$reparto, PDO::PARAM_INT);
      $result =  $stm->execute();
  
      if($result) 
        return $stm->fetchAll(PDO::FETCH_ASSOC);
      else
        return array();
  
    } catch(PDOException $e) {
        echo "query failed: " . $e->getMessage();
        return false;
    }
}
?> 