<?php

namespace ProbeIPA\Classes\Models;

use ProbeIPA\Classes\Rest;
use ProbeIPA\Classes\Util;

/**
 * @autor Florian Leimer
 * @version 2020
 */
class Customer implements \JsonSerializable
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
    $customer->setClientNumber($data['clientNumber'] ?? $data['client_number'] ?? '');
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
    $hasError = [
      'name' => false,
      'clientNumber' => false,
      'address' => false,
      'comments' => false,
    ];

    if (!Util::CheckName($this->name)) {
      $hasError['name'] = true;
      $status = false;
    }
    if (!Util::CheckEmpty($this->clientNumber)) {
      $hasError['clientNumber'] = true;
      $status = false;
    }

    if (!$status) {
      echo Rest::encodeJson($hasError);
      Rest::setHttpHeaders(420, true);
    }
  }

  /**
   * @return int
   */
  public function getCid(): int
  {
    return $this->cid;
  }

  /**
   * @param int|string $cid
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

  public function jsonSerialize()
  {
    return [
      'cid' => $this->getCid(),
      'name' => $this->getName(),
      'clientNumber' => $this->getClientNumber(),
      'address' => $this->getAddress(),
      'comments' => $this->getComments(),
    ];
  }
}
