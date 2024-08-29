<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\SocialMediaRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\V3\SocialMediaCollection;
use App\Http\Resources\V3\SocialMediaResource;
use Illuminate\Http\Request;

class SocialMediaController extends BaseApiController
{
    private $socialMediaRepository;

    public function __construct(SocialMediaRepositoryInterface $socialMediaRepository)
    {
        parent::__construct();

        $this->socialMediaRepository = $socialMediaRepository;
    }

    public function index(Request $request)
    {
        $index = $this->socialMediaRepository->all($request);

        return SocialMediaCollection::make($index)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single($id)
    {
        $single = $this->socialMediaRepository->getOneWithRelations($id);

        return SocialMediaResource::make($single)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
