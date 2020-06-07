<?php
require_once './db/config.php';
require_once './functions/global.php';
$erro = "";
$mensagem = "";

function userLogin($login, $password)
{
  $conection = conection();
  $sql = "SELECT * FROM adm WHERE login='$login' and senha='$password'";
  $query = mysqli_query($conection, $sql);

  if (mysqli_num_rows($query) >= 1) {
    return true;
  } else {
    return false;
  }
}

if (isset($_POST['entrar'])) {
  $login = ($_POST['login']);
  $password = ($_POST['password']);

  if (userLogin($login, $password)) {
    $id_login = getIdUser($login);
    $userLevel = getUserLevel($login);
    
    @session_start();
    $_SESSION['login'] = true;
    $_SESSION['id_login'] = $id_login;
    $_SESSION['userLevel'] = $userLevel;

    /*
    Nivel 1 = Supervisor
    Nivel 2 = RH
    Nivel 3 = Coordenador
    Nivel 4 = Diretor
    */
    if ($userLevel == 1) {
      header("location: supervisor/index.php");
    } else if ($userLevel == 2) {
      header("location: rh/index.php");
    } else if ($userLevel == 3) {
      header("location: coordenador/index.php");
    } else if ($userLevel == 4) {
      header("location: adm/index.php");
    } else {
      $mensagem = "";
      $erro = "Erro de permissão!";
    }
  } else {
    $mensagem = "";
    $erro = "Login ou senha inválidos!";
  }
}

?>

<!DOCTYPE html>
<html lang="pt_BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/login.css">
  <title>RH Plus</title>
</head>

<body>
  <form method="POST">
    <h3>Bem-Vindo ao RH Plus</h1>
      <?php
      if (!$erro == "") {
        echo "<div class='alert alert-danger alerta-sm' role='alert'>";
        echo $erro;
        echo "</div>";
      }
      if (!$mensagem == "") {
        echo "<div class='alert alert-success alerta-sm' role='alert'>";
        echo $mensagem;
        echo "</div>";
      }
      ?>
      </div>
      <div class="form-group">
        <label>Login</label>
        <input name="login" type="text" class="form-control" placeholder="Digite seu login de acesso">
      </div>
      <div class="form-group">
        <label>Senha</label>
        <input name="password" type="password" class="form-control" placeholder="Digite sua senha de acesso">
      </div>
      <button name="entrar" type="submit" class="btn btn-primary">Entrar</button>
  </form>

  <script src="./js/jquery.min.js"></script>
  <script src="./js/popper.min.js"></script>
  <script src="./js/bootstrap.min.js"></script>
</body>

</html>