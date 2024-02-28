<?php

namespace App\Repositories;

interface BukuRepository
{
    public function create($data);
    public function update($id, $data);
    public function delete($id);
    public function getCmsTableWhere($relasi = null, $where = null,$like = null, $label = null, $collection = null, $sort = null);
    public function find($id);
	public function findAll($label = null,$like = null,$kategori = null);
    public function findWithRelation($id,$relasi = null);
    public function count($where = null);
    
    

}
