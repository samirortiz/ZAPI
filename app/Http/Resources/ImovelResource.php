<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImovelResource extends JsonResource
{

    protected $preserveKeys = false;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Resource
     */
    public function toArray($request) 
    {
        return $this->resource;
    }

   
    
}