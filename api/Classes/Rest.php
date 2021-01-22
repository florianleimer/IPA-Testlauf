<?php
namespace ProbeIPA\Classes;

/**
 * @autor Florian Leimer
 * @version 2020
 */
class Rest
{

  private static $httpVersion = "HTTP/1.1";

  /**
   * Sets the httpHeaders for the response and exits if required
   *
   * @param int $statusCode httpStatusCode to set
   * @param bool $exit if true the program exits
   * @param string $contentType sets the content-type, default is 'application/json'
   * @param string $charset sets the charset, default is 'UTF-8'
   */
  public static function setHttpHeaders($statusCode, $exit = false, $contentType = "application/json", $charset = "UTF-8")
  {
    $statusMessage = self::getHttpStatusMessage($statusCode);
    header(self::$httpVersion . " " . $statusCode . " " . $statusMessage);
    header("Content-Type:$contentType; charset=$charset");
    if ($exit) exit();
  }

  /**
   * Returns the message for an statusCode
   *
   * @param $statusCode int the statusCode you would like the message for
   * @return string the selected httpStatusMessage
   */
  public static function getHttpStatusMessage($statusCode)
  {
    $httpStatus = [
      100 => 'Continue',
      101 => 'Switching Protocols',
      200 => 'OK',
      201 => 'Created',
      202 => 'Accepted',
      203 => 'Non-Authoritative Information',
      204 => 'No Content',
      205 => 'Reset Content',
      206 => 'Partial Content',
      300 => 'Multiple Choices',
      301 => 'Moved Permanently',
      302 => 'Found',
      303 => 'See Other',
      304 => 'Not Modified',
      305 => 'Use Proxy',
      306 => '(Unused)',
      307 => 'Temporary Redirect',
      400 => 'Bad Request',
      401 => 'Unauthorized',
      402 => 'Payment Required',
      403 => 'Forbidden',
      404 => 'Not Found',
      405 => 'Method Not Allowed',
      406 => 'Management Not Allowed',
      407 => 'Proxy Authentication Required',
      408 => 'Request Timeout',
      409 => 'Conflict',
      410 => 'Gone',
      411 => 'Length Required',
      412 => 'Precondition Failed',
      413 => 'Request Entity Too Large',
      414 => 'Request-URI Too Long',
      415 => 'Unsupported Media Type',
      416 => 'Requested Range Not Satisfiable',
      417 => 'Expectation Failed',
      420 => 'Input Validation Failed',
      500 => 'Internal Server Error',
      501 => 'Not Implemented',
      502 => 'Bad Gateway',
      503 => 'Service Unavailable',
      504 => 'Gateway Timeout',
      505 => 'HTTP Version Not Supported'];
    return ($httpStatus[$statusCode]) ? $httpStatus[$statusCode] : $httpStatus[500];
  }

  /**
   * Returns a string with the encoded data
   *
   * @param $responseData mixed the data to convert into json
   * @return false|string encoded data
   */
  public static function encodeJson($responseData)
  {
    return json_encode($responseData);
  }

}

?>
