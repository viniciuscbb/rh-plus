<?php
require_once '../db/config.php';
require_once '../functions/checkLogin.php';
require_once '../functions/global.php';

acessoRestrito(2);

$mensagem = '<div class="alert alert-info" role="alert">
              <h6>Abaixo estão as reservas pendentes.</h6>
            </div>';

function getSector($id_setor)
{
  $conection = conection();
  $sql = "SELECT setor FROM setores WHERE id_setor = '$id_setor'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $setor = $row['setor'];
  return $setor;
}

/*Status reserva
1 = Pendente para Diretor
2 = Pendente para Coordenador
3 = Aprovado
4 = Refazer
5 = Negado
*/

function getTotalHours(){
  $id_reserva = htmlspecialchars($_GET["hours"]);
  $conection = conection();
  $sql = "SELECT sum(hora) as hora FROM lista WHERE id_reserva = '$id_reserva'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $hora = $row['hora'];
  return $hora;
}

function showRoutes()
{
  $id_reserva = htmlspecialchars($_GET["hours"]);
  $conection = conection();
  $sql = "SELECT 
            C.cracha as cracha, C.nome as nome, L.hora as hora
          FROM 
            lista as L
          INNER JOIN 
            colaboradores as C ON L.id_colaborador = C.id_colaborador 
          WHERE L.id_reserva = '$id_reserva'
          GROUP BY
            nome
          ORDER BY
            nome";

  $query = mysqli_query($conection, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $cracha  = $row['cracha'];
    $nome  = $row['nome'];
    $hora  = $row['hora'];

    echo '
    <tr>
      <td>' . $cracha . '</td>
      <td><b>' . $nome . '</b></td>
      <td>
        <h5>
          <span class="badge badge-primary badge-pill" id="totalColaboradores">
          ' . $hora . '
          </span>
        </h5>
      </td>
    </tr>';
  }
}


function selectedSector()
{
  $id_reserva = htmlspecialchars($_GET["hours"]);
  $conection = conection();
  $sql = "SELECT id_setor FROM reservas WHERE id_reserva='$id_reserva'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $setor = getSector($row['id_setor']);
  return $setor;
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
      <li class="breadcrumb-item"><a href="index.php">Reservas</a></li>
      <li class="breadcrumb-item active" aria-current="page">Horas Extras</li>
    </ol>
  </nav>
  <div class="setores">
    <p class="lead"></p>

    <div class="alert alert-info" role="alert">
      Setor selecionado: <b><?php echo selectedSector(); ?></b>.
    </div>

    <table class="table table-striped tabela">
      <thead>
        <tr>
          <th scope="col">Chapa</th>
          <th scope="col">Nome</th>
          <th scope="col">Horas</th>
        </tr>
      </thead>
      <tbody>
        <?php echo showRoutes(); ?>
      </tbody>
    </table>
    <div class="valor">
      <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <h4>Total </h4>
          <h4><span class="badge badge-primary"><?php echo getTotalHours(); ?> horas</span></h4>
        </li>
      </ul>
    </div>
  </div>


  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
</body>

</html>