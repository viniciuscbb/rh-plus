<?php
require_once '../db/config.php';
require_once '../functions/checkLogin.php';
require_once '../functions/global.php';

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

if (isset($_POST['Delete'])) {
  $id_colaborador = $_POST['id_colaborador'];
  $conection = conection();

  $result = mysqli_query($conection, "DELETE FROM lista WHERE id_colaborador = '$id_colaborador'");
  if (!$result) {
    echo "Error";
  }
}

function showCollaborator()
{
  $id_sector = htmlspecialchars($_GET["sector"]);
  $conection = conection();
  $sql = "SELECT C.id_colaborador, C.nome FROM reservas as R INNER JOIN lista as L ON R.id_reserva = L.id_reserva INNER JOIN colaboradores as C ON L.id_colaborador = C.id_colaborador";
  $query = mysqli_query($conection, $sql);
  while ($row = mysqli_fetch_array($query)) {
    $id_colaborador = $row['id_colaborador'];
    $nome           = $row['nome'];

    echo '
    <tr>
      <td>
        <form method="post">
          <input type="hidden" name="id_colaborador" value=' . $id_colaborador . '> 
          <button name="Delete" class="btn btn-danger btn-sm" type="submit" title="Remover o colaborador da lista"">X</button>
        </form>
      </td>
      <td><b>' . $nome . '</b></td>
      <td>
        <div class="form-check">
          <input id="' . $id_colaborador . '" value="' . $id_colaborador . '" class="form-check-input position-static checkbox transporte" type="checkbox" checked>
        </div>
      </td>
      <td>
        <div class="form-check">
          <input id="' . $id_colaborador . '" value="' . $id_colaborador . '" class="form-check-input position-static checkbox alimentacao" type="checkbox" checked>
        </div>
      </td>
      <td>
        <input id="' . $id_colaborador . '" class="form-control input-number horas" type="number" value="0">
      </td>
    </tr>';
  }
}

function insertReserve($id_colaborador)
{
  $conection = conection();

  $sql = "SELECT * FROM colaboradores where id_colaborador = '$id_colaborador'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $id_colaborador = $row['id_colaborador'];
  $nome           = $row['nome'];
  $cracha         = $row['cracha'];
  $cargo          = $row['cargo'];
  $ramal          = $row['ramal'];
  $rota           = $row['id_rota'];
  $hora           = $row['hora'];

  $sql = "INSERT INTO reservas (id_supervisor, id_colaborador, id_rota) VALUES (2, '$id_colaborador', $rota)";
  $query = mysqli_query($conection, $sql);

  if ($query) {
    return true;
  } else {
    return false;
  }
}

if (isset($_POST['next'])) {
  $idSelected = ($_POST['idSelected']);
  $array = explode('-', $idSelected);

  foreach ($array as $values) {
    if (insertReserve($values)) {
      echo "deu certo";
    }
  }
}

if (isset($_POST['delete'])) {
  $id_reserva = htmlspecialchars($_GET["reserve"]);
  $conection = conection();

  $result = mysqli_query($conection, "DELETE FROM lista WHERE id_reserva = '$id_reserva'");
  if (!$result) {
    echo "Error";
  }

  $result = mysqli_query($conection, "DELETE FROM reservas WHERE id_reserva = '$id_reserva'");
  if (!$result) {
    echo "Error";
  }else{
    echo '<script> alert("Reserva cancelada com sucesso!"); history.go(-2)</script>';

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
    <div id="saveSuccess" class="alert alert-success" role="alert" style="display: none;">
      <h4 class="alert-heading">Suas configurações foram salvas com sucesso!</h4>
      <hr>
      <p>Clique em Prosseguir para continuar.</p>
    </div>
    <table class="table table-striped tabela">
      <thead>
        <tr>
          <th scope="col">Opc</th>
          <th scope="col">Nome</th>
          <th scope="col">Transporte</th>
          <th scope="col">Alimentação</th>
          <th scope="col">H. Extras</th>
        </tr>
      </thead>
      <tbody>
        <?php echo showCollaborator(); ?>
      </tbody>
    </table>
    <div class="button">
      <button id="save" type="button" class="btn btn-success">Salvar</button>
      <button name="next" type="button" class="btn btn-primary">Criar Reserva</button>
      <form method="post">
        <button name="delete" type="submit" class="btn btn-danger">Cancelar Reserva</button>
        <input id="totalTransporte" name="totalTransporte" type="text" style="display: none;">
        <input id="totalAlimentacao" name="idSelected" type="text" style="display: none;">
        <input id="totalHoras" name="totalHoras" type="text" style="display: none;">
      </form>
    </div>
  </div>


  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/sector.js"></script>
</body>

</html>