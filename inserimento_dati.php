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
  
  if(!isset($_SESSION['login_name'])) header ('location: index.php');
  ?>

  <title>Inserimento dati</title>
<style>
.box{
  padding: 1rem;
  margin: 20px 0;
  border:1px solid rgba(0,0,0,.3);
  position: relative;
}
.box > .title{
  position: absolute;
  top: 0;
  background-color :white;
  transform: translateY(-65%);
  padding: 0px;
  font-size: 20px;
  text-transform:capitalize;

}
td{
  max-width: 300px;
  min-width: 150px;
}
tr td:nth-child(2) {

  max-width: 120px;
  min-width: 60px;
}

</style>
</head>
<body class="container">

<?php
include 'core/database/conn.php';
include 'core/database/funzioni.php';
include 'core/php/components/nav.php';
include 'core/php/components/secondNav.php';

$tabellaCorrente = isset ($_GET['table']) ? $_GET['table'] : 'Pazienti';

if( isset( $_POST['submit'] ) && $_POST['submit'] === "add")
{
  $valori = [];
  foreach ($_POST as $key => $value) {
    $valori[$key] = filter_var($value, FILTER_SANITIZE_STRING);
  }
  addTableRow($conn,$valori);
}
else if( isset( $_POST['submit'] ) && $_POST['submit'] === "modifica")
{
  $valori = [];
  foreach ($_POST as $key => $value) {
    $valori[$key] = filter_var($value, FILTER_SANITIZE_STRING);
  }
  editTableRow($conn,$valori);
}
else if( isset( $_POST['submit'] ) && $_POST['submit'] === "elimina")
{
  $valori = [];
  foreach ($_POST as $key => $value) {
    $valori[$key] = filter_var($value, FILTER_SANITIZE_STRING);
  }
  deleteTableRow($conn,$valori);
}

?>


<div class="box">
  <div class="title">
  <?= $tabellaCorrente ?> 
  </div>
  <div class="body d-flex"style="max-height: 400px;overflow-y: scroll;" >
    <div class="w-100" >
      <!-- Button trigger modal per aggiunta paziente -->
      <button <?php if($tabellaCorrente == 'medici') echo 'disabled'; ?> type="button" class="btn btn-success" data-toggle="modal" data-target="#pazienteModal">
        Aggiungi in <?= $tabellaCorrente ?> 
      </button>
      
      <!-- Modal per aggiunta paziente-->
      <div class="modal fade" id="pazienteModal" tabindex="-1" role="dialog" aria-labelledby="pazienteModal" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Aggiungi in <?= $tabellaCorrente ?> </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              
            <form action="" method="POST">
              <?php
                $colonne = getColumnsOf($conn,$tabellaCorrente);
                $nCol = count ($colonne) + 1;
                foreach ($colonne as $key => $value) { if($value['Field'] != 'id') {?>
               
                <div class="form-group">
                  <label for="<?= $value['Field'] ?>"><?= $value['Field'] ?></label>
                  <input <?php if($value['Null'] == 'NO') echo "required" ?> name="<?= $value['Field'] ?>" type="text" class="form-control" >
                </div>
                  
                <?php  }} ?>
             
            </div>
            <div class="modal-footer">
              <input hidden type="text" name="tableName" value="<?= $tabellaCorrente ?>">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" name="submit" value="add" class="btn btn-primary">Conferma</button>
            </form>
            </div>
          </div>
        </div>
      </div>

      <table class=" mh-50 mt-3 w-100 table table-striped table-bordered table-hover table-sm" style="min-width:400px" >
        <thead class="thead-dark w-100">
          <tr>
          <?php
            $colonne = getColumnsOf($conn,$tabellaCorrente);
            $nCol = count ($colonne) + 1;
            foreach ($colonne as $key => $value) {
              echo "<th scope='col'>$value[Field]</th>";
            }
            
          ?>
            <th scope='col'>Azioni</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $pazienti = getAllOf($conn,$tabellaCorrente);
          if (!$pazienti)
              echo "<td colspan='$nCol'>Nessun dato</td>";
          else{
            foreach ($pazienti as $key => $value) {
             ?>
                <tr>
                <form action='' method='POST'>

                  <?php  foreach ($value as $key1 => $value1) {
                    $readOnly = $key1 == 'id' || $key1 == 'pass' ? 'readonly' : '';
                   echo "
                   <td> <input $readOnly  class=' form-control'  type='text' name='$key1' value='$value1' ></td>
                   ";
                  } ?>
                  
                  <td class='d-flex'>
                  <input class='form-control'  type='text' name='tableName' value='<?= $tabellaCorrente ?>' hidden>
                  <button name='submit' value='modifica' type='submit' class='btn btn-info'>I</button>
                  <button name='submit' value='elimina' type='submit' class='ml-2 btn btn-danger'>X</button>
                  </td>
                </form>
                </tr>
              <?php
            }
          }
         ?>

        </tbody>
      </table>
    </div>
  </div>
</div>




</body>
</html>