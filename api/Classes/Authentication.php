<?php

namespace ProbeIPA\Classes;

use ProbeIPA\Classes\Models\User;
use ProbeIPA\Classes\Repositories\UserRepository;
use ReallySimpleJWT\Token;

/**
 * @autor Florian Leimer
 * @version 2021
 */
class Authentication
{

  private const SECRETS = 'd#$lp*EWEaB3';
  private const EXPIRATION = 3600;
  private const ISSUER = 'localhost';

  private const PUBLIC_MANAGEMENTS = ['login'];
  private const STATUS_MANAGEMENTS = [
    User::STATUS_USER => ['customer', 'project', 'report', 'profile'],
    User::STATUS_ADMINISTRATOR => ['customer', 'project', 'report', 'user', 'profile'],
  ];

  /**
   * @var User
   */
  public static $currentUser = NULL;


  /**
   * @param string $username user to get token for
   * @param string $password user to get token for
   * @return array user informations
   * @throws \Exception
   */
  public static function createToken(string $username, string $password)
  {
    $user = self::authenticateUser($username, $password);
    if (!is_null($user) && $user instanceof Models\User) {
      return [
        'token' => Token::create($user->getUid(), self::SECRETS, time() + self::EXPIRATION, self::ISSUER),
        'role' => $user->getStatus(),
      ];
    }

    return ['token' => null];
  }

  /**
   * @param string $username
   * @param string $password
   * @return Models\User the found user
   * @throws \Exception
   */
  private static function authenticateUser(string $username, string $password)
  {
    $userRepository = new UserRepository();
    $user = $userRepository->findByUsername($username);

    $errors = ['username' => false, 'password' => false];
    if (is_null($user) || !$user->isActive()) {
      $errors['username'] = true;
      $errors['password'] = true;
    } else if (!password_verify($password, $user->getPassword())) {
      $errors['password'] = true;
    } else {
      return $user;
    }

    echo Rest::encodeJson($errors);
    Rest::setHttpHeaders(420, true);
  }

  /**
   * @param string $token token to check
   * @return array informations to validation and user role
   * @throws \Exception
   */
  public static function validateToken(string $token)
  {
    if (empty($token))
      return ['validated' => false];

    $user = self::getUserByToken($token);

    return [
      'validated' => (Token::validate($token, self::SECRETS) && $user != false),
      'role' => $user->getStatus(),
    ];
  }

  private static function getUserByToken($token)
  {
    $userRepository = new UserRepository();

    $uid = Token::getPayload($token, self::SECRETS)['user_id'];
    return $userRepository->findByID($uid);
  }


  /**
   * Check if client is authorized for this request
   *
   * @param string $management
   * @return bool
   */
  public static function hasAccess($management)
  {
    if (in_array($management, self::PUBLIC_MANAGEMENTS)) return true;

    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) return false;
    $token = $_SERVER['HTTP_AUTHORIZATION'];

    if (!Token::validate($token, self::SECRETS)) return false;
    self::$currentUser = self::getUserByToken($token);

    if (!is_null(self::$currentUser)) {
      $allowedManagements = array_key_exists(self::$currentUser->getStatus(), self::STATUS_MANAGEMENTS) ? self::STATUS_MANAGEMENTS[self::$currentUser->getStatus()] : [];
      if (in_array($management, $allowedManagements)) {
        return true;
      }
    }

    return false;
  }

}
