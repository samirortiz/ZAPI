<?php

namespace App\Http\Controllers;

use App\Services\ImovelService; 
use App\Http\Resources\ResponseResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;


class PortalController extends Controller
{

    protected $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->service = new ImovelService();
    }


    public function list(Request $request) : ResponseResource
    {
        if(!$request->portal) {
            abort(404);
        }

        $response = $this->service->list($request->portal);

        if(!count($response)) {
            abort(404);
        }

        $page = $request->page ? $request->page : 1;
        $perPage = $request->per_page ? $request->per_page : 15;
        $offset = ($page * $perPage) - $perPage;

        $results =  new LengthAwarePaginator(
            $response->slice($offset, $perPage),
            count($response),
            $perPage,
            $page,
            ['path' => $request->url()]
        );

        return new ResponseResource($results);       
    }
    
}
