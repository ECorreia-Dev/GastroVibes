<?php
require_once  "./inc/config.php";

$con = new PDO(SERVIDOR, USUARIO, SENHA);

// procura o usuário no banco de dados
$sql = $con->prepare("SELECT * FROM tbl_usuario WHERE email=? AND senha=?");
$sql->execute(array($_POST['email'], ($_POST['senha'])));

// devolve o registro do usuário procurado
$row=$sql->fetchObject();

// se o usuário foi localizado
if ($row) {
    $_SESSION ['usuario']=$row;
    header("Location: ./main.php");

// se o usuário não foi localizado
} else {
    $_SESSION ['msg'] ="<div class = 'alert alert-danger'> <strong> Ops! </strong> Acesso negado </div>";
    header("Location: ./");
}