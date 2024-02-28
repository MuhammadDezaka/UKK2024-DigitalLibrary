<?php 

namespace App\Repositories\Eloquent; 

use App\Models\AdminUser;
use App\Models\User;
use App\Repositories\AdminUserRepository; 
use App\Repositories\Eloquent\BaseRepositoryEloquent; 

class AdminUserRepositoryEloquent extends BaseRepositoryEloquent implements AdminUserRepository
{
	/**
	 * Construct 
	 * 
	 * @param AdminUser $model 
	 */
	public function __construct(User $model)
	{
		$this->model = $model; 
	}

    /**
     * Get CMS Table
     */
    public function getCmsTables($request)
    {
        $datatables = $this->model->select([
            'user.id', 
            'user.nama_lengkap', 
            'user.email', 
            'user.username', 
        ]); 

        $datatables = $datatables->role(['peminjam','admin','petugas']);

        $datatables = $datatables->with(['roles' => function ($query) {
            $query->select(['id', 'name']); 
        }]);

        if (isset($request['keyword'])) {
            $datatables = $datatables->where(function ($query) use ($request) {
                $query->where('nama_lengkap', 'like', '%'.$request['keyword'].'%'); 
                $query->orWhere('email', 'like', '%'.$request['keyword'].'%'); 
                $query->orWhere('username', 'like', '%'.$request['keyword'].'%'); 
            }); 
        }

        $datatables = $datatables->orderBy('id', 'DESC'); 
        $datatables = $datatables->paginate(10); 

        return $datatables; 
    }


    /**
	 * Create
	 *
	 * @param  array 	
	 * @return array
	 */
	public function create($data)
	{
		$adminUser = $this->model->create($data); 
        $adminUser->assignRole($data['role']); 

        return $adminUser->toArray(); 
	}
}