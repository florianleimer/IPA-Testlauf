<?php

namespace ProbeIPA\Classes\Repositories;

use ProbeIPA\Classes\DB;
use ProbeIPA\Classes\Models;

/**
 * @autor Florian Leimer
 * @version 2020
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
      return $this->save($user);
    }
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
    while ($row = $statement->fetchObject(Models\User::class)) {
      $results = $row;
    }

    return $results;
  }

  /**
   * @param int $id
   * @return Models\User
   */
  public function findByID(int $id)
  {
    return $this->queryPrepared('SELECT * FROM user WHERE uid = ? LIMIT 1', [$id])->fetchObject(Models\User::class);
  }

  /**
   * @param string $username
   * @return Models\User
   */
  public function findByUsername(string $username)
  {
    return $this->queryPrepared('SELECT * FROM user WHERE initials = ? LIMIT 1', [$username])->fetchObject(Models\User::class);
  }

}
