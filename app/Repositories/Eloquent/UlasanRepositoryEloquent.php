<?php

namespace App\Repositories\Eloquent;

use App\Models\Kategori;
use App\Models\UlasanBuku;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UlasanRepository;
use App\Repositories\KategoriRepository;
use App\Repositories\Eloquent\BaseRepositoryEloquent;

class UlasanRepositoryEloquent extends BaseRepositoryEloquent implements UlasanRepository
{
	
	public function __construct(UlasanBuku $model)
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
    
}
