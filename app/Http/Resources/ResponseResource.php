<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ImovelCollection;

class ResponseResource extends JsonResource
{

    protected $preserveKeys = false;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) : array
    {
        if (is_null($this->resource)) {
            abort(404);
        }

        return [
            'pageNumber' => $this->resource->currentPage(),
            'pageSize' => $this->resource->perPage(),
            'totalCount' => $this->resource->total(), 
            'listings' => new ImovelCollection($this->resource)
        ];
    }

   
    
}