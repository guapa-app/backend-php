<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\PageRepositoryInterface;
use App\Http\Requests\PageRequest;
use Illuminate\Http\Request;

class PageController extends BaseAdminController
{
    private $pageRepository;

    public function __construct(PageRepositoryInterface $pageRepository)
    {
        parent::__construct();

        $this->pageRepository = $pageRepository;
    }

    public function index(Request $request)
    {
        $pages = $this->pageRepository->all($request);

        return response()->json($pages);
    }

    public function single($id)
    {
        $page = $this->pageRepository->getOneWithRelations($id);

        return response()->json($page);
    }

    public function create(PageRequest $request)
    {
        // Create the page
        $page = $this->pageRepository->create($request->validated());

        return response()->json($page);
    }

    public function update(PageRequest $request, $id)
    {
        // Update the page
        $page = $this->pageRepository->update($id, $request->validated());

        // If the page is a category and
        return response()->json($page);
    }

    public function delete($id)
    {
        $this->pageRepository->delete($id);

        return response()->json(['id' => $id]);
    }
}
