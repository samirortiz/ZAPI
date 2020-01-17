<?php

namespace App\Services;

use Illuminate\Support\Collection;
use GuzzleHttp\Client;
use App\Services\ZapService;
use App\Services\VivaRealService;
use App\Services\ImovelService;
use Illuminate\Support\Facades\Cache;

class PortalService
{
    protected $client;
    protected $collection;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct() 
    {
        if (!Cache::has('response')) {
            $this->client = new Client(['base_uri' => env('PORTAL_BASE_URI')]);
            $response = $this->client->get(env('PORTAL_SOURCE_FILE'))->getBody()->getContents();

            Cache::put('response', $response, 60);
        }

        $response = Cache::get('response');
        $this->collection = collect(json_decode($response));
    }

    /**
     * list
     *
     * @param  string $portal
     *
     * @return collection
     */
    public function list($request) : collection
    {
        $listings = [];
        foreach ($this->collection as $key => $imovel) 
        {
            if (!ImovelService::validate($imovel)) {
                continue;
            }
            switch ($request->portal) {
                case 'zap':
                    $zapService = new ZapService($imovel, urldecode($request->neighborhood));
                    
                    if($zapService->validate()) {
                        $listings[] = $zapService->getImovel();
                    }
                break;

                case 'vivareal':
                    $vivaRealService = new VivaRealService($imovel, urldecode($request->neighborhood));
                    
                    if ($vivaRealService->validate()) {
                        $listings[] = $vivaRealService->getImovel();
                    }
                break;

                default:
                    return collect($listings);
            }
        }
        return collect($listings);
    }

}