<?php
require_once '../functions/checkLogin.php';

//Caso clique em pesquisar
if (isset($_POST['search'])) {
  $mes = ($_POST['month']);
  header("location: indicators.php?month=".$mes);
}

?>