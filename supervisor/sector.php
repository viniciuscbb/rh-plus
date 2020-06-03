<?php
require_once '../db/config.php';
require_once '../functions/checkLogin.php';
require_once '../functions/global.php';
$erro = "";

function getUserName()
{
  $id_login = $_SESSION['id_login'];
  $conection = conection();
  $sql = "SELECT nome FROM adm WHERE id_adm='$id_login'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $nome = $row['nome'];
  return $nome;
}

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

function showCollaborator()
{
  $id_sector = htmlspecialchars($_GET["sector"]);
  $conection = conection();
  $sql = "SELECT * FROM colaboradores where id_setor = '$id_sector'";
  $query = mysqli_query($conection, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $id_colaborador = $row['id_colaborador'];
    $nome           = $row['nome'];
    $cracha         = $row['cracha'];
    $cargo          = $row['cargo'];
    $ramal          = $row['ramal'];
    $rota           = $row['id_rota'];
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

function getIdReserve(){
  $id_login = $_SESSION['id_login'];
  
  $conection = conection();
  $sql = "SELECT id_reserva FROM reservas where id_supervisor = '$id_login' and data is null";
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
function createReserve(){
  $id_login = $_SESSION['id_login'];

  $conection = conection();
  $sql = "INSERT INTO reservas (id_supervisor) VALUES ('$id_login')";
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
  $idSelected = ($_POST['idSelected']);
  $array = explode('-', $idSelected);

  //Cria a reserva
  createReserve();

  //Envia os dados separados para o banco
  foreach ($array as $values) {
    if (insertList($values)) {
      $nextPage = $nextPage+1;
    }
  }

  if ($nextPage > 0) {
    $id_sector = htmlspecialchars($_GET["sector"]);
    header("location: reserve.php?reserve=".getIdReserve()."&sector=".$id_sector);
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
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">Bem-Vindo, <?php echo getUserName(); ?>!</li>
    </ol>
  </nav>

  <div class="setores">
    <div class="alert alert-info" role="alert">
      Setor selecionado: <b><?php echo selectedSector(); ?>.</b>
    </div>

    <?php
      if (!$erro == "") {
        echo "<div class='alert alert-danger alerta-sm' role='alert'>";
        echo $erro;
        echo "</div>";
      }
    ?>

    <div id="saveSuccess" class="alert alert-success" role="alert" style="display: none;">
      <h4 class="alert-heading">Salvo com sucesso!</h4>
      <hr>
      <p>Clique em Prosseguir para continuar.</p>
    </div>
    <table class="table table-striped tabela">
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
      <button id="save" type="button" class="btn btn-success">Salvar</button>
      <form method="post">
        <button id="nextBtn" name="next" type="submit" class="btn btn-primary" disabled>Prosseguir</button>
        <input id="total" name="idSelected" type="text" style="display: none;">
      </form>
    </div>
  </div>


  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/sector.js"></script>
</body>

</html>