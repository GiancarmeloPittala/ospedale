<ul class="nav justify-content-center">
<?php
 $nomeTabelle = getTableName($conn);
  foreach ($nomeTabelle as $key => $value) {
    foreach ($value as $key1 => $value1) {
      echo "
      <li class='nav-item'>
        <a class='nav-link active' href='inserimento_dati.php?table=$value1'>$value1</a>
      </li>
      ";
    }

 } ?>
 </ul>
  
