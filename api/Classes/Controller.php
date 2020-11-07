<?php
namespace ProbeIPA\Classes;

use Exception;

/**
 * @autor Florian Leimer
 * @version 2020
 *§
 *  This class contains functions that implement the application logic.
 */
class Controller
{

  /**
   * switch to look for which management the user is using
   *
   * @param string $management the requested management
   * @param string $method the requested method
   * @param array $data the input data
   * @throws Exception
   */
  public static function dispatch($management, $method, $data)
  {

    if ($management != 'login' && (!isset($data->token) || !Authentication::validateToken($data->token)))
      Rest::setHttpHeaders(403, true);

    switch ($management) {
      case 'customer':
        return self::customer($method, $data);
      case 'project':
        return self::project($method, $data);
      case 'report':
        return self::report($method, $data);
      case 'login':
        return self::login($method, $data);
      default:
        Rest::setHttpHeaders(406, true);
    }
  }

  /**
   * Contains the application logic for land management
   *
   * @param string $method the requested method
   * @param array $data the input data
   * @throws Exception
   */
  private static function customer($method, $data)
  {
    $customerRepository = new Repositories\CustomerRepository();

    switch ($method) {
      case 'POST':
        $customer = Models\Customer::createFromArray($data->customer);
        $customerRepository->save($customer);
        break;
      case 'GET':
        return $customerRepository->findAll();
      case 'DELETE':
        $customerRepository->delete($data->cid);
        break;
    }
  }

  /**
   * Contains the application logic for location management
   *
   * @param string $method the requested method
   * @param array $data the input data
   * @throws Exception
   */
  private static function project($method, $data)
  {
    $projectRepository = new Repositories\ProjectRepository();

    switch ($method) {
      case 'POST':
        $project = Models\Project::createFromArray($data->ort);
        $projectRepository->save($project);
        break;
      case 'GET':
        return $projectRepository->findAll();
      case 'DELETE':
        $projectRepository->delete($data->pid);
        break;
    }
  }

  /**
   * Contains the application logic for person management
   *
   * @param string $method the requested method
   * @param array $data the input data
   * @return mixed of type person
   * @throws Exception
   */
  private static function report($method, $data)
  {
    $reportRepository = new Repositories\ReportRepository();

    switch ($method) {
      case 'POST':
        $report = Models\Report::createFromArray($data->report);
        $reportRepository->save($report);
        break;
      case 'GET':
        return $reportRepository->findAll();
      case 'DELETE':
        $reportRepository->delete($data->rid);
        break;
    }
  }

  /**
   * Function to login User
   *
   * @param string $method the requested method
   * @param array $data the input data
   * @return string token
   */
  private static function login($method, $data)
  {
    switch ($method) {
      case 'POST':
        $user = Models\User::createFromArray($data->user);
        return Authentication::getToken($user);
      case 'GET':
        return Authentication::validateToken($data->token);
    }
  }

}

?>
