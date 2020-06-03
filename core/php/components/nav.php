<nav class=" navbar navbar-expand-lg navbar-light bg-light">
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
            <a class="nav-link" href="reparto.php">Reparto</a>
          </li>
        <?php } else {?>
        <li class="nav-item">
          <a class="nav-link" href="prescrizione.php">Prescrizioni</a>
        </li>

      <?php } } ?>
  </div>
</nav>

<?php if (isset($_SESSION['login_name']) && isset($_SESSION['login_ruolo'])) {?>
<div class="row">
  
  <div class="col"></div>
  <div class="col">
    <?php 
    $namePage = substr( $_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'],"/") + 1);
    $namePage = substr($namePage, 0, strrpos($namePage,".")); 
    $namePage = $namePage != "index" ? $namePage : "login";
    ?>
    <p class="text-center mt-2 p-3 text-capitalize font-weight-bold" style="border-radius: 20px">
      <span><?= $namePage ?></span>
    </p>  
  </div>
  <div class="col">
    <p class="text-center mt-2 p-3 border" style="border-radius: 20px">
      <span><?= $_SESSION['login_name'] ?> | <?= $_SESSION['login_ruolo'] ?></span>
    </p>
  </div>
  
  
</div>

<?php } ?>
