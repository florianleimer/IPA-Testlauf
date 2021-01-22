<?php

namespace ProbeIPA\Classes\Repositories;

use ProbeIPA\Classes\DB;
use ProbeIPA\Classes\Models;

/**
 * @autor Florian Leimer
 * @version 2020
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
      return $this->save($report);
    }
  }

  /**
   * @param $report Models\Report
   * @return Models\Report
   */
  public function insert($report)
  {
    $data = [
      $report->getDate()->getTimestamp(),
      $report->getProject()->getPid(),
      $report->getTime(),
      $report->getDescription(),
      $report->getCreator()->getUid(),
    ];

    $insertedId = $this->db->queryPrepared('
                INSERT INTO report (date, project, time, description, creator)
                VALUES (?, ?, ?, ?, ?)
            ', $data);
    $report->setRid($insertedId);

    return $report;
  }

  /**
   * @param $report Models\Report
   * @return Models\Report
   */
  public function update($report)
  {
    $data = [
      $report->getDate()->getTimestamp(),
      $report->getProject()->getPid(),
      $report->getTime(),
      $report->getDescription(),
      $report->getCreator()->getUid(),
      $report->getRid(),
    ];

    $this->db->queryPrepared('
                UPDATE report
                SET date = ?, project = ?, time = ?, description = ?, creator = ?
                WHERE rid = ?
            ', $data);

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
    while ($row = $statement->fetchObject(Models\Report::class)) {
      $results = $row;
    }

    return $results;
  }

  /**
   * @param int $id
   * @return Models\Report
   */
  public function findByID(int $id)
  {
    return $this->queryPrepared('SELECT * FROM report WHERE rid = ? LIMIT 1', [$id])->fetchObject(Models\Report::class);
  }

}
