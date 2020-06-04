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

  <title>Registrazione</title>

</head>
<body class="container">

<?php
include 'core/php/components/nav.php';
include 'core/database/conn.php';
include 'core/database/funzioni.php';


if( isset($_POST['reg_email']) )
{

  // $nome = filter_var($_POST['reg_nome'], FILTER_SANITIZE_STRING);
  // $ruolo = filter_var($_POST['reg_ruolo'], FILTER_SANITIZE_STRING);
  // $email = filter_var($_POST['reg_email'], FILTER_SANITIZE_STRING);
  // $pass = filter_var($_POST['reg_pass'], FILTER_SANITIZE_STRING);
  
  $stato = registrazione($conn,$_POST['reg_nome'],$_POST['reg_ruolo'],$_POST['reg_email'],$_POST['reg_pass']);
}

?>

<div  class="w-100 d-flex justify-content-center" >

<form autocomplete="off" class="mt-3; p-3" style="max-width:600px; width:400px;" action="registrazione.php" method="POST">
<?php if(isset($_POST['reg_email']) && isset($stato) && $stato )  {?>
  <div class="alert alert-success" role="alert">
  Correttamente registrato
  </div>
<?php } else if(isset($_POST['reg_email']) && isset($stato) && !$stato ){?>
  <div class="alert alert-danger" role="alert">
    E-mail |<?php echo $_POST['reg_email'] ?>| esistente effettua il login <a href="index.php">login</a>
  </div>
<?php } ?>

  <div class="form-group">
    <label for="InputNome">nome</label>
    <input name="reg_nome" type="text" class="form-control" id="InputEmail" aria-describedby="emailHelp" required>
  </div>
  <div class="form-group">
    <label for="InputEmail">Email</label>
    <input name="reg_email" type="email" class="form-control" id="InputEmail" aria-describedby="emailHelp" required>
  </div>
  <div class="form-group">
    <label for="InputPassword">Password</label>
    <input name="reg_pass" type="password" class="form-control" id="InputPassword" required>
  </div>
  <div class="input-group">
  <div class="input-group-prepend">
    <label class="input-group-text" for="inputGroupSelect01">Ruolo</label>
  </div>
  <select name="reg_ruolo" class="custom-select" id="inputGroupSelect01" required>
    <option value="" selected>scegli...</option>
    <option value="Dottore">Dottore</option>
    <option value="Infermiere">Infermiere</option>
  </select>
  </div>
  <div class="form-group">
    <label class="label">Hai già un account? 
      <a href="index.php">Login</a>
    </label>
  </div>
  <button type="submit" class="btn btn-primary float-right">Registrati</button>
</form>

</div>

</body>
</html>