<?php

namespace ProbeIPA\Classes\Models;

use ProbeIPA\Classes\Repositories;
use ProbeIPA\Classes\Rest;
use ProbeIPA\Classes\Util;

/**
 * @autor Florian Leimer
 * @version 2020
 */
class Project implements \JsonSerializable
{

  const STATUS_OPEN = 'open';
  const STATUS_COMPLETED = 'completed';
  const STATUS_SUPPORT = 'support';

  /**
   * @var int
   */
  protected $pid = 0;

  /**
   * @var string
   */
  protected $name = '';

  /**
   * @var Customer
   */
  protected $customer = null;

  /**
   * @var \DateTime
   */
  protected $startDate = null;

  /**
   * @var string
   */
  protected $status = '';

  /**
   * @var int
   */
  protected $volume = 0;

  /**
   * @var User
   */
  protected $projectManager = null;

  /**
   * @var string
   */
  protected $comments = '';

  /**
   * creates a project from an array
   * @param array $data
   * @param bool $validate should the input be validated or not
   * @return Project
   */
  public static function createFromArray(array $data, $validate = true)
  {
    $project = new Project();

    $project->setPid($data['pid'] ?? 0);
    $project->setName($data['name'] ?? '');
    $project->setCustomer($data['customer'] ?? null);
    $project->setStartDate($data['startDate'] ?? $data['start_date'] ?? null);
    $project->setStatus($data['status'] ?? '');
    $project->setVolume($data['volume'] ?? 0);
    $project->setProjectManager($data['projectManager'] ?? $data['project_manager'] ?? null);
    $project->setComments($data['comments'] ?? '');

    if ($validate)
      $project->validate();

    return $project;
  }

  /**
   * Function for validation of the input for a project
   */
  private function validate()
  {
    // TODO: Validation

    $status = true;
    $hasError = [
      'name' => false,
      'customer' => false,
      'startDate' => false,
      'status' => false,
      'volume' => false,
      'projectManager' => false,
      'comments' => false,
    ];

    if (!Util::CheckName($this->name)) {
      $hasError['name'] = true;
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
  public function getPid(): int
  {
    return $this->pid;
  }

  /**
   * @param int|string $pid
   */
  public function setPid($pid): void
  {
    $pid = Util::filterInteger($pid);
    if ($pid) $this->pid = $pid;
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
   * @return Customer|null
   */
  public function getCustomer()
  {
    return $this->customer;
  }

  /**
   * @param Customer|int|string $customer
   */
  public function setCustomer($customer): void
  {
    if ($customer instanceof Customer) {
      $this->customer = $customer;
    } elseif ($id = Util::filterInteger($customer)) {
      $customerRepository = new Repositories\CustomerRepository();
      $this->customer = $customerRepository->findByID($id);
    }
  }

  /**
   * @return \DateTime|null
   */
  public function getStartDate()
  {
    return $this->startDate;
  }

  /**
   * @param \DateTime|int|string $startDate
   */
  public function setStartDate($startDate): void
  {
    if ($startDate instanceof \DateTime) {
      $this->startDate = $startDate;
    } elseif ($time = Util::filterInteger($startDate)) {
      $this->startDate = \DateTime::createFromFormat('U', $time);
    } elseif ($startDate = \DateTime::createFromFormat('Y-m-d', $startDate)) {
      $this->startDate = $startDate;
    }
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
   * @return int
   */
  public function getVolume(): int
  {
    return $this->volume;
  }

  /**
   * @param int|string $volume
   */
  public function setVolume($volume): void
  {
    $volume = Util::filterInteger($volume);
    if ($volume) $this->volume = $volume;
  }

  /**
   * @return User|null
   */
  public function getProjectManager()
  {
    return $this->projectManager;
  }

  /**
   * @param User|int|string $projectManager
   */
  public function setProjectManager($projectManager): void
  {
    if ($projectManager instanceof User) {
      $this->projectManager = $projectManager;
    } elseif ($id = Util::filterInteger($projectManager)) {
      $userRepository = new Repositories\UserRepository();
      $this->projectManager = $userRepository->findByID($id);
    }
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
      'pid' => $this->getPid(),
      'name' => $this->getName(),
      'customer' => $this->getCustomer(),
      'startDate' => (!is_null($this->getStartDate())) ? $this->getStartDate()->format('Y-m-d') : null,
      'status' => $this->getStatus(),
      'volume' => $this->getVolume(),
      'projectManager' => $this->getProjectManager(),
      'comments' => $this->getComments(),
    ];
  }
}
