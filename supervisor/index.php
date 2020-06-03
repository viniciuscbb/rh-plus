<?php
require_once '../db/config.php';
require_once '../functions/checkLogin.php';
require_once '../functions/global.php';

function getUserName()
{
  $id_login = $_SESSION['id_login'];
  $conection = conection();
  $sql = "SELECT nome FROM adm WHERE id_adm='$id_login'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $nome = $row['nome'];
  return $nome;
}

function showSector()
{
  $conection = conection();
  $id_login = $_SESSION['id_login'];
  $sql = "SELECT * FROM setores where id_responsavel = '$id_login'";
  $query = mysqli_query($conection, $sql);

  while ($row = mysqli_fetch_array($query)) {
    $id_setor = $row['id_setor'];
    $setor    = $row['setor'];
    echo '<a href="sector.php?sector='.$id_setor.'" class="list-group-item list-group-item-action">
            '.$setor.'
          </a>';
  }
}

?>


<!DOCTYPE html>
<html lang="pt_BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/supervisor.css">
  <title>Supervisor - RH Plus</title>
</head>

<body>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">Bem-Vindo, <?php echo getUserName(); ?>!</li>
    </ol>
  </nav>

  <div class="setores">
    <p class="lead"></p>
    <div class="alert alert-info" role="alert">
      Nível: <?php echo getOffice(); ?>
    </div>
    <div class="list-group">
      <a class="list-group-item list-group-item-action active">
        Escolha o setor responsável abaixo.
      </a>
      <?php echo showSector(); ?>
    </div>
  </div>


  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
</body>

</html>