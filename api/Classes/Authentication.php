<?php

namespace ProbeIPA\Classes;

use Exception;
use ProbeIPA\Classes\Models\User;
use ProbeIPA\Classes\Repositories\UserRepository;
use ReallySimpleJWT\Token;

/**
 * @autor Florian Leimer
 * @version 2020
 */
class Authentication
{

  private const SECRETS = 'd#$lp*EWEaB3';
  private const EXPIRATION = 3600;
  private const ISSUER = 'localhost';

  /**
   * @param string $username user to get token for
   * @param string $password user to get token for
   * @return string|null token for user
   * @throws Exception
   */
  public static function getToken(string $username, string $password)
  {
    $uid = self::authenticateUser($username, $password);
    if ($uid !== null) {
      return Token::create($uid, self::SECRETS, time() + self::EXPIRATION, self::ISSUER);
    }

    return null;
  }

  /**
   * @param string $username
   * @param string $password
   * @return int|null uid of the found user
   * @throws Exception
   */
  private static function authenticateUser(string $username, string $password)
  {
    $userRepository = new UserRepository();
    $user = $userRepository->findByUsername($username);

    $errors = ['username' => false, 'password' => false];
    if (empty($user)) {
      $errors['username'] = true;
      $errors['password'] = true;
    } else if (!password_verify($password, $user->getPassword())) {
      $errors['password'] = true;
    } else {
      return $user->getUid();
    }

    echo Rest::encodeJson($errors);
    Rest::setHttpHeaders(420, true);
  }

  /**
   * @param string $token token to check
   * @return boolean return if check was sucessful
   * @throws Exception
   */
  public static function validateToken(string $token)
  {
    if (empty($token))
      return false;

    $userRepository = new UserRepository();

    $uid = Token::getPayload($token, self::SECRETS)['user_id'];
    $user = $userRepository->findByID($uid);

    return (Token::validate($token, self::SECRETS) && $user != false);
  }
}
