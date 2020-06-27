<?php
require_once '../db/config.php';
require_once '../functions/checkLogin.php';
require_once '../functions/global.php';
$erro = "";

acessoRestrito(1);

function getSector($id_setor)
{
  $conection = conection();
  $sql = "SELECT setor FROM setores WHERE id_setor = '$id_setor'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $setor = $row['setor'];
  return $setor;
}

function showMyReserve()
{
  $id_login = $_SESSION['id_login'];

  $conection = conection();
  $sql = "SELECT * FROM reservas WHERE id_supervisor = '$id_login'";
  $query = mysqli_query($conection, $sql);

  while ($row = mysqli_fetch_array($query)) {
    $id_reserva = $row['id_reserva'];
    $setor      = getSector($row['id_setor']);
    $data       = $row['data'];
    $status     = $row['status'];
    $refazer     = $row['refazer'];
    $cancelar = '';
    $id_setor = $row['id_setor'];

    /*Status reserva
    1 = Pendente para Diretor
    2 = Pendente para Coordenador
    3 = Aprovado
    4 = Refazer
    5 = Negado
    */

    switch ($status) {
      case 1:
        $status = '<form method="post" class="dois">
                    <span class="badge badge-primary badge-pill">Em análise</span>
                    <input type="hidden" name="idReserva" value=' . $id_reserva . '> 
                      <button name="Cancel" onclick="trocar(' . $id_reserva . $id_setor . ')" class="btn btn-danger btn-sm deleteBtn2" type="submit" title="Cancelar esta reserva">Cancelar</button>
                   </form>';
        break;
      case 2:
        $status = '<form method="post" class="dois">
                    <span class="badge badge-primary badge-pill">Em análise</span>
                      <input type="hidden" name="idReserva" value=' . $id_reserva . '> 
                      <button name="Cancel" class="btn btn-danger btn-sm deleteBtn2" type="submit" title="Cancelar esta reserva">Cancelar</button>
                   </form>';

        break;
      case 3:
        $status = '<span class="badge badge-success badge-pill">Aprovado</span>';
        break;
      case 4:
        $status = '<button value="' . $refazer . '" onclick="change(' . $id_reserva . ', ' . $id_setor . ', this.value)" class="btn btn-warning btn-sm" type="button" data-toggle="modal" data-target="#modalExemplo">Reanálise</button>
                  ';
        break;
      case 5:
        $status = '<span class="badge badge-danger badge-pill">Cancelado</span>';
        break;
      default:
        $status = '<span class="badge badge-danger badge-pill">Erro</span>';
    }

    date_default_timezone_set('America/Sao_Paulo');
    $today = date("Y-m");
    if (strtotime($data) >= strtotime($today)) {
      $data =  date("d/m/Y", strtotime($data));
      echo '
      <tr>
        <th>
          ' . $setor . '
          <input type="hidden" id="idReserva" value=' . $id_reserva . '> 
        </th>
        <td>
          <h6>
            <span class="badge badge-primary badge-pill">
              ' . $data . '
            </span>
          </h6>
        </td>
        <td><h6>' . $status . '</h6></td>
      </tr>';
    }
  }
  //cancela a reserva
  if (isset($_POST['Cancel'])) {
    $id_reserva = ($_POST['idReserva']);
    $conection = conection();
    $sql = "UPDATE 
            reservas 
          SET 
            status = 5
          WHERE 
            id_reserva = '$id_reserva'";

    $result = mysqli_query($conection, $sql);
    if (!$result) {
      echo "Error";
    } else {
      header("Refresh:0");
    }
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
        <a class="nav-item nav-link active" disabled href="#">Minhas Reservas<span class="sr-only">(Página atual)</span></a>
        <a class="nav-item nav-link" href="#">Realizar Reservas</a>
      </div>
    </div>
  </nav>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Setores</a></li>
      <li class="breadcrumb-item active" aria-current="page">Minhas Reservas</li>
    </ol>
  </nav>

  <div class="setores">
    <div class="alert alert-info" role="alert">
      Abaixo estão suas reservas realizadas no mês.
    </div>

    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">Setor</th>
          <th scope="col">Data</th>
          <th scope="col">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php echo showMyReserve(); ?>
      </tbody>
    </table>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="modalExemplo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Confirme a reanálise</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="alert alert-warning" role="alert">
            Abaixo está o motivo da reanálise.
          </div>
          <div class="form-group area">
            <h5>Motivo</h5>
            <textarea id="inputRenew" class="form-control" name="motive" rows="3" minlength="10" maxlength="200" disabled></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <a href="#" id="renewReserve" type="button" class="btn btn-success">Refazer</a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/sector.js"></script>
</body>

</html>