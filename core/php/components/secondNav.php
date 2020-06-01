<ul class="nav justify-content-center">
<?php
 $nomeTabelle = getTableName($conn);
  foreach ($nomeTabelle as $key => $value) {
    echo "
    <li class='nav-item'>
      <a class='nav-link active' href='inserimento_dati.php?table=$value[Tables_in_ospedale]'>$value[Tables_in_ospedale]</a>
    </li>
    ";

 } ?>
 </ul>
  
