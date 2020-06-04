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

  <title>Login</title>

</head>
<body class="container">

<?php



include 'core/database/conn.php';
include 'core/database/funzioni.php';

if( isset($_POST['log_email']) )
{

  // $email = filter_var($_POST['log_email'], FILTER_SANITIZE_STRING);
  // $pass = filter_var($_POST['log_pass'], FILTER_SANITIZE_STRING);
  
  $stato = login($conn,$_POST['log_email'],$_POST['log_pass']);
}

include 'core/php/components/nav.php';
?>

<div  class="w-100 d-flex justify-content-center" >

<form class="mt-3; p-3" style="max-width:600px; width:300px;" action="index.php" method="POST">
  
  <?php if(isset($stato) && !$stato['error']) {?>
  <div class="alert alert-success" role="alert">
    <?php echo $stato['mess'] ?>
  </div>
  <?php } else if (isset($stato) && $stato['error']) {?>
    <div class="alert alert-danger" role="alert">
    <?php echo $stato['mess'] ?>
  </div>
  <?php } ?>
  <div class="form-group">
    <label for="InputEmail">Email</label>
    <input name="log_email" type="email" class="form-control" id="InputEmail" aria-describedby="emailHelp">
  </div>
  <div class="form-group">
    <label for="InputPassword">Password</label>
    <input name="log_pass"type="password" class="form-control" id="InputPassword">
  </div>
  <div class="form-group">
    <label class="label">Non sei registrato? 
      <a href="registrazione.php">registrati</a>
    </label>
  </div>
  <button type="submit" class="btn btn-primary float-right">Login</button>
</form>

</div>


</body>
</html>