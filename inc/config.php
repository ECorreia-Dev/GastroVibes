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
    }
  }
}

new Connection();
