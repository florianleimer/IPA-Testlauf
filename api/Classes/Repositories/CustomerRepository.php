<?php

namespace ProbeIPA\Classes\Repositories;

use ProbeIPA\Classes\DB;
use ProbeIPA\Classes\Models;

/**
 * @autor Florian Leimer
 * @version 2020
 */
class CustomerRepository implements BaseRepository
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
   * @param $customer Models\Customer
   * @return Models\Customer
   */
  public function save($customer)
  {
    if ($customer->getCid() > 0) {
      return $this->update($customer);
    } else {
      return $this->save($customer);
    }
  }

  /**
   * @param $customer Models\Customer
   * @return Models\Customer
   */
  public function update($customer)
  {
    $data = [
      $customer->getName(),
      $customer->getClientNumber(),
      $customer->getAddress(),
      $customer->getComments(),
      $customer->getCid(),
    ];

    $this->db->queryPrepared('
                UPDATE customer
                SET name = ?, client_number = ?, address = ?, comments = ?
                WHERE cid = ?
            ', $data);

    return $customer;
  }

  /**
   * @param $customer Models\Customer
   * @return Models\Customer
   */
  public function insert($customer)
  {
    $data = [
      $customer->getName(),
      $customer->getClientNumber(),
      $customer->getAddress(),
      $customer->getComments(),
    ];

    $insertedId = $this->db->queryPrepared('
                INSERT INTO customer (name, client_number, address, comments)
                VALUES (?, ?, ?, ?)
            ', $data);
    $customer->setCid($insertedId);

    return $customer;
  }

  public function delete(int $id)
  {
    $this->db->queryPrepared('DELETE FROM customer WHERE cid = ?', [$id]);
  }

  /**
   * @return array<Models\Customer>
   */
  public function findAll()
  {
    $results = [];

    $statement = $this->db->select('SELECT * FROM customer ORDER BY name');
    while ($row = $statement->fetchObject(Models\Customer::class)) {
      $results = $row;
    }

    return $results;
  }

  /**
   * @param int $id
   * @return Models\Customer
   */
  public function findByID(int $id)
  {
    return $this->queryPrepared('SELECT * FROM customer WHERE cid = ? LIMIT 1', [$id])->fetchObject(Models\Customer::class);
  }

}
