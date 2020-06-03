<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php 
  include 'core/php/include/icon.php';
  include 'core/php/include/css.php'; 
  include 'core/php/include/js.php'; 
  include 'core/php/include/general.php'; 
  ?>

  <title>Reparto</title>
</head>
<body class="container">

<?php
include 'core/php/components/nav.php';
include 'core/database/conn.php';
include 'core/database/funzioni.php';
if( isset($_SESSION['login_ruolo']) && $_SESSION['login_ruolo'] == "dottore" ) header( "Location: index.php ");

$nomeDottore = isset($_SESSION['login_name']) ? $_SESSION['login_name'] : "erorr";
$idDottore = isset($_SESSION['login_id']) ? $_SESSION['login_id'] : die("erorr id not found !");

$repartoid = getAllOf($conn,"medici",['id' => $_SESSION['login_id'] ]);
$repartoid = $repartoid ? $repartoid[0]['id_reparto'] : "";

$nomeReparto = getAllOf($conn,"reparti",['id' => $repartoid]);
$nomeReparto= $nomeReparto ?  $nomeReparto = $nomeReparto[0]['nome'] : "";

if(isset($_GET['accettaRicetta']) && isset($_GET['id'])){
  $id = $_GET['id'];
  accettaRicetta($conn,$id,$repartoid);
}
?>

<div class="box">
  <div class="title">
    Farmaci da ordinare
  </div>
  <div class="body">
  <table class="table table-responsive w-100" >
    <?php 
      $farmaci = getFarmaciSottoLaSoglia($conn,$repartoid);
      if(count($farmaci) == 0){
        echo '
          <div class="alert alert-success" role="alert">
            Nessun farmaco supra la soglia del 90% di consumo
          </div>
        ';
      }else{
        echo '
          <div class="alert alert-danger" role="alert">
            Attenzione, risultano farmaci con quantità sotto il 90% di consumo
          </div>
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">NOME</th>
              <th scope="col">QTA</th>
              <th scope="col">QTA MAX</th>
              <th scope="col">QTA Minima</th>
            </tr>
          </thead>
        ';
        foreach ($farmaci as $key => $value) { ?>
            <tr>
              <th scope="row"><?= $value['id_farmaco'] ?></th>
              <td><?= $value['nome'] ?></td>
              <td><?= $value['qta'] ?></td>
              <td><?php echo $value['max_qta'] ?></td>
              <td><?php echo $value['minQta'] ?></td>
            </tr>
       <?php } } ?>
    </table>
  </div>
</div>


<div class="box">
  <div class="title" style="font-size:13px">
    Ricette mediche da confermare per il tuo reparto | <b><?= $nomeReparto ?> </b>
  </div>
  <div class="body">
    <table class="mh-50 mt-3 table-responsive table table-striped table-bordered table-hover table-sm">
      <thead>
      <tr>
      
        <?php 
          $ricetteNonConfermate = getAllRicettyByReparto($conn,$repartoid);
          foreach ($ricetteNonConfermate as $key => $value) {
           if($key == 0){ echo "<th>Accetta</th>";
            foreach ($value as $key1 => $value1) if($key1 != 'id'){?>
              <th scope="col"><?= $key1?></th>
          <?php }} ?>
        </tr>
      </thead>
      <tbody>
        <tr>
          <form action="" method="get">
            <td><button name="accettaRicetta" type="submit" class="btn btn-info">OK</button></td>
            <?php
              foreach ($value as $key1 => $value1) {?>
                  <?php
                    if($key1 == 'id'){
                      echo "<td style='display: none;'><input type='text' name='$key1' value='$value1'></input></td>";
                    }
                    else 
                    echo "<td>$value1</td>"; 
                  ?>
              <?php } ?>
            </form>
        </tr>
            <?php } ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>