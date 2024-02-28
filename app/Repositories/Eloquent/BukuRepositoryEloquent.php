<?php

namespace App\Repositories\Eloquent;

use App\Models\Buku;
use App\Repositories\BukuRepository;
use App\Repositories\Eloquent\BaseRepositoryEloquent;

class BukuRepositoryEloquent extends BaseRepositoryEloquent implements BukuRepository
{

	public function __construct(Buku $model)
	{
		$this->model = $model;
	}

	public function create($data)
	{
		return $this->model->create($data);
	}

	public function update($id, $data)
	{
		$buku = $this->model->findOrFail($id);
		$buku->update($data);

		return $buku;
	}

	public function findWithRelation($id,$relasi = null)
	{
		$data = $this->model->with($relasi)->find($id);

		if (is_null($data)) {
			return null;
		}
		return $data;
	}

	public function findAll($label = null,$like = null,$kategori = null)
	{
		$query = $this->model->query();

		if($like){
            $query = $query->where($label, 'LIKE','%'.$like.'%');
        }

		// if($kategori){
		// 	$query->with('kategori');
		// 	$query->whereHas('kategori', function ($q) use ($kategori) {
		// 		$q->where('kategori_id', $kategori); 
		// 	});
		// }
		return $query->get()->toArray();
	}

}
