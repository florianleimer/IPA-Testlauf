<?php

namespace ProbeIPA\Classes\Repositories;

use ProbeIPA\Classes\DB;
use ProbeIPA\Classes\Models;

/**
 * @autor Florian Leimer
 * @version 2021
 */
class ReportRepository implements BaseRepository
{

  /**
   * @var DB
   */
  protected $db;

  public function __construct()
  {
    $this->db = new DB('localhost', 'probeipa', 'root', '');
  }

  /**
   * @param $report Models\Report
   * @return Models\Report
   */
  public function save($report)
  {
    if ($report->getRid() > 0) {
      return $this->update($report);
    } else {
      return $this->insert($report);
    }
  }

  /**
   * @param $report Models\Report
   * @return Models\Report
   */
  public function update($report)
  {
    $data = [
      (!is_null($report->getDate())) ? $report->getDate()->getTimestamp() : 0,
      (!is_null($report->getProject())) ? $report->getProject()->getPid() : 0,
      $report->getTime(),
      $report->getDescription(),
      (!is_null($report->getCreator())) ? $report->getCreator()->getUid() : 0,
      $report->getRid(),
    ];

    $this->db->queryPrepared('
                UPDATE report
                SET date = ?, project = ?, time = ?, description = ?, creator = ?
                WHERE rid = ?
            ', $data);

    return $report;
  }

  /**
   * @param $report Models\Report
   * @return Models\Report
   */
  public function insert($report)
  {
    $data = [
      (!is_null($report->getDate())) ? $report->getDate()->getTimestamp() : 0,
      (!is_null($report->getProject())) ? $report->getProject()->getPid() : 0,
      $report->getTime(),
      $report->getDescription(),
      (!is_null($report->getCreator())) ? $report->getCreator()->getUid() : 0,
    ];

    $insertedId = $this->db->queryPrepared('
                INSERT INTO report (date, project, time, description, creator)
                VALUES (?, ?, ?, ?, ?)
            ', $data);
    $report->setRid($insertedId);

    return $report;
  }

  public function delete(int $id)
  {
    $this->db->queryPrepared('DELETE FROM report WHERE rid = ?', [$id]);
  }

  /**
   * @return array<Models\Report>
   */
  public function findAll()
  {
    $results = [];

    $statement = $this->db->select('SELECT * FROM report ORDER BY date');
    while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
      $results[] = Models\Report::createFromArray($row, false);
    }

    return $results;
  }

  /**
   * @param int $creator
   * @return array<Models\Report>
   */
  public function findAllForCreator($creator)
  {
    $results = [];

    $statement = $this->db->selectPrepared('SELECT * FROM report WHERE creator = ? ORDER BY date', [$creator]);
    while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
      $results[] = Models\Report::createFromArray($row, false);
    }

    return $results;
  }

  /**
   * @param int $id
   * @return Models\Report
   */
  public function findByID(int $id)
  {
    $statement = $this->db->selectPrepared('SELECT * FROM report WHERE rid = ? LIMIT 1', [$id]);
    return ($result = $statement->fetch(\PDO::FETCH_ASSOC)) ? Models\Report::createFromArray($result, false) : null;
  }

}
