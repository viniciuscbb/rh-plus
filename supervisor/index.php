<?php
require_once '../db/config.php';

function getUserName()
{
  session_start();
  $id_subscribe = $_SESSION['id_subscribe'];
  $conection = conection();
  $sql = "SELECT nome FROM adm WHERE id_adm='$id_subscribe'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $nome = $row['nome'];
  return $nome;
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
  <form method="post">
    <div class="form-row ">
      <div class="search">
        <input class="form-control" type="search" placeholder="Filtrar por nome" name="nomePesquisa">
        <button type="submit" name="pesquisar" class="btn btn-outline-success">Pesquisar</button>
      </div>
    </div>
  </form>



  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
</body>

</html>