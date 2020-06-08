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

    /*Status reserva
    1 = Pendente para Diretor
    2 = Pendente para Coordenador
    3 = Aprovado
    4 = Refazer
    5 = Negado
    */

    switch ($status) {
      case 1:
        $status = '<span class="badge badge-primary badge-pill">Em análise</span>';
        break;
      case 2:
        $status = '<span class="badge badge-primary badge-pill">Em análise</span>';
        break;
      case 3:
        $status = '<span class="badge badge-success badge-pill">Aprovado</span>';
        break;
      case 4:
        $status = '<a href="reserve.php?reserve='.$id_reserva.'&sector='.$row['id_setor'].'" class="badge badge-warning">Reanálise<p>Clique para refazer</p></a>';
        break;
      case 5:
        $status = '<span class="badge badge-danger badge-pill">Negado</span>';
        break;
      default:
        $status = '<span class="badge badge-danger badge-pill">Erro</span>';
    }

    date_default_timezone_set('America/Sao_Paulo');
    $today = date("Y-m-d");
    if (strtotime($data) >= strtotime($today)) {
      $data =  date("d/m/Y", strtotime($data));
      echo '
      <tr>
        <th>
          ' . $id_reserva . '
        </th>
        <td><b>' . $setor . '</b></td>
        
        <td>
          <h5>
            <span class="badge badge-primary badge-pill">
              ' . $data . '
            </span>
          </h5>
        </td>
        <td><h5>' . $status . '</h5></td>
        
      </tr>';
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
    <a class="navbar-brand" href="#">RH Plus</a>
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
      Abaixo estão suas reservas realizadas.
    </div>

    <table class="table table-striped tabela">
      <thead>
        <tr>
          <th scope="col">Cod.</th>
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


  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/sector.js"></script>
</body>

</html>