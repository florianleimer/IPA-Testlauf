<?php

namespace ProbeIPA\Classes\Repositories;

use ProbeIPA\Classes\DB;
use ProbeIPA\Classes\Models;

/**
 * @autor Florian Leimer
 * @version 2021
 */
class UserRepository implements BaseRepository
{

  /**
   * @var DB
   */
  protected $db;

  public function __construct()
  {
    $this->db = new DB('localhost', 'probeipa', 'root', '');
  }

  /**
   * @param $user Models\User
   * @return Models\User
   */
  public function save($user)
  {
    if ($user->getUid() > 0) {
      return $this->update($user);
    } else {
      return $this->insert($user);
    }
  }

  /**
   * @param $user Models\User
   * @return Models\User
   */
  public function update($user)
  {
    $data = [
      $user->getName(),
      $user->getInitials(),
      $user->getPassword(),
      $user->isActive(),
      $user->getStatus(),
      $user->getUid(),
    ];

    $this->db->queryPrepared('
                UPDATE user
                SET name = ?, initials = ?, password = ?, active = ?, status = ?
                WHERE uid = ?
            ', $data);

    return $user;
  }

  /**
   * @param $user Models\User
   * @return Models\User
   */
  public function insert($user)
  {
    $data = [
      $user->getName(),
      $user->getInitials(),
      $user->getPassword(),
      $user->isActive(),
      $user->getStatus(),
    ];

    $insertedId = $this->db->queryPrepared('
                INSERT INTO user (name, initials, password, active, status)
                VALUES (?, ?, ?, ?, ?)
            ', $data);
    $user->setUid($insertedId);

    return $user;
  }

  public function delete(int $id)
  {
    $this->db->queryPrepared('DELETE FROM user WHERE uid = ?', [$id]);
  }

  /**
   * @return array<Models\User>
   */
  public function findAll()
  {
    $results = [];

    $statement = $this->db->select('SELECT * FROM user ORDER BY name');
    while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
      $results[] = Models\User::createFromArray($row, false);
    }

    return $results;
  }

  /**
   * @param int $uid ID to exclude from results
   * @return array<Models\User>
   */
  public function findAllExcluded($uid)
  {
    $results = [];

    $statement = $this->db->selectPrepared('SELECT * FROM user WHERE uid <> ? ORDER BY name', [$uid]);
    while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
      $results[] = Models\User::createFromArray($row, false);
    }

    return $results;
  }

  /**
   * @param int $id
   * @return Models\User
   */
  public function findByID(int $id)
  {
    $statement = $this->db->selectPrepared('SELECT * FROM user WHERE uid = ? LIMIT 1', [$id]);
    return ($result = $statement->fetch(\PDO::FETCH_ASSOC)) ? Models\User::createFromArray($result, false) : null;
  }

  /**
   * @param string $username
   * @return Models\User
   */
  public function findByUsername(string $username)
  {
    $statement = $this->db->selectPrepared('SELECT * FROM user WHERE initials = ? LIMIT 1', [$username]);
    return ($result = $statement->fetch(\PDO::FETCH_ASSOC)) ? Models\User::createFromArray($result, false) : null;
  }

}
