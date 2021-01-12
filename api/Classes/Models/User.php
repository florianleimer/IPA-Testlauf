<?php

namespace ProbeIPA\Classes\Models;

use ProbeIPA\Classes\Rest;
use ProbeIPA\Classes\Util;

/**
 * @autor Florian Leimer
 * @version 2020
 */
class User implements \JsonSerializable
{

  const STATUS_ADMINISTRATOR = 'admin';
  const STATUS_USER = 'user';

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
  protected $status = self::STATUS_USER;

  /**
   * creates a user from an array
   * @param array $data
   * @param bool $validate should the input be validated or not
   * @return User
   */
  public static function createFromArray(array $data, $validate = true)
  {
    if ($validate)
      self::validate($data);

    $user = new User();

    $user->setUid($data['uid'] ?? 0);
    $user->setName($data['name'] ?? '');
    $user->setInitials($data['initials'] ?? '');
    $user->setPassword($data['password'] ?? '');
    $user->setActive($data['active'] ?? false);
    $user->setStatus($data['status'] ?? self::STATUS_USER);

    return $user;
  }

  /**
   * Function for validation of the input for a user
   * @param array $data
   */
  private static function validate(array $data)
  {
    $status = true;
    $hasError = [
      'name' => false,
      'initials' => false,
      'password' => false,
      'active' => false,
      'status' => false,
    ];

    if (!Util::CheckName($data['name'])) {
      $hasError['name'] = true;
      $status = false;
    }
    if (!Util::CheckEmpty($data['initials'], 2)) {
      $hasError['initials'] = true;
      $status = false;
    }
    if (!Util::CheckPassword($data['password'])) {
      $hasError['password'] = true;
      $status = false;
    }
    if (!Util::CheckInArray($data['status'], [self::STATUS_ADMINISTRATOR, self::STATUS_USER])) {
      $hasError['status'] = true;
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
  public function getUid(): int
  {
    return $this->uid;
  }

  /**
   * @param int|string $uid
   */
  public function setUid($uid): void
  {
    $uid = Util::filterInteger($uid);
    if ($uid) $this->uid = $uid;
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
    if (is_null(password_get_info($password)['algo'])) {
      $password = password_hash($password, PASSWORD_DEFAULT);
    }
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
   * @param mixed $active
   */
  public function setActive($active): void
  {
    $this->active = boolval($active);
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

  public function jsonSerialize()
  {
    return [
      'uid' => $this->getUid(),
      'name' => $this->getName(),
      'initials' => $this->getInitials(),
      'password' => '**********', // $this->getPassword(),
      'active' => $this->isActive(),
      'status' => $this->getStatus(),
    ];
  }
}
