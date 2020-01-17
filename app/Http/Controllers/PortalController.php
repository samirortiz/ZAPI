<?php

namespace App\Http\Controllers;

use App\Services\PortalService; 
use App\Http\Resources\ResponseResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


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
        $this->service = app(PortalService::class);
    }

    /**
     * list
     *
     * @param Request $request
     * 
     * @return JsonResponse 
     */
    public function list(Request $request) : JsonResponse
    {
        if (!$request->portal) {
            return response()->json(['status' => 400, 
                'message' => 'Nenhum portal selecionado', 
                'hint' => 'Acesse '.$request->url().'/portal/nomedoportal']);
        }

        $response = $this->service->list($request->portal);

        if (!count($response)) {
            return response()->json(['status' => 203, 
                'message' => 'Nenhum registro encontrado', 
                'hint' => '']);
        }

        $page = $request->page ? $request->page : 1;
        $perPage = $request->per_page ? $request->per_page : 20;
        $offset = ($page * $perPage) - $perPage;

        $results =  new LengthAwarePaginator(
            $response->slice($offset, $perPage),
            count($response),
            $perPage,
            $page,
            ['path' => $request->url()]
        );

        return response()->json(new ResponseResource($results));       
    }
    
}
