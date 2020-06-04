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

  <title>Prescrizone</title>
</head>
<body class="container">

<?php
include 'core/php/components/nav.php';
include 'core/database/conn.php';
include 'core/database/funzioni.php';
if( isset($_SESSION['login_ruolo']) && $_SESSION['login_ruolo'] == "infermiere" ) header( "Location: index.php ");

$nomeDottore = isset($_SESSION['login_name']) ? $_SESSION['login_name'] : "erorr";
$idDottore = isset($_SESSION['login_id']) ? $_SESSION['login_id'] : die("erorr id not found !");

?>

    <form action="" method="GET"> 
<div class="box">
  <div class="title">
      Nuova ricetta medica</b>
  </div>
  <div class="body">
      <div class="row">     
        <!-- sezione utente -->
        <div class="col-12 col-lg-6 my-2">
          <div class="d-flex">
            <p class="input-group-text">utente</p>
              <select id="pazienteSelect" required name="paziente" class="form-control">
              <option  value="">..seleziona un paziente</option>
              <?php  
                $utenti = getAllOf($conn,'pazienti');
                foreach ($utenti as $key => $value) { ?>
                  <option <?php $gg = isset($_GET['paziente']) && $_GET['paziente'] == $value['id'] ? 'selected' : ''; echo $gg ?> value="<?= $value['id'] ?>"> <?= $value['cognome']." ".$value['nome'] ?> </option>
              <?php } ?>
              </select>
              <button  class="btn btn-success">seleziona</button>
              </form>
          </div>
        </div>
    

      <div class="col-12 col-lg-6 row my-2">

        <?php
          if(isset($_GET['paziente']) ){
            $paziente = getAllOf($conn,'pazienti',['id' => $_GET['paziente'] ]);

            if($paziente)
            foreach ($paziente[0] as $key => $value) if($key != 'id') {?>
              <div class="col-6">
                <div class="d-flex">
                  <p class="input-group-text bg-info text-white" style="font-size: 12px"><?= $key?></p>
                  <input readonly class="form-control" value="<?= $value?>"></input>
                </div>
              </div>
            <?php }} ?>
      </div>
    </div>

    <div class="row">
       <!-- sezione farmaco -->
        <div class="col-12 col-lg-6 my-2" >
          <div class="d-flex">
            <p class="input-group-text">Farmaco</p>
              <input id="inputFarmarcoRicerca" class="form-control" minlength="2" type="text">
              <button type="button" class="btn btn-success" id="buttonCercaFarmaco">Cerca</button>
          </div>
        </div>
    

      <div class="col-12 col-lg-6 row my-2" id="listaFarmaci" ></div>

      </div>

  </div>
  
    <div class="box" >
      <div class="title">
        lista farmaci
      </div>
      <div >
      
      <table class="table table-responsive w-100" id="tabellaFarmaci">
        <tbody id="listaFarmaciAggiunti">
          <tr>
           <th scope="col">#</th>
            <th scope="col">Nome Farmaco</th>
            <th scope="col">Qrcode</th>
            <th scope="col">Barcode</th>
            <th scope="col">QTA</th>
            <th scope="col">Dose Assunzione</th>
            <th scope="col">Tempi Assunzione</th>
            <th scope="col">Note</th>
          </tr>
        </tbody>
      </table>

      </div>
      <div class="row" >
        <div class="col"></div>
        <div class="col"></div>
        <div class="col text-right"><button type="button" class="btn btn-success" onclick="conferma()">conferma</button></div>
      </div>
    </div>

    <div>
        <div class="box">
              <div class="title">
                Ultima ricetta medica
              </div>
              <div id="ultimaprescrizoni" >

              </div>
        </div>
    </div>

</body>

