<?php
require_once 'inc/config.php';

if (!isset($_SESSION['usuario'])) {

    $_SESSION['msg'] = "<div class='alert alert-warning'>É necessário fazer o login.</div>";

    header("Location: ./");
    exit;
}

if (isset($_GET["acao"])) {
    if ($_GET["acao"] == "inserir" && isset($_POST["salvar"])) {

        $bd = new PDO(SERVIDOR, USUARIO, SENHA);
        $nome = $_POST["nome"];
        $conteudo = $_POST["conteudo"];
        $usuario_id = $_SESSION["usuario"]->id;


        if ($_FILES["foto"]["error"] == 0) {
            $photoPath = getcwd() . "/img/";
            $photoName = basename($_FILES["foto"]["name"]);
            if (!file_exists($photoPath)) mkdir($photoPath, 0777, true);
            try {
                move_uploaded_file($_FILES["foto"]["tmp_name"], $photoPath . $photoName);
                $foto = $photoName;
            } catch (Exception $e) {
                echo $e;
            }
        } else if ($_POST["salvar"] == "semfoto") $foto = "img/usuariodefault.png";
        else $foto = $_SESSION["usuario"]->foto;
        $sql = $bd->prepare("INSERT INTO TBL_publicacoes (nome, conteudo, foto, usuario_id) VALUES (?,?,?,?);");

        $sql->execute([$nome, $conteudo, $foto, $usuario_id]);

        if ($sql->errorCode() == "00000") {
            header("location: ./perfil.php");
        } else echo ("<div class='alert alert-danger'>" . $sql->errorInfo()[2] . "</div>");
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Gastro Vibes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="plugins/ekko-lightbox/ekko-lightbox.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="css/adminlte.min.css">
    <link rel="stylesheet" href="css/adminlte.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">    

    <style>
        .navbar {
            background-color: #9B111E;
            overflow: auto;
        }

        .titulo {
            font-family:'Orbitron';
        }

        .ativo {
            border-radius: 5px;
            color: #FFF;
            text-decoration-line: underline;
        }

        .nav-item:hover {
            background-color: #C40233;
            border-radius: 5px;
        }

        .imagem {
            margin-left: 75px;
            border-radius: 5px;
        }
    </style>
</head>

<body style="background-color:#f5f5f5;">

    <!-- Navbar -->
    <nav class=" navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->

        <div class="col-md-10">
            <ul class="navbar-nav">
                <li class="">
                    <a class="nav-link titulo" style="margin-right:40px;font-size:25px;margin-top:2px;color:white;">GastroVibes</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block " style="margin-left:10px;margin-top:10px;margin-bottom:10px;">
                    <a href="main.php" class=" nav-link" style="color:white;">Início</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block" style="margin-left:10px;margin-top:10px;margin-bottom:10px;">
                    <a href="perfil.php" class="nav-link" style="color:white;">Perfil</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block ativo" style="margin-left:10px;margin-top:10px;margin-bottom:10px;">
                    <a href="criarreceita.php" class="nav-link" style="color:white;">Criar Receita</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block" style="margin-left:10px;margin-top:10px;margin-bottom:10px;">
                    <a href="logout.php" class="nav-link" style="color:white;">Sair</a>
                </li>
            </ul>
        </div>
        <!-- SEARCH FORM -->
        <div class="col-md-2">
            <form class="form-inline ml-3">
                <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" placeholder="Buscar" aria-label="Buscar">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </nav>
    <!-- Content Wrapper. Contains page content -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="./">Início</a></li>
                        <li class="breadcrumb-item">Cria Receita </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div style="margin-left:200px;margin-right:200px;">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="row mb-2">
                                        <div class="col-sm2-1">

                                        </div>
                                        <div class="col-sm2-1" style="margin-left:10px;margin-top:5px;">
                                            <h3>Formulario para Criar Receita</h3>
                                        </div>
                                        <div class="col-sm2-10">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card-header">


                                <div class="card-body">
                                    <form class="form-horizontal" action="?acao=inserir" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="nome">Nome:</label>
                                            <div class="col-sm-10">
                                                <input type="name" class="form-control" id="nome" placeholder="Digite o nome da Receita" name="nome" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="pwd">Descrição:</label>
                                            <div class="col-sm-10">
                                                <textarea name="conteudo" class="form-control" rows="5" id="descricao" placeholder="Fale um pouco sobre a receita e seus ingredientes" required></textarea>
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="pwd">Imagem da receita:</label>
                                            <div class="col-sm-11">
                                                <div class="card-body">
                                                    <input type="file" name="foto" id="input_foto" accept="image/*" class="d-none">
                                                    <label class="btn btn-primary" for="input_foto" style="cursor:pointer;">Inserir foto</label>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button name="salvar" type="submit" class="btn btn-success">Enviar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <br><br><br><br>

            <script src="plugins/jquery/jquery.min.js"></script>
            <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
            <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
            <script src="plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
            <script src="js/adminlte.min.js"></script>
            <script src="js/demo.js"></script>
            <script src="plugins/filterizr/jquery.filterizr.min.js"></script>
</body>
<hr>
<footer style="padding-bottom:20px;" align=center>
    <div class="float-right d-none d-sm-block">
        <b>Versão</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2020 <a href="#">GastroVibes</a>.</strong> Todos os direitos reservados.
</footer>

</html>