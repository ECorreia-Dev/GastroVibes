<?php
require_once 'inc/config.php';

if (!isset($_SESSION['usuario'])) {

  $_SESSION['msg'] = "<div class='alert alert-warning'>Bem vindo de volta" . $_SESSION['usuario'] . "!</div>";

  header("Location: ./");
  exit;
}

?>

<!DOCTYPE html>
<html>

<?php include './head.php' ?>

<body style="background-color:#f5f5f5;">

  <!-- Navbar -->
  <nav class=" navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->

    <div class="col-md-10">
      <ul class="navbar-nav">
        <li class="">
          <a class="nav-link titulo" style="margin-right:40px;font-size:25px;margin-top:2px;color:white;">GastroVibes</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block ativo" style="margin-left:10px;margin-top:10px;margin-bottom:10px;">
          <a href="main.php" class=" nav-link" style="color:white;">Início</a>
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
            <li class="breadcrumb-item"><a href="./main.php">Início</a></li>
            <li class="breadcrumb-item">Feed </li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <main class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-6">
          <?php foreach (Connection::QueryAll("select * from ver_publicacoes where status = 'ativo' order by id desc") as $item) { ?>
            <div class="card">
              <div class="card-header">
                <div class="card-title">
                  <div class="row mb-2">
                    <div class="col-sm2-1">
                      <img width="30px" height="30px" src="<?= $item->ufoto ?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="col-sm2-1" style="margin-left:10px;margin-top:5px;">
                      <h4><?= $item->unome ?></h4>
                    </div>
                    <div class="col-sm2-2">
                      <?php if ($item->usuario_id !== $_SESSION['usuario']->id) { ?>
                        <a class="btn btn-outline-success btn-sm mx-2" href="./main.php?action=seguir&id=<?= $item->usuario_id ?>">
                          <span class="fas fa-user-friends"></span> Seguir
                        </a>
                      <?php } ?>
                    </div>
                    <div class="col-sm2-8"></div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <img class="img-fluid w-100" style="object-fit: contain; max-height: 250px" src='img/<?= $item->foto ?>' alt='Img. Receita'>
                <div class="card-body"><?= $item->conteudo ?></div>
              </div>
              <div class="card-footer">
                <a class="btn btn-outline-success btn-sm" href="./main.php?action=favoritar&id=<?= $item->id ?>">
                  <!-- //ins. rec._salvas vai precisar mudar um monte de coisa mas o conceito é esse -->
                  <span class="fa fa-plus"></span>
                </a>
                <?php
                $likes = Connection::QueryObject("select quant_likes as quantidade from ver_likes where publicacao_id = '" . $item->id . "'")->quantidade ?? 0;
                ?>
                <a class="btn btn-outline-primary btn-sm" href="./main.php?action=like&id=<?= $item->id ?>">
                  <!-- //upd. public. mesma coisa do de cima -->
                  <span class="fa fa-thumbs-up"></span>
                </a>
                <b> <?= $likes ?></b>
                <?php if ($item->usuario_id == $_SESSION['usuario']->id) { ?>
                  <a class="text-danger float-right excluir" data-id="<?= $item->id ?>">
                    <span class="fas fa-trash"></span>
                  </a>
                <?php } ?>
              </div>
            </div>
          <?php } ?>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-3">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-4">
                  <img width="100px" height="100px" src="<?= $_SESSION['usuario']->foto ?>" class="img-circle p-2" alt="User Image">
                </div>
                <div class="col-md-8 d-flex align-items-center">
                  <h5><?= $_SESSION['usuario']->nome ?></h5>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <h6 class="text-blue">Seguindo</h6>
              <ul class="list-group">
                <?php foreach (Connection::QueryAll("select * from ver_seguidores where seguidor_id = '" . $_SESSION['usuario']->id . "'") as $item) { ?>
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-md-4">
                        <img src="<?= $item->foto ?? 'img/usuariodefault.png' ?>" class="img-circle w-100" alt="Follower Image">
                      </div>
                      <div class="col-md-8 d-flex align-items-center">
                        <h6><?= $item->seguido ?></h6>
                      </div>
                    </div>
                  </li>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-1"></div>
      </div>
      <hr>
      <footer class="text-center mt-3">
        <div class="float-right d-none d-sm-block"><b>Versão</b> 1.0.0</div>
        <strong>Copyright &copy; 2020 <a href="#">GastroVibes</a>.</strong> Todos os direitos reservados.
      </footer>
    </div>
  </main>
</body>
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

</html>