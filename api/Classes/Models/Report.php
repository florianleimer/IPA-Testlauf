<?php

namespace ProbeIPA\Classes\Models;

use ProbeIPA\Classes\Repositories;
use ProbeIPA\Classes\Rest;
use ProbeIPA\Classes\Util;

/**
 * @autor Florian Leimer
 * @version 2020
 */
class Report implements \JsonSerializable
{

  /**
   * @var int
   */
  protected $rid = 0;

  /**
   * @var \DateTime
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
   * creates a report from an array
   * @param array $data
   * @param bool $validate should the input be validated or not
   * @return Report
   */
  public static function createFromArray(array $data, $validate = true)
  {
    if ($validate)
      self::validate($data);

    $report = new Report();

    $report->setRid($data['rid'] ?? 0);
    $report->setDate($data['date'] ?? null);
    $report->setProject($data['project'] ?? null);
    $report->setTime($data['time'] ?? 0);
    $report->setDescription($data['description'] ?? '');
    $report->setCreator($data['creator'] ?? null); // TODO: set to logged in user

    return $report;
  }

  /**
   * Function for validation of the input for a report
   * @param array $data
   */
  private static function validate(array $data)
  {
    $status = true;
    $hasError = [
      'date' => false,
      'project' => false,
      'time' => false,
      'description' => false,
      'creator' => false,
    ];

    if (!Util::CheckDate($data['date'])) {
      $hasError['date'] = true;
      $status = false;
    }
    if (!Util::CheckID($data['project'])) {
      $hasError['project'] = true;
      $status = false;
    }
    if (!Util::CheckTime($data['time'])) {
      $hasError['time'] = true;
      $status = false;
    }
    if (!Util::CheckEmpty($data['description'], 20)) {
      $hasError['description'] = true;
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
  public function getRid(): int
  {
    return $this->rid;
  }

  /**
   * @param int|string $rid
   */
  public function setRid($rid): void
  {
    $rid = Util::filterInteger($rid);
    if ($rid) $this->rid = $rid;
  }

  /**
   * @return \DateTime|null
   */
  public function getDate()
  {
    return $this->date;
  }

  /**
   * @param \DateTime|int|string $date
   */
  public function setDate($date): void
  {
    if ($date instanceof \DateTime) {
      $this->date = $date;
    } elseif ($time = Util::filterInteger($date)) {
      $this->date = \DateTime::createFromFormat('U', $time);
    } elseif ($date = \DateTime::createFromFormat('Y-m-d', $date)) {
      $this->date = $date;
    }
  }

  /**
   * @return Project|null
   */
  public function getProject()
  {
    return $this->project;
  }

  /**
   * @param Project|int|string $project
   */
  public function setProject($project): void
  {
    if ($project instanceof Project) {
      $this->project = $project;
    } elseif ($id = Util::filterInteger($project)) {
      $projectRepository = new Repositories\ProjectRepository();
      $this->project = $projectRepository->findByID($id);
    }
  }

  /**
   * @return int
   */
  public function getTime(): int
  {
    return $this->time;
  }

  /**
   * @return string
   */
  public function getTimeString(): string
  {
    $hours = floor($this->time / 60);
    $minutes = floor($this->time - ($hours*60));
    return str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT);
  }

  /**
   * @param int|string $time
   */
  public function setTime($time): void
  {
    if (is_string($time) && strpos($time, ':')) {
      $times = explode(':', $time);
      $this->time = (($times[0] * 60) + $times[1]);
    } elseif ($time = Util::filterInteger($time)) {
      $this->time = $time;
    }
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
   * @return User|null
   */
  public function getCreator()
  {
    return $this->creator;
  }

  /**
   * @param User|int|string $creator
   */
  public function setCreator($creator): void
  {
    if ($creator instanceof User) {
      $this->creator = $creator;
    } elseif ($id = Util::filterInteger($creator)) {
      $userRepository = new Repositories\UserRepository();
      $this->creator = $userRepository->findByID($id);
    }
  }

  public function jsonSerialize()
  {
    return [
      'rid' => $this->getRid(),
      'date' => (!is_null($this->getDate())) ? $this->getDate()->format('Y-m-d') : null,
      'project' => $this->getProject(),
      'time' => $this->getTimeString(),
      'description' => $this->getDescription(),
      'creator' => $this->getCreator(),
    ];
  }
}
