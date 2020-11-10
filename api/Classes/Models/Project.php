<?php

namespace ProbeIPA\Classes\Models;

use DateTime;
use ProbeIPA\Classes\Rest;
use ProbeIPA\Classes\Util;

/**
 * @autor Florian Leimer
 * @version 2020
 */
class Project
{

  const STATUS_CONVERSION = 'conversion';
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
   * @var DateTime
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
   * @return int
   */
  public function getPid(): int
  {
    return $this->pid;
  }

  /**
   * @param int $pid
   */
  public function setPid(int $pid): void
  {
    $this->pid = $pid;
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
   * @return Customer
   */
  public function getCustomer(): Customer
  {
    return $this->customer;
  }

  /**
   * @param Customer $customer
   */
  public function setCustomer(Customer $customer): void
  {
    $this->customer = $customer;
  }

  /**
   * @return DateTime
   */
  public function getStartDate(): DateTime
  {
    return $this->startDate;
  }

  /**
   * @param DateTime $startDate
   */
  public function setStartDate(DateTime $startDate): void
  {
    $this->startDate = $startDate;
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
   * @param int $volume
   */
  public function setVolume(int $volume): void
  {
    $this->volume = $volume;
  }

  /**
   * @return User
   */
  public function getProjectManager(): User
  {
    return $this->projectManager;
  }

  /**
   * @param User $projectManager
   */
  public function setProjectManager(User $projectManager): void
  {
    $this->projectManager = $projectManager;
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


  /**
   * creates a project from an array
   * @param array $data
   * @param bool $validate should the input be validated or not
   */
  public static function createFromArray(array $data, $validate = true)
  {
    $project = new Project();

    $project->pid = $data['pid'] ?? 0;
    $project->name = $data['name'] ?? '';
    $project->customer = $data['customer'] ?? null;
    $project->startDate = $data['startDate'] ?? null;
    $project->status = $data['status'] ?? '';
    $project->volume = $data['volume'] ?? 0;
    $project->projectManager = $data['projectManager'] ?? null;
    $project->comments = $data['comments'] ?? '';

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
