<?php
require_once '../db/config.php';
require_once '../functions/checkLogin.php';
require_once '../functions/global.php';

acessoRestrito(1);

function getUserName()
{
  $id_login = $_SESSION['id_login'];
  $conection = conection();
  $sql = "SELECT nome FROM adm WHERE id_adm='$id_login'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $nome = $row['nome'];

  $nome = explode(" ", $nome);

  return $nome[0];
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
    echo '<a href="sector.php?sector=' . $id_setor . '" class="list-group-item list-group-item-action">
            ' . $setor . '
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
  <nav class="navbar navbar-expand-md navbar-dark bg-dark barra">
    <a class="navbar-brand" href="#">RH Plus</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Alterna navegação">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-item nav-link active" href="index.php">Início <span class="sr-only">(Página atual)</span></a>
        <a class="nav-item nav-link" disabled href="myreserve.php">Minhas Reservas</a>
        <a class="nav-item nav-link" href="#">Realizar Reservas</a>
      </div>
    </div>
  </nav>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">Setores</li>
    </ol>
  </nav>
  <div class="setores">
    <p class="lead"></p>
    <div class="alert alert-info" role="alert">
      Bem-Vindo, <b><?php echo getUserName(); ?></b>!
      <hr>
      Nível: <b><?php echo getOffice(); ?></b>
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