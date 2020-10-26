<?php
session_start();

unset($_SESSION['usuario']);

$_SESSION['msg'] = "<div class='alert alert-warning'>Sessão encerrada!</div>";

header("Location: ./");
        
?>    