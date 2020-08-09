<?php
require_once '../db/config.php';
require_once '../functions/checkLogin.php';
require_once '../functions/global.php';

acessoRestrito(4);

$mensagem = 'Para realizar uma pesquisa, preencha o mês desejado abaixo.';
date_default_timezone_set('America/Sao_Paulo');
$mes = date('m');

if (isset($_GET["month"])) {
  $mes = $_GET["month"];
  switch ($mes) {
    case 01:
      $mensagem = 'Mês selecionado: Janeiro';
      break;
    case 02:
      $mensagem = 'Mês selecionado: Fevereiro';
      break;
    case 03:
      $mensagem = 'Mês selecionado: Março';
      break;
    case 04:
      $mensagem = 'Mês selecionado: Abril';
      break;
    case 05:
      $mensagem = 'Mês selecionado: Maio';
      break;
    case 06:
      $mensagem = 'Mês selecionado: Junho';
      break;
    case 07:
      $mensagem = 'Mês selecionado: Julho';
      break;
    case 8:
      $mensagem = 'Mês selecionado: Agosto';
      break;
    case 9:
      $mensagem = 'Mês selecionado: Setembro';
      break;
    case 10:
      $mensagem = 'Mês selecionado: Outubro';
      break;
    case 11:
      $mensagem = 'Mês selecionado: Novembro';
      break;
    case 12:
      $mensagem = 'Mês selecionado: Dezembro';
      break;
  }
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

function getNameSupervisor($id_adm)
{
  $conection = conection();
  $sql = "SELECT nome FROM adm WHERE id_adm = '$id_adm'";
  $query = mysqli_query($conection, $sql);
  $row = mysqli_fetch_array($query);
  $name = $row['nome'];
  return $name;
}

function selectCharts($mes)
{
  $chart = '';
  $conection = conection();
  $sql = "SELECT 
            SUM(valor) AS valor, 
            id_setor AS setor 
          FROM 
            reservas 
          WHERE 
            MONTH(data) = '$mes' AND status = 3
          GROUP BY id_setor";
  $query = mysqli_query($conection, $sql);
  
  while ($row = mysqli_fetch_array($query)) {
    $valor = $row['valor'];
    $setor = getSector($row['setor']);

    $chart = $chart."['$setor', $valor],";

  }

  return "<script type='text/javascript'>
  // Load Charts and the corechart and barchart packages.
  google.charts.load('current', {
    'packages': ['corechart']
  });

  // Draw the pie chart and bar chart when Charts is loaded.
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Setor');
    data.addColumn('number', 'Valor');
    data.addRows([$chart]);

    var piechart_options = {
      title: 'Porcentagem de gastos por setor',
    };
    var piechart = new google.visualization.PieChart(document.getElementById('piechart_div'));
    piechart.draw(data, piechart_options);

    var barchart_options = {
      title: 'Valor gasto por setor',
      legend: 'none'
    };
    var barchart = new google.visualization.ColumnChart(document.getElementById('barchart_div'));
    barchart.draw(data, barchart_options);
  }
</script>";

}


?>

<!DOCTYPE html>

<html lang="pt_BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/adm.css">
  <script type="text/javascript" src="../js/charts.js"></script>
  <title>Diretor - RH Plus</title>
  
</head>

<body>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark barra">
    <a class="navbar-brand" href="index.php">RH Plus</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Alterna navegação">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-item nav-link" href="index.php">Início</a>
        <a class="nav-item nav-link active" href="#">Indicadores<span class="sr-only">(Página atual)</span></a>
      </div>
    </div>
  </nav>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php">Reservas</a></li>
      <li class="breadcrumb-item active" aria-current="page">Indicadores</li>
    </ol>
  </nav>
  <div class="charts">
    <p class="lead"></p>
    <div class="alert alert-info" role="alert">
      <h6><?php echo $mensagem; ?></h6>
    </div>
    <div>
      <form action="search.php" method="post">
        <div class="form-row">
          <div class="col">
            <select class="custom-select" name="month" required>
              <option selected>Escolher...</option>
              <option value="01">Janeiro</option>
              <option value="02">Fevereiro</option>
              <option value="03">Março</option>
              <option value="04">Abril</option>
              <option value="05">Maio</option>
              <option value="06">Junho</option>
              <option value="07">Julho</option>
              <option value="08">Agosto</option>
              <option value="09">Setembro</option>
              <option value="10">Outubro</option>
              <option value="11">Novembro</option>
              <option value="12">Dezembro</option>
            </select>
          </div>
          <button type="submit" name="search" class="btn btn-outline-success">Pesquisar</button>
        </div>
      </form>
    </div>

    <div class="container">
      <div class="row">
        <div class="col">
          <div id="piechart_div"></div>
        </div>
        <div class="col">
          <div id="barchart_div"></div>
        </div>
        <div class="w-100"></div>
      </div>
    </div>

  </div>
  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/adm.js"></script>
  <?php echo selectCharts($mes); ?>

</body>

</html>