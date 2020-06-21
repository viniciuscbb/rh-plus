<?php
require_once '../db/config.php';
require_once '../functions/checkLogin.php';
require_once '../functions/global.php';

acessoRestrito(2);

//Caso clique em salvar
if (isset($_POST['save'])) {
  $name = ($_POST['name']);
  $date = ($_POST['date']);
  $type = ($_POST['type']);

  if ($type == "Escolher...") {
    header("location: holidays.php?msg=1");
  } else {
    $conection = conection();
    $sql = "INSERT INTO feriados (nome, data, tipo) VALUES ('$name', '$date', '$type')";
    $query = mysqli_query($conection, $sql);
    if ($query) {
      header("location: holidays.php?msg=2");
    } else {
      header("location: holidays.php?msg=3");
    }
  }
}
