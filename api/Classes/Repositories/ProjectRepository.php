<?php

namespace ProbeIPA\Classes\Repositories;

use ProbeIPA\Classes\DB;
use ProbeIPA\Classes\Models;

/**
 * @autor Florian Leimer
 * @version 2020
 */
class ProjectRepository implements BaseRepository
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
   * @param $project Models\Project
   * @return Models\Project
   */
  public function save($project)
  {
    if ($project->getPid() > 0) {
      return $this->update($project);
    } else {
      return $this->save($project);
    }
  }

  /**
   * @param $project Models\Project
   * @return Models\Project
   */
  public function insert($project)
  {
    $data = [
      $project->getName(),
      $project->getCustomer()->getCid(),
      $project->getStartDate()->getTimestamp(),
      $project->getStatus(),
      $project->getVolume(),
      $project->getProjectManager()->getUid(),
      $project->getComments(),
    ];

    $insertedId = $this->db->queryPrepared('
                INSERT INTO project (name, customer, start_date, status, volume, project_manager, comments)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ', $data);
    $project->setPid($insertedId);

    return $project;
  }

  /**
   * @param $project Models\Project
   * @return Models\Project
   */
  public function update($project)
  {
    $data = [
      $project->getName(),
      $project->getCustomer()->getCid(),
      $project->getStartDate()->getTimestamp(),
      $project->getStatus(),
      $project->getVolume(),
      $project->getProjectManager()->getUid(),
      $project->getComments(),
      $project->getPid(),
    ];

    $this->db->queryPrepared('
                UPDATE project
                SET name = ?, customer = ?, start_date = ?, status = ?, volume = ?, project_manager = ?, comments = ?
                WHERE pid = ?
            ', $data);

    return $project;
  }

  public function delete(int $id)
  {
    $this->db->queryPrepared('DELETE FROM project WHERE pid = ?', [$id]);
  }

  /**
   * @return array<Models\Project>
   */
  public function findAll()
  {
    $results = [];

    $statement = $this->db->select('SELECT * FROM project ORDER BY name');
    while ($row = $statement->fetchObject(Models\Project::class)) {
      $results = $row;
    }

    return $results;
  }

  /**
   * @param int $id
   * @return Models\Project
   */
  public function findByID(int $id)
  {
    return $this->queryPrepared('SELECT * FROM project WHERE pid = ? LIMIT 1', [$id])->fetchObject(Models\Project::class);
  }

}