<script>
function addShowPrescrizione(idMedico){
  // console.log(idMedico)
  fetch('./core/php/api/ultimaRicetta.php', {
      method: 'POST',
      body: JSON.stringify(idMedico),
      headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/*; charset=UTF-8',
    }
    }).then(data => data.json())
    .then(data => {
      
        let div = document.getElementById('ultimaprescrizoni')
        div.innerHTML = "";
        let table = document.createElement('table')
        let thead = document.createElement('thead')
        let tbody = document.createElement('tbody')
        
        thead.innerHTML = `
          <tr>
            <th scope="col">Progressivo Ricetta</th>
            <th scope="col">Numero Farmarci</th>
            <th scope="col">Numero Farmaci Totatle</th>
            <th scope="col">Prezzo totale</th>
          </tr>
        `
        tbody.innerHTML = `
          <tr>
            <th>${data.num_pre}</th>
            <td>${data.numeroFarmaci}</td>
            <td>${data.TotalQta}</td>
            <td>${data.totalPrezzo} €</td>
          </tr>
        `;

        table.classList.add("table", "table-responsive-lg", "w-100")
        table.append(thead)
        table.append(tbody)
        div.append(table);

    }).catch(e => console.error(e))


}

function conferma(){

  const tableRow = document.querySelectorAll("#tabellaFarmaci tbody tr");
  const pazienteSelect = document.getElementById('pazienteSelect')
  const idPaziente = pazienteSelect.options[pazienteSelect.selectedIndex].value;
  const idMedico = "<?= $_SESSION['login_id'] ?>";
  let newRicetta = [];
  for(let i = 1; i<tableRow.length; i++){
    let t = tableRow[i].querySelectorAll("input");
    newRicetta.push({
      idPaziente : idPaziente,
      idMedico: idMedico,
      idFarmaco: t[0].value,
      qta: t[4].value,
      doseAssunzione: t[5].value,
      tempiAssunzione: t[6].value,
      Note: t[7].value,
    })
  }
  
  newRicetta = JSON.stringify(newRicetta);
  fetch('./core/php/api/addRicetta.php', {
      method: 'POST',
      body: newRicetta,
      headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/*; charset=UTF-8',
    }
    })
    .then(data => {
      /**pulisco la table */
      let primo = 1
      let table = document.getElementById('tabellaFarmaci');
      let rowCount = table.rows.length;
      for (let i = primo; i < rowCount; i++) 
          table.deleteRow(primo);

      addShowPrescrizione(idMedico);

    }).catch(e => console.error(e))
}
function elimina(e){
      const row = e.closest("tr");
      document.getElementById('tabellaFarmaci').deleteRow(row.rowIndex);
}

  window.onload = () => {

    
    let cercaFarmacoButton = document.getElementById('buttonCercaFarmaco');
    let inputFarmarcoRicerca = document.getElementById('inputFarmarcoRicerca');
    let divFarmaci = document.getElementById('listaFarmaci');
    
    inputFarmarcoRicerca.onkeypress = function (e) {
      if (e.keyCode == 13) {
      e.preventDefault()
      cerca()
      }
    }

    cercaFarmacoButton.onclick = () => {cerca()}

    function addFarmacoOnList(dati){
      
      const {id, nome, qrcode, barcode} = dati[0];
      

      let listaFarmaci = document.getElementById('listaFarmaciAggiunti');
      listaFarmaci.innerHTML += `
        <tr>
          <th scope="row"><button onclick="elimina(this)" type="button" class="btn btn-danger">X</button></th>
          <td style="display:none"><input  class="form-control" type="text" value="${id}"></td>
          <td><input class="form-control" readonly type="text" value="${nome}"></td>
          <td><input class="form-control" readonly type="text" value="${qrcode}"></td>
          <td><input class="form-control" readonly type="text" value="${barcode}"></td>
          <td><input class="form-control" type="number" value="1"></td>
          <td><input class="form-control" type="text" value=""></td>
          <td><input class="form-control" type="text" value=""></td>
          <td><input class="form-control" type="text" value=""></td>
        </tr>
      `;
    }

    function cerca(){
      let codiceRicerca = inputFarmarcoRicerca.value;
      if(codiceRicerca.length < 2) return 

      fetch('./core/php/api/searchFarmaco.php', {
        method: 'POST',
        body: JSON.stringify(codiceRicerca),
        headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/*; charset=UTF-8',
      }
      })
      .then( data => data.json() )
      .then( data => {
        divFarmaci.innerHTML = "";
        if(data.length == 0){
          divFarmaci.innerHTML += `
              <div class="col-12">
                <div class="alert alert-danger" role="alert">
                  Nessuna corrispondenza
                </div>
              </div>
            `;
        }else if(data.length == 1){
          
          addFarmacoOnList(data);

        }else{
          
          divFarmaci.innerHTML += `
            <div class="col-12">
              <div class="alert alert-danger" role="alert">
                Più di una corrispondenza rendere univoco il codice
              </div>
            </div>
            `;

        }
      })
      .catch( e => console.error(e) )
    }
  }

</script>
</html>