<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Contracts\Repositories\PageRepositoryInterface;
use App\Contracts\Repositories\TaxRepositoryInterface;
use App\Contracts\Repositories\CityRepositoryInterface;
use App\Contracts\Repositories\SupportMessageRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupportMessageRequest;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function data()
    {
        $settingRepository = resolve(SettingRepositoryInterface::class);
        $taxRepository = resolve(TaxRepositoryInterface::class);
        $cityRepository = resolve(CityRepositoryInterface::class);
        $addressTypes = \App\Models\Address::TYPES;
        $vendorTypes = \App\Models\Vendor::TYPES;

    	return [
    		'specialties' => $taxRepository->getForApiData([
                'type' => 'specialty',
            ]),
            'categories' => $taxRepository->getForApiData([
                'type' => 'category',
            ]),
            'blog_categories' => $taxRepository->getForApiData([
                'type' => 'blog_category',
            ]),
            'address_types' => array_map(function($v, $k) {
                return [
                    'id' => $k,
                    'name' => $v,
                ];
            }, $addressTypes, array_keys($addressTypes)),
            'vendor_types' => array_map(function($v, $k) {
                return [
                    'id' => $k,
                    'name' => $v,
                ];
            }, $vendorTypes, array_keys($vendorTypes)),
            'cities' => $cityRepository->getAll(),
    		'settings' => $settingRepository->getAll(),
            'max_price' => ceil(\App\Models\Product::max('price')),
    	];
    }

    /**
     * Application pages
     *
     * @unauthenticated
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function pages()
    {
        $pageRepository = resolve(PageRepositoryInterface::class);
        return response()->json($pageRepository->getAll());
    }

    /**
     * Contact support
     *
     * @responseFile 200 responses/general/contact.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     *
     * @unauthenticated
     * 
     * @param  \App\Http\Requests\SupportMessageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function contact(SupportMessageRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $this->user ? $this->user->id : null;

        $message = app(SupportMessageRepositoryInterface::class)->create($data);

        return response()->json($message);
    }
}
