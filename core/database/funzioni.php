<?php 

function registrazione($conn,$nome,$tipo,$email,$pass,$reparto = null){

  $pass = password_hash($pass, PASSWORD_DEFAULT);
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
    $sql = "SELECT email,pass,nome,ruolo from medici where email = :email ";
    $stm = $conn->prepare($sql);
    $stm->bindParam(":email",$email, PDO::PARAM_STR);
    $stm->execute();
  
    if( $stm->rowCount() == 0){//email inesistente
      return ["error" => true, "mess" => "Account non trovato"];
    }
  
    $medico = $stm->fetchAll()[0];
    if (password_verify($pass, $medico['pass'])) {
      $_SESSION['login_name'] = $medico['nome'];
      $_SESSION['login_ruolo'] = $medico['ruolo'];

      return ["error" => false, "mess" => "Login effettuato"];
  } else {
    return ["error" => true, "mess" => "password errata"];
  }
   
  } catch(PDOException $e) {
      //echo "query failed: " . $e->getMessage();
      return ["error" => true, "mess" => "query fallita"];
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

function getAllOf($conn,$tableName){
    try {
      return $conn->query("select * FROM $tableName order by id desc;")->fetchAll(PDO::FETCH_ASSOC);
  
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
    print_r($valori);



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

?> 