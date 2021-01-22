<?php

namespace ProbeIPA\Classes\Models;

use DateTime;
use ProbeIPA\Classes\Rest;
use ProbeIPA\Classes\Util;

/**
 * @autor Florian Leimer
 * @version 2020
 */
class Report
{

  /**
   * @var int
   */
  protected $rid = 0;

  /**
   * @var DateTime
   */
  protected $date = null;

  /**
   * @var Project
   */
  protected $project = null;

  /**
   * @var int
   */
  protected $time = 0;

  /**
   * @var string
   */
  protected $description = '';

  /**
   * @var User
   */
  protected $creator = null;


  /**
   * @return int
   */
  public function getRid(): int
  {
    return $this->rid;
  }

  /**
   * @param int $rid
   */
  public function setRid(int $rid): void
  {
    $this->rid = $rid;
  }

  /**
   * @return DateTime
   */
  public function getDate(): DateTime
  {
    return $this->date;
  }

  /**
   * @param DateTime $date
   */
  public function setDate(DateTime $date): void
  {
    $this->date = $date;
  }

  /**
   * @return Project
   */
  public function getProject(): Project
  {
    return $this->project;
  }

  /**
   * @param Project $project
   */
  public function setProject(Project $project): void
  {
    $this->project = $project;
  }

  /**
   * @return int
   */
  public function getTime(): int
  {
    return $this->time;
  }

  /**
   * @param int $time
   */
  public function setTime(int $time): void
  {
    $this->time = $time;
  }

  /**
   * @return string
   */
  public function getDescription(): string
  {
    return $this->description;
  }

  /**
   * @param string $description
   */
  public function setDescription(string $description): void
  {
    $this->description = $description;
  }

  /**
   * @return User
   */
  public function getCreator(): User
  {
    return $this->creator;
  }

  /**
   * @param User $creator
   */
  public function setCreator(User $creator): void
  {
    $this->creator = $creator;
  }


  /**
   * creates a report from an array
   * @param array $data
   * @param bool $validate should the input be validated or not
   * @return Report
   */
  public static function createFromArray(array $data, $validate = true)
  {
    $report = new Report();

    $report->rid = $data['rid'] ?? 0;
    $report->date = $data['date'] ?? null;
    $report->project = $data['project'] ?? null;
    $report->time = $data['time'] ?? 0;
    $report->description = $data['description'] ?? '';
    $report->creator = $data['creator'] ?? null;

    if ($validate)
      $report->validate();

    return $report;
  }

  /**
   * Function for validation of the input for a report
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
