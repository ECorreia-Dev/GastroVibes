<?php
require_once 'inc/config.php';

if (!isset($_SESSION['usuario'])) {
    $_SESSION['msg'] = "<div class='alert alert-warning'> É necessário fazer o login! </div>";
    header("Location: ./");
}

if (isset($_GET["acao"])) {
    if ($_GET["acao"] == "edit" && isset($_POST["salvar"])) {
        function AtualizarUsuario($id)
        {
            $bd = new PDO(SERVIDOR, USUARIO, SENHA);
            $sql = $bd->prepare("SELECT * FROM tbl_usuario WHERE id = ?");
            $sql->execute([$id]);
            if ($obj = $sql->fetchObject()) $_SESSION["usuario"] = $obj;
        }

        $bd = new PDO(SERVIDOR, USUARIO, SENHA);
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $senha = $_POST["senha"] != "" ? $_POST["senha"] : $_SESSION["usuario"]->senha;
        $data_nascimento = $_POST["data_nascimento"];
        $id = $_POST["id"];

        if ($_FILES["foto"]["error"] == 0) {
            $photoPath = getcwd() . "/img/";
            $photoName = basename($_FILES["foto"]["name"]);
            if (!file_exists($photoPath)) mkdir($photoPath, 0777, true);
            try {
                move_uploaded_file($_FILES["foto"]["tmp_name"], $photoPath . $photoName);
                $foto = "img/" . $photoName;
            } catch (Exception $e) {
                echo $e;
            }
        } else if ($_POST["salvar"] == "semfoto") $foto = "img/usuariodefault.png";
        else $foto = $_SESSION["usuario"]->foto;

        $sql = $bd->prepare("UPDATE tbl_usuario SET email             = ?,
                                                    nome              = ?,
                                                    senha             = ?,
                                                    data_nascimento   = ?,
                                                    foto              = ?
                                                WHERE id = ?");

        $sql->execute([$email, $nome, $senha, $data_nascimento, $foto, $id]);
        if ($sql->errorCode() == "00000") {
            AtualizarUsuario($id);
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
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
    <script src="js/adminlte.min.js"></script>
    <script src="js/demo.js"></script>
    <script src="plugins/filterizr/jquery.filterizr.min.js"></script>
    <script>
        $(document).ready(() => {
            $("#input_foto").on("change", function() {
                if (this.files && this.files[0]) {
                    let reader = new FileReader()
                    reader.onload = function(e) {
                        $("#img_foto").attr("src", e.target.result)
                    }
                    reader.readAsDataURL(this.files[0])
                }
            })
            $("button#apagar_foto").on("click", function() {
                $("img#img_foto").attr("src", "./img/usuariodefault.png")
                $("button#submit").val("semfoto")
            })
        })
    </script>
    <style>
        footer {
            border-top: 2px solid rgb(153, 84, 84);
            padding: 15px;
        }

        .navbar {
            background-color: #9B111E;
            overflow: auto;
        }

        .titulo {
            font-family: 'Orbitron', cursive;
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

        .nav-link {
            color: white;
        }

        .imagem {
            margin-left: 75px;
            border-radius: 5px;
        }

        div#content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60%;
        }

        div#div_foto>img {
            width: 200px;
            height: 200px;
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
                <li class="nav-item d-none d-sm-inline-block" style="margin-left:10px;margin-top:10px;margin-bottom:10px;">
                    <a href="main.php" class="nav-link" style="color:white;">Início</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block" style="margin-left:10px;margin-top:10px;margin-bottom:10px;">
                    <a href="perfil.php" class="nav-link" style="color:white;">Perfil</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block" style="margin-left:10px;margin-top:10px;margin-bottom:10px;">
                    <a href="criarreceita.php" class="nav-link" style="color:white;">Criar Receita</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block" style="margin-left:10px;margin-top:10px;margin-bottom:10px;">
                    <a href="logout.php" class="nav-link" style="color:white;">Sair</a>
                </li>
            </ul>
        </div>
        <!-- SEARCH FORM -->
        <!-- <div class="col-md-2">
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
        </div> -->
    </nav>
    <!-- /.navbar -->

    <main class="container my-3">
        <form action="?acao=edit" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $_SESSION['usuario']->id ?>">
            <div class="row">
                <div class="col-md-3">
                    <div id="div_foto">
                        <br>
                        <img id="img_foto" src="img/<?= $_SESSION['usuario']->foto ?>" alt="<?= $_SESSION['usuario']->usuario ?>'s profile photo">
                    </div>
                    <input type="file" name="foto" id="input_foto" accept="image/*" class="d-none">
                    <div class="mt-1">
                        <label class="btn btn-primary" for="input_foto" style="cursor:pointer;">Editar Foto</label>
                        <button id="apagar_foto" type="button" class="btn btn-danger mb-2"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input class="form-control" type="text" name="usuario" id="usuario" value="<?= $_SESSION['usuario']->usuario ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" type="email" name="email" id="email" value="<?= $_SESSION['usuario']->email ?>">
                    </div>
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input class="form-control" type="text" name="nome" id="nome" value="<?= $_SESSION['usuario']->nome ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input class="form-control" type="password" name="senha" id="password" placeholder="Deixe vazio para não alterar a senha">
                    </div>
                    <div class="form-group">
                        <label for="data_nascimento">Data de Nascimento</label>
                        <input class="form-control" type="date" name="data_nascimento" id="data_nascimento" value="<?= date('Y-m-d', strtotime($_SESSION['usuario']->data_nascimento)) ?>">
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6"><button type="submit" class="btn w-75 mx-auto btn-block btn-success" name="salvar" id="submit">Alterar</button></div>
                <div class="col-md-6"><button type="reset" class="btn w-75 mx-auto btn-block btn-danger">Limpar</button></div>
            </div>
        </form>
    </main>
    <footer class="text-center mt-3">
        <div class="float-right d-none d-sm-block"><b>Versão</b> 1.0.0</div>
        <strong>Copyright &copy; 2020 <a href="#">GastroVibes</a>.</strong> Todos os direitos reservados.
    </footer>
</body>

</html>