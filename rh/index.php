<?php
require_once '../db/config.php';
require_once '../functions/checkLogin.php';
require_once '../functions/global.php';

acessoRestrito(2);

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

function showReservers()
{
  $conection = conection();
  $sql = "SELECT * FROM reservas WHERE status = 3 ORDER BY data";
  $query = mysqli_query($conection, $sql);
  $total = mysqli_num_rows($query);

  if ($total > 0) {
    $contador = 0;
    while ($row = mysqli_fetch_array($query)) {
      $id_reserva = $row['id_reserva'];
      $setor = getSector($row['id_setor']);
      date_default_timezone_set('America/Sao_Paulo');

      $data =  $row['data'];

      $today = date("Y-m-d");
      if (strtotime($data) >= strtotime($today)) {
        $data =  date("d/m/Y", strtotime($data));

        $totalAlimentacao = getFood($id_reserva);
        $totalColaborador = getCount($id_reserva);

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
                      Data
                    </h5>
                    <h5>
                      <span class="badge badge-primary badge-pill">
                        ' . $data . '
                      </span>
                    </h5>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <h5>
                      Rotas
                    </h5>
                    <a class="btn btn-info btn-sm" href="routes.php?route=' . $id_reserva . '" role="button">Ver</a>
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
                      Alimentação
                    </h5>
                    <h5>
                      <span class="badge badge-primary badge-pill" id="totalColaboradores">
                        ' . $totalAlimentacao . '
                      </span>
                    </h5>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <h5>
                      Horas Extras
                    </h5>
                    <a class="btn btn-info btn-sm" href="hours.php?hours=' . $id_reserva . '" role="button">Ver</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>';
        $contador++;
      }
    }
  } else {
    return '<div class="alert alert-warning" role="alert">
            <h6>Nenhuma reserva disponível.</h6>
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
  <title>Recursos Humanos - RH Plus</title>
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

    <div class="alert alert-info" role="alert">
      <h6>Abaixo estão as reservas aprovadas.</h6>
    </div>

    <div class="accordion" id="accordionExample">
      <?php echo showReservers(); ?>

    </div>
  </div>


  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
</body>

</html>