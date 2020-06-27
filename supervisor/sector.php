<?php
require_once '../db/config.php';
require_once '../functions/checkLogin.php';
require_once '../functions/global.php';
$erro = "";

acessoRestrito(1);

function selectedSector()
{
  $id_sector = htmlspecialchars($_GET["sector"]);
  $conection = conection();
  $sql = "SELECT setor FROM setores WHERE id_setor='$id_sector'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $setor = $row['setor'];
  return $setor;
}

function selectRoute($id_rota)
{
  $conection = conection();
  $sql = "SELECT rota FROM rotas WHERE id_rota = '$id_rota '";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $rota = $row['rota'];
  return $rota;
}

function showCollaborator()
{
  $id_sector = htmlspecialchars($_GET["sector"]);
  $conection = conection();
  $sql = "SELECT * FROM colaboradores WHERE id_setor = '$id_sector' ORDER BY nome";
  $query = mysqli_query($conection, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $id_colaborador = $row['id_colaborador'];
    $nome           = $row['nome'];
    $cracha         = $row['cracha'];
    $rota           = selectRoute($row['id_rota']);
    $hora           = $row['hora'];
    echo '
    <tr>
      <th>
        <div class="form-check">
          <input id="' . $id_colaborador . '" value="' . $id_colaborador . '" class="form-check-input position-static checkbox" type="checkbox">
        </div>
      </th>
      <td>' . $cracha . '</td>
      <td><b>' . $nome . '</b></td>
      <td>' . $rota . '</td>
      <td style="display: none" id="' . $id_colaborador . '">' . $hora . '</td>
    </tr>';
  }
}

function getIdReserve()
{
  $id_login = $_SESSION['id_login'];

  $conection = conection();
  $sql = "SELECT id_reserva FROM reservas where id_supervisor = '$id_login' and status = 0";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $id_reserva = $row['id_reserva'];
  return $id_reserva;
}

//Insere na tabela lista
function insertList($id_colaborador)
{
  $conection = conection();
  $sql = "SELECT * FROM colaboradores where id_colaborador = '$id_colaborador'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $id_colaborador = $row['id_colaborador'];

  $id_reserve = getIdReserve();

  $sql = "INSERT INTO lista (id_reserva, id_colaborador) VALUES ('$id_reserve', '$id_colaborador')";
  $query = mysqli_query($conection, $sql);

  if ($query) {
    return true;
  } else {
    return false;
  }
}

//Insere na tabela reserva
function createReserve($turno, $date, $motive)
{
  $id_login = $_SESSION['id_login'];
  $id_sector = htmlspecialchars($_GET["sector"]);

  $conection = conection();
  $sql = "INSERT INTO 
            reservas 
            (id_supervisor, 
            id_setor, 
            valor, 
            status, 
            turno,
            data,
            motivo) 
          VALUES 
            ('$id_login', 
            '$id_sector', 
            0, 
            0,
            '$turno',
            '$date',
            '$motive')";
  $query = mysqli_query($conection, $sql);

  if ($query) {
    return true;
  } else {
    return false;
  }
}

//Caso botao Prosseguir seja selecionado
if (isset($_POST['next'])) {
  $nextPage = 0;
  //Pega os dados do input e separa
  $date = ($_POST['data']);
  $turno = ($_POST['turno']);
  $motive = ($_POST['motive']);
  $idSelected = ($_POST['idSelected']);
  $array = explode('-', $idSelected);

  //Cria a reserva
  createReserve($turno, $date, $motive);

  //Envia os dados separados para o banco
  foreach ($array as $values) {
    if (insertList($values)) {
      $nextPage = $nextPage + 1;
    }
  }

  if ($nextPage > 0) {
    $id_sector = htmlspecialchars($_GET["sector"]);
    header("location: reserve.php?reserve=" . getIdReserve() . "&sector=" . $id_sector);
  } else {
    $erro = "Erro ao prosseguir com a reserva!";
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
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark barra">
    <a class="navbar-brand" href="index.php">RH Plus</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Alterna navegação">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-item nav-link" href="index.php">Início</a>
        <a class="nav-item nav-link" disabled href="myreserve.php">Minhas Reservas</a>
        <a class="nav-item nav-link active" href="#">Realizar Reservas <span class="sr-only">(Página atual)</span></a>
      </div>
    </div>
  </nav>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Setores</a></li>
      <li class="breadcrumb-item active" aria-current="page">Colaboradores</li>
    </ol>
  </nav>

  <div class="setores">
    <div class="alert alert-info" role="alert">
      Setor selecionado: <b><?php echo selectedSector(); ?>.</b>
      <hr>
      Selecione os colaboradores desejados e clique em prosseguir.
    </div>

    <?php
    if (!$erro == "") {
      echo "<div class='alert alert-danger alerta-sm' role='alert'>";
      echo $erro;
      echo "</div>";
    }
    ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">Opc</th>
          <th scope="col">Matrícula</th>
          <th scope="col">Nome</th>
          <th scope="col">Rota</th>
        </tr>
      </thead>
      <tbody>
        <?php echo showCollaborator(); ?>
      </tbody>
    </table>
    <div class="button">
      <button id="save" type="button" class="btn btn-info" data-toggle="modal" data-target="#modalExemplo">Prosseguir</button>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="modalExemplo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Data da reserva</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post">
          <div class="modal-body">
            <div class="alert alert-info" role="alert">
              Informe data, turno e motivo da reserva.
            </div>
            <ul class="list-group">
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <h5>
                  Data
                </h5>
                <input type="date" class="form-control data" name="data" placeholder="Data" required>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="form-group area">
                  <h5>
                    Turno
                  </h5>
                  <select class="form-control" name="turno" required>
                    <option value="0">Diurno</option>
                    <option value="1">Noturno</option>
                  </select>
                </div>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="form-group area">
                  <h5>Motivo</h5>
                  <textarea class="form-control" name="motive" rows="3" minlength="10" maxlength="200" placeholder="Descreva o motivo desta reserva" required></textarea>
                </div>
              </li>
            </ul>
          </div>
          <div class="modal-footer">
            <form method="post">
              <input id="total" name="idSelected" type="hidden">
              <button id="nextBtn" name="next" type="submit" class="btn btn-success">Confirmar</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </form>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/sector.js"></script>
</body>

</html>