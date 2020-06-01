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

</style>
</head>
<body class="container">

<?php
include 'core/database/conn.php';
include 'core/database/funzioni.php';
include 'core/php/components/nav.php';
?>


<div class="box">
  <div class="title">
  Pazienti
  </div>
  <div class="body d-flex">
    <div class="w-100" >
      <!-- Button trigger modal per aggiunta paziente -->
      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#pazienteModal">
        Aggiungi un Paziente
      </button>

      <!-- Modal per aggiunta paziente-->
      <div class="modal fade" id="pazienteModal" tabindex="-1" role="dialog" aria-labelledby="pazienteModal" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              
            <form action="" method="POST">
              <div class="form-group">
                <label for="inputPazienteNomeAdd">nome</label>
                <input required name="inputPazienteNomeAdd" type="text" class="form-control" id="inputPazienteNomeAdd" aria-describedby="emailHelp">
              </div>
              <div class="form-group">
                <label for="inputPazienteCognomeAdd">Password</label>
                <input required name="inputPazienteCognomeAdd" type="text" class="form-control" id="inputPazienteCognomeAdd">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Conferma</button>
            </form>
            </div>
          </div>
        </div>
      </div>

      <table  class=" mt-3 w-100 table table-striped table-bordered table-hover table-sm ">
        <thead class="thead-dark">
          <tr>
          <?php
            $colonne = getColumnsOf($conn,'pazienti');
            foreach ($colonne as $key => $value) {
              echo "<th scope='col'>$value[Field]</th>";
            }
          ?>
          </tr>
        </thead>
        <tbody>
        <?php
          if (!getAllOf($conn,'pazienti'))
              echo "<td colspan='3'>Nessun dato</td>";
          else{
            /**<tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
          </tr> */
          }
         ?>
          
          
        </tbody>
      </table>

    </div>
  </div>
</div>



</body>
</html>