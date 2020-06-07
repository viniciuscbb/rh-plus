<?php

function acessoRestrito($userLevel)
{
	if (!isset($_SESSION)) {
		session_start();
	}
	if (!isset($_SESSION['login']) && !isset($_SESSION['userLevel'])) {
		header('Location: ../login.php');
	} else {
		if ($_SESSION['userLevel'] != $userLevel) {
			header("Location: ../login.php?login=" . $_SESSION['userLevel'] . "");
		}
	}
}

function getIdUser($login){
  $conection = conection();
  $sql = "SELECT id_adm FROM adm WHERE login='$login'";
	$query = mysqli_query($conection, $sql);
	$row = mysqli_fetch_array($query);
	$id_adm = $row['id_adm'];
  return $id_adm;
}

function getUserLevel($login){
  $conection = conection();
  $sql = "SELECT nivel FROM adm WHERE login='$login'";
	$query = mysqli_query($conection, $sql);
	$row = mysqli_fetch_array($query);
	$nivel = $row['nivel'];
  return $nivel;
}

function getOffice(){
  $userLevel = $_SESSION['userLevel'];

    /*
    Nivel 1 = Supervisor
    Nivel 2 = RH
    Nivel 3 = Coordenador
    Nivel 4 = Diretor
		*/
		
  if($userLevel == 1){
		return "Supervisor";
	}else if($userLevel == 2){
		return "Recursos Humanos";
	}else if($userLevel == 3){
		return "Coordenador";
	}else if($userLevel == 4){
		return "Diretor";
	}
  
}

?>
