<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Contracts\Repositories\AdminRepositoryInterface;
use App\Models\Admin;

class AdminController extends BaseAdminController
{
    private $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        parent::__construct();
        $this->adminRepository = $adminRepository;
    }

	public function index(Request $request)
	{
        $admins = $this->adminRepository->all($request);
        return response()->json($admins);
	}

	public function admin($id = 0)
	{
		$admin = $this->adminRepository->getOne($id);
        return response()->json($admin);
	}

	public function create(AdminRequest $request)
	{
        $data = $request->validated();
    	$admin = $this->adminRepository->create($data);
        return response()->json($admin);
	}

    public function update(AdminRequest $request, $id = 0)
    {
        $data = $request->validated();
        $admin = $this->adminRepository->update($id, $data);
        return response()->json($admin);
    }

    public function delete($id = 0)
    {
        $this->adminRepository->delete($id);
        return response()->json([
            'message' => $id,
        ]);
    }
}
