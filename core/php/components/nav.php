<?php


?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="index.php">Ospedale</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <?php if (!isset($_SESSION['login_name']) ) { ?>
      <li class="nav-item active">
        <a class="nav-link" href="registrazione.php">Registrazione</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php">Login</a>
      </li>
        <?php } else {?>
          <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
        <?php }?>
      <?php if(isset($_SESSION['login_ruolo'])) {?>
        <li class="nav-item">
          <a class="nav-link" href="inserimento_dati.php">Inserimento dati</a>
        </li>
        <?php if($_SESSION['login_ruolo'] == "infermiere" ) {?>
          <li class="nav-item">
            <a class="nav-link" href="#">Reparto</a>
          </li>
        <?php } else {?>
        <li class="nav-item">
          <a class="nav-link" href="#">Prescrizioni</a>
        </li>

      <?php } } ?>
  </div>
</nav>
