<?php

namespace App\Repositories\Eloquent;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PeminjamanRepository;
use App\Repositories\Eloquent\BaseRepositoryEloquent;

class PeminjamanRepositoryEloquent extends BaseRepositoryEloquent implements PeminjamanRepository
{
	
	public function __construct(Peminjaman $model)
	{
		$this->model = $model;
	}

	public function checkIfExist($buku_id){
		$data = $this->model->query();

		$data->where('user_id',Auth::user()->id);
		$data->where('buku_id',$buku_id);
		$data->whereIn('status',[1,3,4]);
		$data = $data->exists();		

		return $data;

	}
    
}
