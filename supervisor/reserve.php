<?php
require_once '../db/config.php';
require_once '../functions/checkLogin.php';
require_once '../functions/global.php';

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

function getDatee()
{
  $id_reserva = htmlspecialchars($_GET["reserve"]);
  $conection = conection();
  $sql = "SELECT data FROM reservas WHERE id_reserva='$id_reserva'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $date = $row['data'];
  $date =  date("Y/m/d", strtotime($date));
  return $date;
}

//Pega os feriados
function getHoliday($date)
{
  $conection = conection();
  $sql = "SELECT data FROM feriados WHERE data='$date'";
  $query = mysqli_query($conection, $sql);
  $total = mysqli_num_rows($query);

  if ($total > 0) {
    return true;
  } else {
    return false;
  }
}

//Compara a data da reserva com o feriado
function getTodayHoliday()
{
  $id_reserva = htmlspecialchars($_GET["reserve"]);
  $conection = conection();
  $sql = "SELECT data FROM reservas WHERE id_reserva='$id_reserva'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $date = $row['data'];

  if (getHoliday($date)) {
    return 'true';
  } else {
    return 'false';
  }
}

if (isset($_POST['Delete'])) {
  $id_colaborador = $_POST['id_colaborador'];
  $conection = conection();

  $result = mysqli_query($conection, "DELETE FROM lista WHERE id_colaborador = '$id_colaborador'");
  if (!$result) {
    echo "Error";
  }
}

function sumHourOfMonth($id_colaborador)
{
  date_default_timezone_set('America/Sao_Paulo');
  $mes  = date('m');
  $conection = conection();
  $sql = "SELECT 
            sum(L.hora) as total
          FROM 
            lista as L
          INNER JOIN
            reservas as R ON R.id_reserva = L.id_reserva
          WHERE
            MONTH(R.data) = '$mes' AND L.id_colaborador = '$id_colaborador'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $total = $row['total'];

  if ($total == '') {
    $total = 0;
  }
  return $total;
}

function showCollaborator()
{
  $id_reserva = htmlspecialchars($_GET["reserve"]);
  $id_sector = htmlspecialchars($_GET["sector"]);

  $conection = conection();
  $sql = "SELECT 
            C.id_colaborador, C.nome, RT.rota, C.hora, C.id_rota, RT.valor
          FROM 
            reservas as R 
          INNER JOIN 
            lista as L ON R.id_reserva = L.id_reserva 
          INNER JOIN 
            colaboradores as C ON L.id_colaborador = C.id_colaborador 
          INNER JOIN
            rotas as RT ON C.id_rota = RT.id_rota
          WHERE R.id_reserva = '$id_reserva' AND R.id_setor = '$id_sector' AND R.status = 0 OR R.status = 4
          ORDER BY
            nome";

  $query = mysqli_query($conection, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $id_colaborador = $row['id_colaborador'];
    $nome           = $row['nome'];
    $rotaC          = $row['rota'];
    $hora           = $row['hora'];
    $rota           = $row['id_rota'];
    $valorRota      = $row['valor'];
    $horasMesTotal  = sumHourOfMonth($id_colaborador);

    echo '
    <tr style="font-size: 12px">
      <td class="botaoClass">
        <form method="post">
          <input type="hidden" name="id_colaborador" value=' . $id_colaborador . '> 
          <button name="Delete" class="btn btn-danger btn-sm deleteBtn" type="submit" title="Remover o colaborador da lista"><img src="../img/delete.png" alt="Delete"></button>
        </form>
      </td>
      <td><b>' . $nome . '</b></td>
      <td>' . $rotaC . '</td>
      <td>
        <div class="form-check">
          <input id="' . $id_colaborador . '" value="' . $id_colaborador . '" class="form-check-input position-static checkbox transporte" type="checkbox" checked  onchange="teste()">
          <input value=' . $rota . ' class="rota" type="hidden">
          <input value=' . $valorRota . ' class="valorRota" type="hidden">
        </div>
      </td>
      <td>
        <div class="form-check">
          <input id="' . $id_colaborador . '" value="' . $id_colaborador . '" class="form-check-input position-static checkbox alimentacao" type="checkbox" onchange="teste()">
        </div>
      </td>
      <td>
        <input id="' . $id_colaborador . '" class="form-control input-number horas" type="number" value="0" onchange="teste()">
        <input class="valorHora" type="hidden" value=' . $hora . '>
        <input class="horasMesTotal" type="hidden" value=' . $horasMesTotal . '>
      </td>
    </tr>';
  }
}

function insertReserve($id_colaborador, $transporte, $alimentacao, $horas, $id_reserva)
{
  $conection = conection();

  $sql = "UPDATE 
            lista 
          SET 
            transporte = '$transporte', 
            alimentacao = '$alimentacao', 
            hora = '$horas' 
          WHERE 
            id_colaborador = '$id_colaborador' AND id_reserva = '$id_reserva'";
  $query = mysqli_query($conection, $sql);

  if ($query) {
    return true;
  } else {
    return false;
  }
}

