<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ImoveisCollection;

class ImovelCollection extends ResourceCollection
{

    protected $preserveKeys = false;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return AnonymousResourceCollection
     */
    public function toArray($request) 
    {
        return ImovelResource::collection($this->collection);
    }

   
    
}