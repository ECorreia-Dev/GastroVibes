<?php
require_once  "./inc/config.php";

$con = new PDO(SERVIDOR, USUARIO, SENHA);
$foto = "usuariodefault.png";
// MYSQL EXCEPTION !!!
$sql = $con->prepare("INSERT INTO TBL_publicacoes (id, foto, nome, likes, conteudo) VALUES (null, ?, ?, 0, ?);");
$sql->execute([ $_POST['foto'], $_POST['nome'],  $_POST['conteudo'] ]);

header("Location:main.php");

