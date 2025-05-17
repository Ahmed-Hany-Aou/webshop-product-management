<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // You can include pagination data in your response
        return [
            'status_code' => 200,
            'message' => 'Products retrieved successfully',
            'result' => [
                'items' => $this->collection,  // This includes all paginated items
            ],
            'meta' => [
                'page' => $this->currentPage(),
                'take' => $this->perPage(),
                'items_count' => $this->count(),
                'total_items_count' => $this->total(),
                'page_count' => $this->lastPage(),
                'has_previous_page' => $this->previousPageUrl() !== null,
                'has_next_page' => $this->nextPageUrl() !== null,
            ],
        ];
    }
}
