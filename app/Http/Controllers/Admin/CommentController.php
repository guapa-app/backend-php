<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Requests\CommentRequest;
use App\Contracts\Repositories\CommentRepositoryInterface;

class CommentController extends BaseAdminController
{
    private $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        parent::__construct();
        
        $this->commentRepository = $commentRepository;
    }
    
	public function index(Request $request)
	{
        $comments = $this->commentRepository->all($request);
        return response()->json($comments);
	}

	public function single($id = 0)
	{
		$comment = $this->commentRepository->getOneWithRelations($id);
        return response()->json($comment);
	}

	public function create(CommentRequest $request)
	{
        $data = $request->validated();
        $data['user_id' ] = auth()->id();
        $data['user_type'] = auth()->user()->getMorphClass();
        $comment = $this->commentRepository->create($data);
        return response()->json($comment);
	}

    public function update(CommentRequest $request, $id = 0)
    {
        $comment = $this->commentRepository->update($id, $request->validated());
        return response()->json($comment);
    }

    public function delete($id = 0)
    {
        $ids = $this->commentRepository->delete($id);
        return response()->json($ids);
    }
}
