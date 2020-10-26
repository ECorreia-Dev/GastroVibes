<?php
require_once './config.php';

//Sure.sucumbir() criou este arquivo.

class Connection
{
  static function Connect()
  {
    return new PDO(SERVIDOR, USUARIO, SENHA);
  }

  public static function Query($sql)
  {
    $sql = self::Connect()->prepare($sql);
    $sql->execute();

    return true;
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
}
