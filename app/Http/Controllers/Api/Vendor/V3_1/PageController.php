<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\PageRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\V3\PageResource;

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
}
