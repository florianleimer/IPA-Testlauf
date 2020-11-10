<?php

namespace ProbeIPA\Classes\Models;

use ProbeIPA\Classes\Rest;
use ProbeIPA\Classes\Util;

/**
 * @autor Florian Leimer
 * @version 2020
 */
class User
{

  /**
   * @var int
   */
  protected $uid = 0;

  /**
   * @var string
   */
  protected $name = '';

  /**
   * @var string
   */
  protected $initials = '';

  /**
   * @var string
   */
  protected $password = '';

  /**
   * @var bool
   */
  protected $active = false;

  /**
   * @var string
   */
  protected $status = '';


  /**
   * @return int
   */
  public function getUid(): int
  {
    return $this->uid;
  }

  /**
   * @param int $uid
   */
  public function setUid(int $uid): void
  {
    $this->uid = $uid;
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
  public function getInitials(): string
  {
    return $this->initials;
  }

  /**
   * @param string $initials
   */
  public function setInitials(string $initials): void
  {
    $this->initials = $initials;
  }

  /**
   * @return string
   */
  public function getPassword(): string
  {
    return $this->password;
  }

  /**
   * @param string $password
   */
  public function setPassword(string $password): void
  {
    $this->password = $password;
  }

  /**
   * @return bool
   */
  public function isActive(): bool
  {
    return $this->active;
  }

  /**
   * @param bool $active
   */
  public function setActive(bool $active): void
  {
    $this->active = $active;
  }

  /**
   * @return string
   */
  public function getStatus(): string
  {
    return $this->status;
  }

  /**
   * @param string $status
   */
  public function setStatus(string $status): void
  {
    $this->status = $status;
  }


  /**
   * creates a user from an array
   * @param array $data
   * @param bool $validate should the input be validated or not
   * @return User
   */
  public static function createFromArray(array $data, $validate = true)
  {
    $user = new User();

    $user->uid = $data['uid'] ?? 0;
    $user->name = $data['name'] ?? '';
    $user->initials = $data['initials'] ?? '';
    $user->password = $data['password'] ?? '';
    $user->active = $data['active'] ?? false;
    $user->status = $data['status'] ?? '';

    if ($validate)
      $user->validate();

    return $user;
  }

  /**
   * Function for validation of the input for a user
   */
  private function validate()
  {
    // TODO: Validation

    $status = true;
    $inputfields = ['land' => true];

    if (!Util::CheckName($this->land)) {
      $inputfields['land'] = false;
      $status = false;
    }

    if (!$status) {
      echo Rest::encodeJson($inputfields);
      Rest::setHttpHeaders(420, true);
    }
  }

}
