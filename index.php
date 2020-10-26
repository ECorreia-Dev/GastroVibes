<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>GastroVibes</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <style>
        nav {
            height: 50px;
            margin-top: 0;
            margin-bottom: 0;
            padding: 0;
        }

        .conteudo {
            margin-top: 0;
            margin-bottom: 0;
            padding-top: 100px;
        }

        footer {
            padding-top: 14px;
            height: 50px;
        }

        body {
            background-image: url("img/teste2.png");
            background-repeat: no-repeat;
            background-size: cover;
            width: auto;
            height: auto;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style=" background-color: #9B111E;">
        <span class="navbar-brand" style="font-family:'Orbitron';color:white;">GastroVibes</span>
    </nav>
    <div class="container conteudo">
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6" align=center>
                <form method="post" action="autenticacao.php">
                    <div class="card " style="border-color: #9B111E;">
                        <div class="card-header text-light" style=" background-color: #9b111e;">
                            <h2>Entre com sua Conta</h2>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_SESSION['msg'])) {
                                echo $_SESSION['msg'];
                                unset($_SESSION['msg']);
                            } ?>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input id="email" name="email" type="email" class="form-control" required placeholder="Digite o email" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="pwd">Senha:</label>
                                <input id="senha" name="senha" type="password" class="form-control" required placeholder="Digite a senha">
                            </div>

                        </div>
                        <div class="card-footer">
                            <a class="btn btn-outline-primary my-2 my-sm-0" href="register.php">Registre-se</a>
                            <button type="submit" class="btn btn-success ">Entrar</button>
                            <span class="text-success mr-sm-2">&nbsp;</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-3"></div>
    </div>
    <footer class=" text-center text-light fixed-bottom" align=center style="background-color:#f5f5f5;padding-bottom:20px;padding-left:100px;">
        <div class="float-right d-none d-sm-block" style="color:black;">
            <b>Versão</b> 1.0.0
        </div>
        <div style="color:black;"><strong>Copyright &copy; 2020 <a href="#">GastroVibes</a>.</strong> Todos os direitos reservados.</div>
    </footer>
</body>

</html>