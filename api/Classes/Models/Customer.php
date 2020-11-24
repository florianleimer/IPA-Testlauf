<?php

namespace ProbeIPA\Classes\Models;

use ProbeIPA\Classes\Rest;
use ProbeIPA\Classes\Util;

/**
 * @autor Florian Leimer
 * @version 2020
 */
class Customer
{

  /**
   * @var int
   */
  protected $cid = 0;

  /**
   * @var string
   */
  protected $name = '';

  /**
   * @var string
   */
  protected $clientNumber = '';

  /**
   * @var string
   */
  protected $address = '';

  /**
   * @var string
   */
  protected $comments = '';


  /**
   * @return int
   */
  public function getCid(): int
  {
    return $this->cid;
  }

  /**
   * @param mixed $cid
   */
  public function setCid($cid): void
  {
    $cid = Util::filterInteger($cid);
    if ($cid) $this->cid = $cid;
  }

  /**
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * @param string $name
   */
  public function setName(string $name): void
  {
    $this->name = $name;
  }

  /**
   * @return string
   */
  public function getClientNumber(): string
  {
    return $this->clientNumber;
  }

  /**
   * @param string $clientNumber
   */
  public function setClientNumber(string $clientNumber): void
  {
    $this->clientNumber = $clientNumber;
  }

  /**
   * @return string
   */
  public function getAddress(): string
  {
    return $this->address;
  }

  /**
   * @param string $address
   */
  public function setAddress(string $address): void
  {
    $this->address = $address;
  }

  /**
   * @return string
   */
  public function getComments(): string
  {
    return $this->comments;
  }

  /**
   * @param string $comments
   */
  public function setComments(string $comments): void
  {
    $this->comments = $comments;
  }


  /**
   * creates a customer from an array
   * @param array $data
   * @param bool $validate should the input be validated or not
   * @return Customer
   */
  public static function createFromArray(array $data, $validate = true)
  {
    $customer = new Customer();

    $customer->setCid($data['cid'] ?? 0);
    $customer->setName($data['name'] ?? '');
    $customer->setClientNumber($data['clientNumber'] ?? '');
    $customer->setAddress($data['address'] ?? '');
    $customer->setComments($data['comments'] ?? '');

    if ($validate)
      $customer->validate();

    return $customer;
  }

  /**
   * Function for validation of the input for a customer
   */
  private function validate()
  {
    // TODO: Validation

    $status = true;
    $validation = [
      'name' => true,
      'clientNumber' => true,
      'address' => true,
      'comments' => true,
    ];

    if (!Util::CheckName($this->name)) {
      $validation['name'] = false;
      $status = false;
    }

    if (!$status) {
      echo Rest::encodeJson($validation);
      Rest::setHttpHeaders(420, true);
    }
  }

}
