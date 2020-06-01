<?php
session_start();
if(!isset($_SESSION['login']) == true){
  unset($_SESSION['login']);
  echo "<script>window.location = './login.php'</script>";
}
?>
