<?php

namespace App\Repositories\Eloquent;

use App\Models\AdminRole;
use App\Models\RoleUser;
use App\Repositories\AdminRoleRepository;
use App\Repositories\Eloquent\BaseRepositoryEloquent;
use Illuminate\Support\Facades\DB;

class AdminRoleRepositoryEloquent extends BaseRepositoryEloquent implements AdminRoleRepository
{
	/**
	 * Construct
	 *
	 * @param AdminRole $model
	 */
	public function __construct(RoleUser $model)
	{
		$this->model = $model;
	}

    /**
     * Get CMS Table
     */
    public function getCmsTable($requestData)
    {
        $datatables = $this->model->query();

        $datatables = $datatables->select([
            'roles.id',
            'roles.name',
            DB::raw('(
                SELECT
                COUNT(user.id) as total
                FROM user
                    JOIN model_has_roles
                        ON user.id = model_has_roles.model_id
                    WHERE model_has_roles.role_id = role.id
            ) as total')
        ]);

        if (isset($requestData['keyword']))
            $datatables = $datatables->where('name', 'like', '%'.$requestData['keyword'].'%');

        $datatables = $datatables->paginate(10);
        $datatables = $datatables->toArray();

        return $datatables;
    }
}
