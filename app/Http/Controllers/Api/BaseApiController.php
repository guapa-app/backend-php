<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\CityRepositoryInterface;
use App\Contracts\Repositories\PageRepositoryInterface;
use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Contracts\Repositories\SupportMessageRepositoryInterface;
use App\Contracts\Repositories\TaxRepositoryInterface;
use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupportMessageRequest;
use App\Http\Resources\SupportMessageResource;
use App\Models\Address;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;

class BaseApiController extends Controller
{
    public function __construct()
    {
        $this->user = auth('api')->user();
    }

    /**
     * Application data
     *
     * @unauthenticated
     *
     * @return array
     */
    public function data()
    {
        $taxRepository = resolve(TaxRepositoryInterface::class);
        $cityRepository = resolve(CityRepositoryInterface::class);
        $settingRepository = resolve(SettingRepositoryInterface::class);

        return [
            'specialties'           => $taxRepository->getForApiData(['type' => 'specialty']),
            'categories'            => $taxRepository->getForApiData(['type' => 'category']),
            'blog_categories'       => $taxRepository->getForApiData(['type' => 'blog_category']),
            'address_types'         => self::address_types(),
            'vendor_types'          => self::vendor_types(),
            'cities'                => $cityRepository->getAll(),
            'settings'              => $settingRepository->getAll(),
            'max_price'             => ceil(Product::max('price')),
            'product_fees'          => Setting::getProductFees(),
            'taxes_percentage'      => Setting::getTaxes(),
        ];
    }

    public function address_types()
    {
        $types = Address::TYPES;
        return Common::mapIdName($types);
    }

    public function vendor_types()
    {
        $types = Vendor::TYPES;
        return Common::mapIdName($types);
    }

    /**
     * Application pages
     *
     * @unauthenticated
     *
     * @return JsonResponse
     */
    public function pages()
    {
        $pageRepository = resolve(PageRepositoryInterface::class);
        return $this->successJsonRes([
            "items" => $pageRepository->getAll()
        ], __('api.success'));
    }

    /**
     * Contact support
     *
     * @responseFile 200 responses/general/contact.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     *
     * @unauthenticated
     *
     * @param SupportMessageRequest $request
     * @return JsonResponse
     */
    public function contact(SupportMessageRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $this->user ? $this->user->id : null;

        $record = app(SupportMessageRepositoryInterface::class)->create($data);

        return SupportMessageResource::make($record)
            ->additional([
                "success" => true,
                'message' => __('api.success'),
            ]);
    }
}
