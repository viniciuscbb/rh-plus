<?php
require_once '../db/config.php';
require_once '../functions/checkLogin.php';
require_once '../functions/global.php';

acessoRestrito(3);

$mensagem = '<div class="alert alert-info" role="alert">
              <h6>Abaixo estão as reservas pendentes.</h6>
            </div>';


if (isset($_GET["msg"])) {
  $msg = $_GET["msg"];
  $id_reserva = $_GET["id"];
  switch ($msg) {
    case 1:
      $mensagem = '<div class="alert alert-success" role="alert">
                    <h6>Reserva do setor ' . getReserve($id_reserva) . ' aceita com sucesso!</h6>
                   </div>';
      break;
    case 2:
      $mensagem = '<div class="alert alert-danger" role="alert">
                    <h6>Erro ao aceitar reserva do setor ' . getReserve($id_reserva) . '!</h6>
                   </div>';
      break;
    case 3:
      $mensagem = '<div class="alert alert-success" role="alert">
                    <h6>Reserva do setor ' . getReserve($id_reserva) . ' negada com sucesso!</h6>
                   </div>';
      break;
    case 4:
      $mensagem = '<div class="alert alert-danger" role="alert">
                    <h6>Erro ao negar reserva do setor ' . getReserve($id_reserva) . '!</h6>
                   </div>';
      break;
    case 5:
      $mensagem = '<div class="alert alert-success" role="alert">
                    <h6>Pedido de reanálise do setor ' . getReserve($id_reserva) . ' enviada com sucesso!</h6>
                   </div>';
      break;
    case 6:
      $mensagem = '<div class="alert alert-danger" role="alert">
                    <h6>Erro ao enviar pedido de reanálise do setor ' . getReserve($id_reserva) . '!</h6>
                   </div>';
      break;
  }
}

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

function getNameSupervisor($id_adm)
{
  $conection = conection();
  $sql = "SELECT nome FROM adm WHERE id_adm = '$id_adm'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $name = $row['nome'];
  return $name;
}

function getSector($id_setor)
{
  $conection = conection();
  $sql = "SELECT setor FROM setores WHERE id_setor = '$id_setor'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $setor = $row['setor'];
  return $setor;
}

