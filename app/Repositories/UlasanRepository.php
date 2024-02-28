<?php

namespace App\Repositories;

interface UlasanRepository
{
    public function create($data);
    public function update($id, $data);
    public function delete($id);
    public function getCmsTableWhere($relasi = null, $where = null,$like = null, $label = null, $collection = null, $sort = null);
    public function find($id);
    public function checkIfExist($buku_id);
}
