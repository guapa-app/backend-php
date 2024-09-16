<?php

namespace App\Http\Resources\V3_1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class GeneralCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->prepareAddiitonal($request) + [
                'data' => [
                        'items' => $this->collection,
                    ] + $this->preparePayload($request),
            ];
    }

    private function prepareAddiitonal($request)
    {
        return $request->has('perPage') ? $this->additional : [];
    }

    private function preparePayload($request)
    {
        if ($request->has('perPage') || $request->has('per_page')) {
            $payload = [
                'payload' => [
                    'pagination' => [
                        'total'             => $this->total(),
                        'page'              => $this->currentPage(),
                        'from'              => $this->firstItem(),
                        'to'                => $this->lastItem(),
                        'last_page'         => $this->lastPage(),
                        'items_per_page'    => $this->perPage(),
                        'first_page_url'    => $this->url(1),
                        'next_page_url'     => $this->nextPageUrl(),
                        'prev_page_url'     => $this->previousPageUrl(),
                        'links'             => $this->linkCollection(),
                    ],
                ],
            ];
        }

        return $payload ?? [];
    }

    public function preparePaginatedResponse($request)
    {
        return $this;
    }
}
