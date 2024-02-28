<?php

namespace App\Repositories\Eloquent;

use App\Models\KoleksiBuku;
use Illuminate\Support\Facades\Auth;
use App\Repositories\KoleksiRepository;
use App\Repositories\Eloquent\BaseRepositoryEloquent;

class KoleksiRepositoryEloquent extends BaseRepositoryEloquent implements KoleksiRepository
{
	
	public function __construct(KoleksiBuku $model)
	{
		$this->model = $model;
	}

	public function checkIfExist($buku_id){
		$data = $this->model->query();

		$data->where('user_id',Auth::user()->id);
		$data->where('buku_id',$buku_id);
		$data = $data->exists();		

		return $data;

	}

	public function findWhere($where){
		return $this->model->where($where)->first();
	}
    
}
