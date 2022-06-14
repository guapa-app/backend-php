<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupportMessageRequest;
use App\Contracts\Repositories\SupportMessageRepositoryInterface;
use Carbon\Carbon;

class SupportMessageController extends BaseAdminController
{
    protected $repository;

    public function __construct(SupportMessageRepositoryInterface $repository)
    {
        parent::__construct();        
        $this->repository = $repository;
    }


    public function index(Request $request)
    {
        $messages = $this->repository->all($request);
        return response()->json($messages);
    }

    public function single($id)
    {
        $message = $this->repository->getOneWithRelations($id);
        return response()->json($message);
    }

    public function update(SupportMessageRequest $request, $id)
    {
        $data = $request->validated();
        $read = $request->get('read');
        
        $message = $this->repository->update($id, [
            'read_at' => $read == '1' ? Carbon::now() : null,
        ]);

        return response()->json($message);
    }

    public function delete($id = 0)
    {
        $this->repository->delete($id);

        return response()->json(['id' => $id]);
    }
}
