<?php
namespace ProbeIPA\Classes;

/**
 * @autor Florian Leimer
 * @version 2020
 */
class Util
{

  /**
   * Checks if value is empty or has minimal length
   *
   * @param $value string value to check
   * @param int|null $minlength int minimal length of value
   * @return boolean returns if check was successful
   */
  public static function CheckEmpty(string $value, $minlength = null)
  {
    if (empty($value)) return false;
    if (!is_null($minlength) && strlen($value) < $minlength) return false;
    else return true;
  }

  /**
   * Checks if the value is a valid email address
   *
   * @param $value string value to check
   * @param $empty bool tells if the value can be empty
   * @return boolean returns if check was successful
   */
  public static function CheckEmail(string $value, $empty = false)
  {
    $pattern_email = '/^[^@\s<&>]+@([-a-z0-9]+\.)+[a-z]{2,}$/i';
    if ($empty && empty($value)) return true;
    if (preg_match($pattern_email, $value)) return true;
    else return false;
  }

  /**
   * Checks if value is a name (prename, lastname)
   *
   * @param $value string value to check
   * @param $empty bool tells if the value can be empty
   * @return boolean returns if check was successful
   */
  public static function CheckName(string $value, $empty = false)
  {
    $pattern_name = '/^[a-zA-ZäöüÄÖÜ \-]{2,}$/';
    if ($empty && empty($value)) return true;
    if (preg_match($pattern_name, $value)) return true;
    else return false;
  }

  /**
   * Checks if value is a locality
   *
   * @param $value string value to check
   * @param $empty bool tells if the value can be empty
   * @return boolean returns if check was successful
   */
  public static function CheckOrt(string $value, $empty = false)
  {
    $pattern_ort = '/^[a-zA-ZäöüÄÖÜ \-]{2,}$/';
    if ($empty && empty($value)) return true;
    if (empty($value)) return false;
    if (preg_match($pattern_ort, $value)) return true;
    else return false;
  }

  /**
   * Checks if value is a street with house number
   *
   * @param $value string value to check
   * @param $empty bool tells if the value can be empty
   * @return boolean returns if check was successful
   */
  public static function CheckStrasse(string $value, $empty = false)
  {
    $pattern_ort = '/^[a-zA-ZäöüÄÖÜ \-]{2,}\s\d+$/';
    if ($empty && empty($value)) return true;
    if (empty($value)) return false;
    if (preg_match($pattern_ort, $value)) return true;
    else return false;
  }

  /**
   * Checks if value is a phone number
   *
   * @param $value string value to check
   * @param bool $empty string tells if the value can be empty
   * @return boolean returns if check was successful
   */
  public static function CheckPhone(string $value, $empty = false)
  {
    $pattern_phone = '/^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/';
    if ($empty && empty($value)) return true;
    if (preg_match($pattern_phone, $value)) return true;
    else return false;
  }

  /**
   * Checks if value is a time
   *
   * @param $value string value to check
   * @param bool $empty string tells if the value can be empty
   * @return boolean returns if check was successful
   */
  public static function CheckTime(string $value, $empty = false)
  {
    $pattern_time = '/^[0-9]{1,2}\:[0-9]{1,2}$/'; // 00:00
    if ($empty && empty($value)) return true;
    if (preg_match($pattern_time, $value)) return true;
    else return false;
  }

  /**
   * Checks if value is a date
   *
   * @param $value string value to check
   * @param bool $empty string tells if the value can be empty
   * @return boolean returns if check was successful
   */
  public static function CheckDate(string $value, $empty = false)
  {
    $pattern_date = '/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/'; // 2000-01-01
    if ($empty && empty($value)) return true;
    if (preg_match($pattern_date, $value)) return true;
    else return false;
  }

  /**
   * Checks if value is a valid username
   *
   * @param $value string value to check
   * @param bool $empty string tells if the value can be empty
   * @return boolean returns if check was successful
   */
  public static function CheckUsername(string $value, $empty = false)
  {
    $pattern_username = '/^[a-zA-Z0-9.-]{2,}$/';
    if ($empty && empty($value)) return true;
    if (preg_match($pattern_username, $value)) return true;
    else return false;
  }

  /**
   * Checks if value is a valid password
   *
   * @param $value string value to check
   * @param bool $empty string tells if the value can be empty
   * @return boolean returns if check was successful
   */
  public static function CheckPassword(string $value, $empty = false)
  {
    $pattern_password = '/^[a-zA-Z0-9.$!?&]{2,}\S$/';
    if ($empty && empty($value)) return true;
    if (preg_match($pattern_password, $value)) return true;
    else return false;
  }

  /**
   * Checks if value is a possible ID
   *
   * @param string $value value to check
   * @param bool $empty string tells if the value can be empty
   * @return boolean returns if check was successful
   */
  public static function CheckID(string $value, $empty = false)
  {
    if ($empty && empty($value)) return true;
    if (($int = filter_var($value, FILTER_VALIDATE_INT)) === false) return false;
    if ($empty || $int > 0) return true;
    else return false;
  }

  /**
   * Checks if value is a Integer
   *
   * @param string $value value to check
   * @param bool $empty string tells if the value can be empty
   * @return boolean returns if check was successful
   */
  public static function CheckInteger(string $value, $empty = false)
  {
    if ($empty && empty($value)) return true;
    return (filter_var($value, FILTER_VALIDATE_INT) !== false);
  }

  /**
   * @param $value
   * @return int|bool returns integer or false on fail
   */
  public static function filterInteger($value)
  {
    return filter_var($value, FILTER_VALIDATE_INT);
  }
}

?>
