<?php

namespace ProbeIPA\Classes\Repositories;

interface BaseRepository
{

  public function save($model);
  public function insert($model);
  public function update($model);

  public function delete(int $id);

  public function findAll();

  public function findByID(int $id);

}
