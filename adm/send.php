<?php
require_once '../db/config.php';
require_once '../functions/checkLogin.php';
require_once '../functions/global.php';

acessoRestrito(4);

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


/*Status reserva
1 = Pendente para Diretor
2 = Pendente para Coordenador
3 = Aprovado
4 = Refazer
5 = Negado

Cod das msg
1 = Aceito com sucesso
2 = Erro ao aceitar
3 = Negado com sucesso
4 = Erro ao negar
5 = Reanálise com sucesso
6 = Erro ao reanalisar
*/

//Caso clique em aceitar
if (isset($_POST['accept'])) {
  $id_reserva = ($_POST['idReserva']);

  $conection = conection();
  $sql = "UPDATE 
            reservas 
          SET 
            status = 3
          WHERE 
            id_reserva = '$id_reserva'";
  $query = mysqli_query($conection, $sql);
  if ($query) {
    header("location: index.php?msg=1&id=".$id_reserva);
  } else {
    header("location: index.php?msg=2&id=".$id_reserva);
  }
}

//Caso clique em cancelar
if (isset($_POST['deny'])) {
  $id_reserva = ($_POST['idReserva']);
  $conection = conection();
  $sql = "UPDATE 
            reservas 
          SET 
            status = 5
          WHERE 
            id_reserva = '$id_reserva'";
  $query = mysqli_query($conection, $sql);
  if ($query) {
    header("location: index.php?msg=3&id=".$id_reserva);
  } else {
    header("location: index.php?msg=4&id=".$id_reserva);
  }
}

//Caso clique em refazer
if (isset($_POST['remake'])) {
  $id_reserva = ($_POST['inputIdReserva']);
  $refazer = ($_POST['inputRemake']);
  $conection = conection();
  $sql = "UPDATE 
            reservas 
          SET 
            status = 4, refazer = '$refazer'
          WHERE 
            id_reserva = '$id_reserva'";
  $query = mysqli_query($conection, $sql);
  if ($query) {
    header("location: index.php?msg=5&id=".$id_reserva);
  } else {
    header("location: index.php?msg=6&id=".$id_reserva);
  }
}

?>