//Verifica se vai pro coordenador ou diretor
function verifySector()
{
  $id_sector = htmlspecialchars($_GET["sector"]);
  $conection = conection();
  $sql = "SELECT * FROM adm WHERE id_setor='$id_sector' AND nivel = 3";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);

  $total = mysqli_num_rows($query);

  if ($total > 0) {
    return 2;
  } else {
    return 1;
  }
}

//Clica no botao Confirmar
if (isset($_POST['createReserve'])) {
  $total = ($_POST['total']);
  $valorGeral = ($_POST['valorGeral']);
  $valorTransporte = ($_POST['valorTransporte']);
  $valorAlimentacao = ($_POST['valorAlimentacao']);
  $valorHoras = ($_POST['valorHoras']);
  $id_reserva = htmlspecialchars($_GET["reserve"]);
  $arrayOne = explode(';', $total);

  $conection = conection();
  $status = verifySector();

  $sql = "UPDATE 
            reservas 
          SET 
            valor = '$valorGeral', 
            transporte = '$valorTransporte', 
            alimentacao = '$valorAlimentacao', 
            horas = '$valorHoras', 
            status = '$status', 
            refazer = null
          WHERE 
            id_reserva = '$id_reserva'";
  $query = mysqli_query($conection, $sql);

  $c = 0;
  foreach ($arrayOne as $numero) {
    $teste = explode("-", $arrayOne[$c]);
    insertReserve($teste[0], $teste[1], $teste[2], $teste[3], $id_reserva);
    $c++;
  }
  if ($query) {
    header("location: myreserve.php");
  }
}

//cancela a reserva
if (isset($_POST['delete'])) {
  $id_sector = htmlspecialchars($_GET["sector"]);
  $id_reserva = htmlspecialchars($_GET["reserve"]);
  $conection = conection();

  $result = mysqli_query($conection, "DELETE FROM lista WHERE id_reserva = '$id_reserva'");
  if (!$result) {
    echo "Error";
  }

  $result = mysqli_query($conection, "DELETE FROM reservas WHERE id_reserva = '$id_reserva'");
  if (!$result) {
    echo "Error";
  } else {
    echo '<script> alert("Reserva cancelada com sucesso!"); window.location = "sector.php?sector=' . $id_sector . '"</script>';
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
      <li class="breadcrumb-item"><a href="sector.php?sector=<?php echo htmlspecialchars($_GET["sector"]); ?>">Colaboradores</a></li>
      <li class="breadcrumb-item active" aria-current="page">Confirmar</li>
    </ol>
  </nav>

  <div class="setores">
    <div class="alert alert-info" role="alert">
      Informe Transporte, Alimentação e Horas Extras para cada colaborador.
      <hr>
      Clique no nome da coluna para selecionar os correpondentes da linha.
    </div>
  </div>

  <table class="table table-striped tabela">
    <thead>
      <tr>
        <th scope="col"></th>
        <th scope="col">Nome</th>
        <th scope="col">Rota</th>
        <th scope="col" onclick="selectTransport()">Transp.</th>
        <th scope="col" onclick="selectFood()">Aliment.</th>
        <th scope="col">H. Extras</th>
      </tr>
    </thead>
    <tbody>
      <?php echo showCollaborator(); ?>
    </tbody>
  </table>
  <div class="valor">
    <ul class="list-group">
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <h3>Valor total </h3>
        <h3><span class="badge badge-primary" id="valorTela">R$ 0,00</span></h3>
      </li>
    </ul>
  </div>
  <div class="button">
    <button name="createReserve" id="nextBtn" type="button" class="btn btn-primary" disabled data-toggle="modal" data-target="#modalExemplo">Criar Reserva</button>
    <form method="post">
      <button name="delete" type="submit" class="btn btn-danger">Cancelar Reserva</button>
    </form>
  </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="modalExemplo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Confirme a reserva</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post">
          <div class="modal-body">
            <div class="alert alert-info" role="alert">
              Confira os dados da reserva e confirme.
            </div>
            <ul class="list-group">
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <h5>
                  Total de colaboradores
                </h5>
                <h4>
                  <span class="badge badge-primary badge-pill" id="totalColaboradores">
                    0
                  </span>
                </h4>
              </li>

              <li class="list-group-item d-flex justify-content-between align-items-center">
                <h5>
                  Valor total
                </h5>
                <h4>
                  <span class="badge badge-primary badge-pill" id="totalValor">
                    R$ 0,00
                  </span>
                </h4>
              </li>
            </ul>
          </div>
          <div class="modal-footer">
            <button name="createReserve" type="submit" class="btn btn-success">Confirmar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>

            <input id="total" name="total" type="hidden">
            <input id="valorGeral" name="valorGeral" type="hidden">
            <input id="valorTransporte" name="valorTransporte" type="hidden">
            <input id="valorAlimentacao" name="valorAlimentacao" type="hidden">
            <input id="valorHoras" name="valorHoras" type="hidden">
            <input id="feriado" value=<?php echo getTodayHoliday(); ?> type="hidden">
            <input id="data" value=<?php echo getDatee(); ?> type="hidden">

          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/reserve.js"></script>
</body>

</html>