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

  <title>LOGOUT</title>

</head>
<body class="container">

<?php
session_destroy();
include 'core/php/components/nav.php';
?>

<div class="alert alert-danger" role="alert">
  Logout effettuato correttamente! si prega di effettuare un 
  <a href="index.php">login</a>
   per utilizzare i nostri servizi 
</div>

</body>
</html>