<?php
namespace ProbeIPA\Classes;

use Exception;

/**
 * @autor Florian Leimer
 * @version 2021
 *
 * This class contains functions that implement the application logic.
 */
class Controller
{

  /**
   * switch to look for which management the user is using
   *
   * @param string $management the requested management
   * @param string $method the requested method
   * @param array $data the input data
   * @return mixed data to output
   * @throws Exception
   */
  public static function dispatch($management, $method, $data)
  {

    if (!Authentication::hasAccess($management))
      Rest::setHttpHeaders(403, true);

    switch ($management) {
      case 'customer':
        return self::customer($method, $data);
      case 'project':
        return self::project($method, $data);
      case 'report':
        return self::report($method, $data);
      case 'user':
        return self::user($method, $data);
      case 'profile':
        return self::profile($method, $data);
      case 'login':
        return self::login($method, $data);
      default:
        Rest::setHttpHeaders(406, true);
    }
  }

  /**
   * Contains the application logic for customer management
   *
   * @param string $method the requested method
   * @param array $data the input data
   * @return mixed data to output
   * @throws Exception
   */
  private static function customer($method, $data)
  {
    $customerRepository = new Repositories\CustomerRepository();

    switch ($method) {
      case 'POST':
        $customer = Models\Customer::createFromArray((array)$data->customer);
        $customerRepository->save($customer);
        break;
      case 'GET':
        if (isset($data->cid) && !empty($data->cid)) {
          return $customerRepository->findByID($data->cid);
        } else {
          return $customerRepository->findAll();
        }
      case 'DELETE':
        $customerRepository->delete($data->cid);
        break;
    }
  }

  /**
   * Contains the application logic for project management
   *
   * @param string $method the requested method
   * @param array $data the input data
   * @return mixed data to output
   * @throws Exception
   */
  private static function project($method, $data)
  {
    $projectRepository = new Repositories\ProjectRepository();

    switch ($method) {
      case 'POST':
        $project = Models\Project::createFromArray((array)$data->project);
        $projectRepository->save($project);
        break;
      case 'GET':
        if (isset($data->pid) && !empty($data->pid)) {
          return $projectRepository->findByID($data->pid);
        } else {
          return $projectRepository->findAll();
        }
      case 'DELETE':
        $projectRepository->delete($data->pid);
        break;
    }
  }

  /**
   * Contains the application logic for report management
   *
   * @param string $method the requested method
   * @param array $data the input data
   * @return mixed data to output
   * @throws Exception
   */
  private static function report($method, $data)
  {
    $reportRepository = new Repositories\ReportRepository();

    switch ($method) {
      case 'POST':
        $report = Models\Report::createFromArray((array)$data->report);
        $reportRepository->save($report);
        break;
      case 'GET':
        if (isset($data->rid) && !empty($data->rid)) {
          return $reportRepository->findByID($data->rid);
        } else {
          return $reportRepository->findAllForCreator(Authentication::$currentUser->getUid());
        }
      case 'DELETE':
        $reportRepository->delete($data->rid);
        break;
    }
  }

  /**
   * Contains the application logic for user management
   *
   * @param string $method the requested method
   * @param array $data the input data
   * @return mixed data to output
   * @throws Exception
   */
  private static function user($method, $data)
  {
    $userRepository = new Repositories\UserRepository();

    switch ($method) {
      case 'POST':
        // TODO: Update without setting password
        $user = Models\User::createFromArray((array)$data->user);
        $userRepository->save($user);
        break;
      case 'GET':
        if (isset($data->uid) && !empty($data->uid)) {
          return $userRepository->findByID($data->uid);
        } else {
          if (isset($data->getAll) && $data->getAll) {
            return $userRepository->findAll();
          } else {
            return $userRepository->findAllExcluded(Authentication::$currentUser->getUid());
          }
        }
      case 'DELETE':
        $userRepository->delete($data->uid);
        break;
    }
  }

  /**
   * Contains the application logic for users profile
   *
   * @param string $method the requested method
   * @param array $data the input data
   * @return mixed data to output
   * @throws Exception
   */
  private static function profile($method, $data)
  {
    $userRepository = new Repositories\UserRepository();

    switch ($method) {
      case 'POST':
        $user = Authentication::$currentUser;
        $userData = (array)$data->user;

        $status = true;
        $hasError = [
          'name' => false,
          'initials' => false,
          'password' => false,
          'passwordRepeat' => false,
        ];
        if (Util::CheckName($userData['name'])) {
          $user->setName($userData['name']);
        } else {
          $hasError['name'] = true;
          $status = false;
        }
        if (Util::CheckEmpty($userData['initials'], 2)) {
          $user->setInitials($userData['initials']); // TODO: Check that initials are unique
        } else {
          $hasError['initials'] = true;
          $status = false;
        }
        if (Util::CheckEmpty($userData['password']) || Util::CheckEmpty($userData['passwordRepeat'])) {
          if (!Util::CheckPassword($userData['password'])) {
            $hasError['password'] = $hasError['passwordRepeat'] = true;
            $status = false;
          }
          if ($userData['password'] === $userData['passwordRepeat']) {
            $user->setPassword($userData['password']);
          } else {
            $hasError['passwordRepeat'] = true;
            $status = false;
          }
        }

        if (!$status) {
          echo Rest::encodeJson($hasError);
          Rest::setHttpHeaders(420, true);
        }

        $user = Authentication::$currentUser;
        $userRepository->save($user);
        break;
      case 'GET':
        return $userRepository->findByID(Authentication::$currentUser->getUid());
    }
  }

  /**
   * Function to login User
   *
   * @param string $method the requested method
   * @param array $data the input data
   * @return array data to output
   * @throws Exception
   */
  private static function login($method, $data)
  {
    switch ($method) {
      case 'POST':
        return Authentication::createToken($data->user->username, $data->user->password);
      case 'GET':
        return Authentication::validateToken($data->token);
    }
  }

}

?>