function getReserve($id_reserva)
{
  $conection = conection();
  $sql = "SELECT id_setor FROM reservas WHERE id_reserva = '$id_reserva'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $setor = getSector($row['id_setor']);
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

function getTransport($id_reserva)
{
  $conection = conection();
  $sql = "SELECT transporte FROM reservas WHERE id_reserva = '$id_reserva'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $transporte = $row['transporte'];
  return $transporte;
}

function getFood($id_reserva)
{
  $conection = conection();
  $sql = "SELECT alimentacao FROM reservas WHERE id_reserva = '$id_reserva'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $alimentacao = $row['alimentacao'];
  return $alimentacao;
}

function getHours($id_reserva)
{
  $conection = conection();
  $sql = "SELECT horas FROM reservas WHERE id_reserva = '$id_reserva'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $horas = $row['horas'];
  return $horas;
}

//Seleciona a id_setor do coordenador logado
function getMySector()
{
  $id_login = $_SESSION['id_login'];
  $conection = conection();
  $sql = "SELECT id_setor FROM adm WHERE id_adm = '$id_login'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $id_setor = $row['id_setor'];
  return $id_setor;
}

/*Status reserva
1 = Pendente para Diretor
2 = Pendente para Coordenador
3 = Aprovado
4 = Refazer
5 = Negado
*/

function getMotive($id_reserva)
{
  $conection = conection();
  $sql = "SELECT motivo FROM reservas WHERE id_reserva = '$id_reserva'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $motivo = $row['motivo'];
  return $motivo;
}

function setTurno($turno)
{
  if ($turno == 0) {
    return 'Matutino';
  } else {
    return 'Noturno';
  }
}


function showReservers()
{
  $conection = conection();
  $sql = "SELECT * FROM reservas WHERE status = 2";
  $query = mysqli_query($conection, $sql);
  $total = mysqli_num_rows($query);

  if ($total > 0) {
    $contador = 0;
    while ($row = mysqli_fetch_array($query)) {
      $id_reserva = $row['id_reserva'];
      $supervisor = getNameSupervisor($row['id_supervisor']);
      $setor = getSector($row['id_setor']);
      date_default_timezone_set('America/Sao_Paulo');

      $data =  $row['data'];
      $turno =  setTurno($row['turno']);
      $data =  date("d/m/Y", strtotime($data));
      $valor = $row['valor'];
      $valor = str_replace(',', '.', $valor);
      $valor = number_format($valor, 2, ',', '.');

      $totalTransporte = getTransport($id_reserva);
      $totalTransporte = str_replace(',', '.', $totalTransporte);
      $totalTransporte = number_format($totalTransporte, 2, ',', '.');

      $totalAlimentacao = getFood($id_reserva);
      $totalAlimentacao = str_replace(',', '.', $totalAlimentacao);
      $totalAlimentacao = number_format($totalAlimentacao, 2, ',', '.');

      $totalHoras = getHours($id_reserva);
      $totalHoras = str_replace(',', '.', $totalHoras);
      $totalHoras = number_format($totalHoras, 2, ',', '.');

      $totalColaborador = getCount($id_reserva);

      $motivo = getMotive($id_reserva);

      $colapso = 'true';
      $colapsed = '';
      $show = 'show';
      if ($contador > 0) {
        $colapso = 'false';
        $colapsed = 'collapsed';
        $show = '';
      }
      $teste = "teste" . $contador;

      echo '
      <div class="card">
      <div class="card-header" id="a' . $teste . '">
        <h5 class="mb-0">
          <button class="btn btn-link ' . $colapsed . '" type="button" data-toggle="collapse" data-target="#' . $teste . '" aria-expanded="' . $colapso . '" aria-controls="' . $teste . '">
            ' . $setor . '
          </button>
        </h5>
      </div>
  
      <div id="' . $teste . '" class="collapse ' . $show . '" aria-labelledby="a' . $teste . '" data-parent="#accordionExample">
        <div class="card-body">
          <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <h5>
                Solicitante
              </h5>
              <h5>
                <span class="badge badge-primary badge-pill" id="totalValor">
                  ' . $supervisor . '
                </span>
              </h5>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <h5>
                Data
              </h5>
              <h5>
                <span class="badge badge-primary badge-pill" id="totalValor">
                  ' . $data . '
                </span>
              </h5>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <h5>
                Turno
              </h5>
              <h5>
                <span class="badge badge-primary badge-pill" id="totalValor">
                  ' . $turno . '
                </span>
              </h5>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <h5>
                Colaboradores
              </h5>
              <h5>
                <span class="badge badge-primary badge-pill" id="totalColaboradores">
                  ' . $totalColaborador . '
                </span>
              </h5>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <h5>
                Transporte
              </h5>
              <h5>
                <span class="badge badge-primary badge-pill" id="totalColaboradores">
                  R$ ' . $totalTransporte . '
                </span>
              </h5>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <h5>
                Alimentação
              </h5>
              <h5>
                <span class="badge badge-primary badge-pill" id="totalColaboradores">
                  R$ ' . $totalAlimentacao . '
                </span>
              </h5>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <h5>
                Horas Extras
              </h5>
              <h5>
                <span class="badge badge-primary badge-pill" id="totalColaboradores">
                  R$ ' . $totalHoras . '
                </span>
              </h5>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <h5>
                Valor total
              </h5>
              <h5>
                <span class="badge badge-primary badge-pill" id="totalValor">
                  R$ ' . $valor . '
                </span>
              </h5>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div class="form-group">
                <h5>Motivo</h5>
                <div class="alert alert-dark" role="alert">
                  <p class="text-justify">' . $motivo . '</p>
                </div>
              </div>
            </li>
          </ul>
          <form action="send.php" method="post">
            <div class="botoes">
              <input name="idReserva" type="hidden" value=' . $id_reserva . '>
              <button type="submit" name="accept" class="btn btn-success">Aceitar</button>
              <button type="button" onclick="change(' . $id_reserva . ')" id="btnRemake" data-toggle="modal" data-target="#modalExemplo" class="btn btn-warning">Refazer</button>
              <button type="submit" name="deny" class="btn btn-danger">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>';
      $contador++;
    }
  } else {
    echo '
    <div class="alert alert-warning" role="alert">
      <h6>Nenhuma reserva disponível para análise.</h6>
    </div>';
  }
}
?>

<!DOCTYPE html>
<html lang="pt_BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/adm.css">
  <title>Coordenador - RH Plus</title>
</head>

<body>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark barra">
    <a class="navbar-brand" href="index.php">RH Plus</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Alterna navegação">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-item nav-link active" href="index.php">Início <span class="sr-only">(Página atual)</span></a>
      </div>
    </div>
  </nav>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">Reservas</li>
    </ol>
  </nav>
  <div class="setores">
    <p class="lead"></p>
    <div class="alert alert-info" role="alert">
      Bem-Vindo, <b><?php echo getUserName(); ?></b>!
      <hr>
      Nível: <b><?php echo getOffice(); ?></b>
    </div>

    <?php echo $mensagem; ?>

    <div class="accordion" id="accordionExample">
      <?php echo showReservers(); ?>

    </div>
  </div>


  <!-- Modal -->
  <div class="modal fade" id="modalExemplo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="send.php" method="post">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Confirme a reanálise</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="alert alert-info" role="alert">
              Digite abaixo o motivo da reanálise.
            </div>
            <div class="form-group area">
              <h5>Motivo</h5>
              <textarea class="form-control" name="inputRemake" rows="3" minlength="10" maxlength="200" placeholder="Descreva o motivo da reanálise" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <input name="inputIdReserva" id="inputIdReserva" type="hidden">
            <button name="remake" type="submit" class="btn btn-success">Refazer</a>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/adm.js"></script>
</body>

</html>