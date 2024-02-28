<?php 

namespace App\Repositories; 

interface AdminUserRepository
{
    public function getCmsTables($request);
    public function count($where = null);
    public function create($data);
    public function update($id, $data);
}