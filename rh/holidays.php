<?php
require_once '../db/config.php';
require_once '../functions/checkLogin.php';
require_once '../functions/global.php';

acessoRestrito(2);

function getSector($id_setor)
{
  $conection = conection();
  $sql = "SELECT setor FROM setores WHERE id_setor = '$id_setor'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $setor = $row['setor'];
  return $setor;
}

function getCount($id_reserva)
{
  $conection = conection();
  $sql = "SELECT count(id_colaborador) as conta FROM lista WHERE id_reserva = '$id_reserva'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $conta = $row['conta'];
  return $conta;
}

function getFood($id_reserva)
{
  $conection = conection();
  $sql = "SELECT count(alimentacao) as a FROM lista WHERE id_reserva = '$id_reserva' AND alimentacao = 1";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $alimentacao = $row['a'];
  return $alimentacao;
}

/*Status reserva
1 = Pendente para Diretor
2 = Pendente para Coordenador
3 = Aprovado
4 = Refazer
5 = Negado
*/

function getTypee($type)
{
  switch ($type) {
    case 1:
      return 'Feriado Nacional';
      break;
    case 2:
      return 'Feriado Estadual';
      break;
    case 3:
      return 'Feriado Municipal';
      break;
    case 4:
      return 'Facultativo';
      break;
  }
}

function showHolidays()
{
  $conection = conection();
  $sql = "SELECT * FROM feriados";
  $query = mysqli_query($conection, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $id_feriado = $row['id_feriado'];
    $name       = $row['nome'];
    $date       = $row['data'];
    $date =  date("d/m/Y", strtotime($date));
    $type       = getTypee($row['tipo']);
    echo '
    <tr>
      <td class="botaoClass">
        <form method="post">
          <input type="hidden" name="idFeriado" value=' . $id_feriado . '> 
          <button name="Delete" class="btn btn-danger btn-sm deleteBtn" type="submit" title="Remover o feriado"><img src="../img/delete.png" alt="Delete"></button>
        </form>
      </td>
      <td><b>' . $name . '</b></td>
      <td><h5><span class="badge badge-primary badge-pill">' . $date . '</span></h5></td>
      <td><h5><span class="badge badge-primary badge-pill">' . $type . '</span></h5></td>
    </tr>';
  }
}

$mensagem = '';

if (isset($_GET["msg"])) {
  $msg = $_GET["msg"];
  switch ($msg) {
    case 1:
      $mensagem = '<div class="alert alert-danger" role="alert">
                    <h6>Selecione o tipo do feriado</h6>
                   </div>';
      break;
    case 2:
      $mensagem = '<div class="alert alert-success" role="alert">
                    <h6>Feriado adicionado com sucesso!</h6>
                   </div>';
      break;
    case 3:
      $mensagem = '<div class="alert alert-danger" role="alert">
                    <h6>Erro ao criar feriado!</h6>
                   </div>';
      break;
  }
}

//Apaga o feriado
if (isset($_POST['Delete'])) {
  $id_feriado = $_POST['idFeriado'];
  $conection = conection();

  $result = mysqli_query($conection, "DELETE FROM feriados WHERE id_feriado = '$id_feriado'");
  if (!$result) {
    echo "Erro ao deletar feriado";
  }
}

?>


<!DOCTYPE html>
<html lang="pt_BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/rh.css">
  <title>Recursos Humanos - RH Plus</title>
</head>

<body>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark barra">
    <a class="navbar-brand" href="index.php">RH Plus</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Alterna navegação">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-item nav-link" href="index.php">Início </a>
        <a class="nav-item nav-link active" href="#">Feriados<span class="sr-only">(Página atual)</span></a>
      </div>
    </div>
  </nav>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php">Reservas</a></li>
      <li class="breadcrumb-item active" aria-current="page">Feriados</li>
    </ol>
  </nav>
  <div class="setores">
    <p class="lead"></p>
    <div class="alert alert-info" role="alert">
      <h6>Preencha abaixo para adicionar novos feriados.</h6>
    </div>

    <?php echo $mensagem; ?>

    <div class="holidays">
      <form action="send.php" method="post">
        <div class="form-row">
          <div class="col">
            <input type="text" class="form-control" name="name" placeholder="Nome" required>
          </div>
          <div class="col">
            <input type="date" class="form-control" name="date" placeholder="Data" required>
          </div>
          <div class="col-2">
            <select class="custom-select" name="type" required>
              <option selected>Escolher...</option>
              <option value="1">Feriado Nacional</option>
              <option value="2">Feriado Estadual</option>
              <option value="3">Feriado Municipal</option>
              <option value="4">Facultativo</option>
            </select>
          </div>
          <button type="submit" name="save" class="btn btn-outline-success">Salvar</button>
        </div>
      </form>
    </div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">Ac</th>
          <th scope="col">Nome</th>
          <th scope="col">Data</th>
          <th scope="col">Tipo</th>
        </tr>
      </thead>
      <tbody>
        <?php echo showHolidays(); ?>
      </tbody>
    </table>
  </div>


  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
</body>

</html>