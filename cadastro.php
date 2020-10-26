<?php
require_once  "./inc/config.php";

$con = new PDO(SERVIDOR, USUARIO, SENHA);
$foto = "usuariodefault.png";
$seguidores = 0;
$data_nascimento = "CURRENT_TIMESTAMP";
// MYSQL EXCEPTION !!!
$sql = $con->prepare("INSERT INTO tbl_usuario (id, usuario, email, nome, senha, foto, data_nascimento) VALUES (null,?,?,?,?,?,$data_nascimento);");
$sql->execute([$_POST['usuario'], $_POST['email'], $_POST['nome'], $_POST['senha'], $foto]);

header("Location: ./");
