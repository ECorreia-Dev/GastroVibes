<?php
session_start();
header("Content-Type:text/html;charset=utf8");
define("SERVIDOR", "mysql:host=localhost;dbname=bd_gastrovibes;charset=utf8;port=3308");
define("USUARIO", "root");
define("SENHA", "");

class Connection
{
  static function Connect()
  {
    return new PDO(SERVIDOR, USUARIO, SENHA);
  }

  public static function Query($sql)
  {
    try {
      $sql = self::Connect()->prepare($sql);
      $sql->execute();
      return true;
    } catch (PDOException $e) {
      var_dump($e);
    }
  }

  public static function QueryAll($sql)
  {
    $sql = self::Connect()->prepare($sql);
    $sql->execute();

    return $sql->fetchAll(PDO::FETCH_CLASS);
  }

  public static function QueryObject($sql)
  {
    $sql = self::Connect()->prepare($sql);
    $sql->execute();

    return $sql->fetchObject();
  }

  function __construct()
  {
    $action = $_REQUEST['action'] ?? null;

    switch ($action) {
      case 'favoritar':
        $id = $_REQUEST['id'];
        $usuario_id = $_SESSION['usuario']->id;
        self::Query("insert into tbl_favoritas values (null, '$id', '$usuario_id')");
        //self é um 'this' obscuro, que chama a classe em si
        break;
      case 'like':
        $id = $_REQUEST['id'];
        $usuario_id = $_SESSION['usuario']->id;
        self::Query("insert into tbl_like values (null, '$usuario_id', '$id')");
        break;
      case 'excluir':
        $id = $_REQUEST['id'];
        $usuario_id = $_SESSION['usuario']->id;
        self::Query("update tbl_publicacoes set status = 'inativo' where id = '$id' and usuario_id = '$usuario_id'");
        break;
      case 'seguir':
        //criador do post
        $usuario_id = $_REQUEST['id'];
        //usuario ativo
        $seguidor_id = $_SESSION['usuario']->id;
        self::Query("insert into tbl_seguindo values (null, '$usuario_id', '$seguidor_id')");
        break;
      case 'registrar':
        $foto = "img/usuariodefault.png";
        $data_nascimento = "CURRENT_TIMESTAMP";
        $POST = (object)$_REQUEST;
        self::Query("insert into tbl_usuario values 
        (null, '$POST->usuario', '$POST->email', '$POST->nome', '$POST->senha', '$foto', $data_nascimento)");
        break;
      case 'exibir':

        break;
      case 'atualizar':
        $POST = (object)$_REQUEST;
        $temfoto = isset($_FILES["foto"]);
        $foto = null;

        if ($temfoto) {
          $photoPath = getcwd() . "./img/";
          $photoName = basename($_FILES["foto"]["name"]);
          if (!file_exists($photoPath)) mkdir($photoPath, 0777, true);
          try {
            move_uploaded_file($_FILES["foto"]["tmp_name"], $photoPath . $photoName);
            $foto = $photoName;
          } catch (Exception $e) {
            echo $e;
          }
        } else if ($_POST["salvar"] == "semfoto") $foto = "img/receitadefault.png";;

        $sql = self::Query($temfoto
          ? "update tbl_publicacoes set foto = '$foto', nome = '$POST->nome', conteudo = '$POST->conteudo' where id = '$POST->id'"
          : "update tbl_publicacoes set nome = '$POST->nome', conteudo = '$POST->conteudo' where id = '$POST->id'");
    }
  }
}

new Connection();
