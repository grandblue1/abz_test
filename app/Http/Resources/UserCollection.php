<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $paginator = $this->resource;

        return [
            'total_pages' => $paginator->lastPage(),
            'total_users' => $paginator->total(),
            'count' => $paginator->perPage(),
            'page' => $paginator->currentPage(),
            'users' => $this->collection,
        ];
    }
    public function withResponse($request, $response)
    {
        $jsonResponse = json_decode($response->getContent(), true);
        $links = [
                'next_url' => $jsonResponse['links']['next'],
                'prev_url' => $jsonResponse['links']['prev']
            ];
        $jsonResponse = array_merge($jsonResponse['data'], array('links' => $links));
        $response->setContent(json_encode($jsonResponse));
    }
}
