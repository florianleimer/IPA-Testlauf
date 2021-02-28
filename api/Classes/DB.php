<?php

namespace ProbeIPA\Classes;

use PDO;
use PDOStatement;

/**
 * @autor Florian Leimer
 * @version 2021
 */
class DB
{

  /**
   * @var PDO
   */
  private $connection;

  /**
   * This method creates a Connection to the DB
   *
   * @param $host
   * @param $database
   * @param $username
   * @param $password
   */
  public function __construct($host, $database, $username, $password)
  {
    $this->connection = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
  }

  /**
   * select gets all data from the DB
   *
   * @param $sql
   * @return PDOStatement
   */
  public function select($sql)
  {
    return $this->connection->query($sql);
  }

  /**
   * Basically is the same as the select method,
   * only with additional data
   *
   * @param $sqlTemplate
   * @param $data
   * @return PDOStatement
   */
  public function selectPrepared($sqlTemplate, $data)
  {
    $preparedStatement = $this->connection->prepare($sqlTemplate);
    $preparedStatement->execute($data);

    return $preparedStatement;
  }

  /**
   * Executes an SQL command without select,
   * i.e. insert, update, delete
   *
   * @param $sql
   * @return string
   */
  public function query($sql)
  {
    $this->connection->exec($sql);
    return $this->connection->lastInsertId();
  }

  /**
   * Basically is the same as the query method,
   * only with additional data
   *
   * @param $sqlTemplate
   * @param $data
   * @return string
   */
  public function queryPrepared($sqlTemplate, $data)
  {
    $preparedStatement = $this->connection->prepare($sqlTemplate);
    $successful = $preparedStatement->execute($data);

    if (!$successful) {
      Rest::setHttpHeaders(430, true);
    }

    return $this->connection->lastInsertId();
  }

}
