<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\PageRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\User\V3_1\PageResource;

class PageController extends BaseApiController
{
    private $pageRepository;

    public function __construct(PageRepositoryInterface $pageRepository)
    {
        parent::__construct();

        $this->pageRepository = $pageRepository;
    }

    /**
     * List pages.
     *
     * @param $request
     * @return PageResource
     */
    public function aboutUs()
    {
        $page = $this->pageRepository->getFirst(['slug' => 'about-us']);

        return PageResource::make($page)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function terms()
    {
        $page = $this->pageRepository->getFirst(['slug' => 'terms']);

        return PageResource::make($page)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    /**
     * Show page.
     *
     * @param $id
     * @return PageResource
     */
    public function show($id)
    {
        $page = $this->pageRepository->getOneWithRelations($id,['published'=>1]);

        return PageResource::make($page)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
