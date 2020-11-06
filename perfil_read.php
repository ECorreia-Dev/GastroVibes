<?php require_once 'inc/config.php';
if (!isset($_SESSION['usuario'])) {
  $_SESSION['msg'] = "<div class='alert alert-warning'> É necessário fazer o login! </div>";
  header("Location: ./");
}

$req = (object) $_REQUEST;

$usuario = Connection::QueryObject("select * from tbl_usuario where id = '" . $req->id . "'");

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
  <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
  <!-- Sweetalert -->
  <link href="./inc/sweetalert/sweetalert2.min.css" rel="stylesheet">
  <script type="text/javascript" src="./inc/sweetalert/sweetalert2.min.js"></script>
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
      font-family: 'Orbitron';
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
  </style>
</head>

<body style="background-color:#f5f5f5;">

  <!-- Navbar -->
  <nav class=" navbar navbar-expand navbar-white navbar-light">
    <div class="col-md-10">
      <ul class="navbar-nav">
        <li class="">
          <a class="nav-link titulo" style="margin-right:40px;font-size:25px;margin-top:2px;color:white;">GastroVibes</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block " style="margin-left:10px;margin-top:10px;margin-bottom:10px;">
          <a href="main.php" class="nav-link" style="color:white;">Início</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block ativo" style="margin-left:10px;margin-top:10px;margin-bottom:10px;">
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
  </nav>

  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="./main.php">Início</a></li>
            <li class="breadcrumb-item">Perfil </li>
          </ol>
        </div>
      </div>
      <div class="row mb-2">
        <div class="col-md-6 d-flex flex-row-reverse">
          <img style="height: 100px; width: 100px" src="<?= $usuario->foto ?>" class="img-circle" alt="User Image">
        </div>
        <?php
        $quant = Connection::QueryObject("select count(*) as quantidade from ver_publicacoes where usuario_id = '" . $req->id . "'")->quantidade ?? 0;
        $seguidores = Connection::QueryObject("select count(*) as quantidade from ver_seguidores where usuario_id = '" . $req->id . "'")->quantidade ?? 0;
        $seguindo = Connection::QueryObject("select count(*) as quantidade from ver_seguidores where seguidor_id = '" . $req->id . "'")->quantidade ?? 0;
        $arr_seguidores = Connection::QueryAll("select * from ver_seguidores where usuario_id = '" . $req->id . "'");
        ?>
        <div class="col-md-6">
          <div><h5><b><?= $usuario->nome ?></b>
          <?php if ($_SESSION['usuario']->id === $usuario->id) { ?>
             <a href="./editar_perfil.php" class="btn btn-primary"><i class="fas fa-cog"></i></a>
            <?php } ?>
            </h5>
            <p><?= $quant ?> Publicaç<?= $quant == 1 ? 'ão' : 'ões' ?></p>
            <span> <?= $seguidores ?> Seguidores</span><span class="ml-2"><?= $seguindo ?> Seguindo</span>

            <span>
              <!-- Concatenando os seguidores do usuario -->
              <br>Seguido por:
              <!-- Filtra os caracteres, substitui a , por e -->
              <?php
              $temp = "";
              foreach ($arr_seguidores as $item) $temp .= "$item->seguidor, ";
              $temp = substr($temp, 0, -2);

              $re = '/(\,)((\s[^\s\,]+)+)$/miu';
              $subst = ' e$2';
              $temp = preg_replace($re, $subst, $temp);

              echo $temp;
              ?>
            </span>

          </div>
        </div>
      </div>
  </section>
  <main>
    <section class="content">
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <div class="card-title">
                <div class="col-sm-12">
                  <div style="font-size:18px;">
                    <a href="./main_user.php?id='<?= $req->id ?>'">Publicações</a>
                    <!-- publicações criadas pelo usuario ativo -->
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <?php foreach (Connection::QueryAll("select * from ver_publicacoes where usuario_id = '" . $req->id . "' and status = 'ativo' ") as $item) { ?>
                  <div class="col-4">
                    <div class="card">
                      <div class="card-img-top">
                        <img class="w-100" style="object-fit: cover" src='img/<?= $item->foto ?>' alt='User Image'>
                      </div>
                      <div class="card-footer"><?= $item->nome ?>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <div class="card-title">
                <div class="col-sm-12">
                  <div style="font-size:18px;">
                    <p>Favoritos</p>
                    <!-- publicações favoritas do usuario ativo -->
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <?php foreach (Connection::QueryAll("select * from ver_favoritas where uid = '" . $usuario->id . "'") as $item) { ?>
                  <div class="col-4">
                    <div class="card">
                      <div class="card-img-top">
                        <img class="w-100" style="object-fit: cover" src='img/<?= $item->foto ?>' alt='User Image'>
                      </div>
                      <div class="card-footer"><?= $item->nome ?></div>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <footer class="text-center mt-3">
    <div class="float-right d-none d-sm-block"><b>Versão</b> 1.0.0</div>
    <strong>Copyright &copy; 2020 <a href="#">GastroVibes</a>.</strong> Todos os direitos reservados.
  </footer>

  <script>
    $(document).ready(() => {
      $(".excluir").on("click", function() {
        //funciona com base no usuario_id -> data-id
        let id = $(this).data("id")
        //chamar o sweetalert
        Swal.fire({
          titleText: 'Tem Certeza?',
          showDenyButton: true,
          confirmButtonText: 'Sim',
          denyButtonText: 'Não',
        }).then(r => { //a r r o w # f u n c t i o n 
          if (r.isConfirmed) location = `./main.php?action=excluir&id=${id}`
        })
      })
    })
  </script>

</body>

</html>