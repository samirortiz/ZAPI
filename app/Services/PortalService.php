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
    public function list($portal) : collection
    {
        $listings = [];

        foreach ($this->collection as $key => $imovel) 
        {
            if (!ImovelService::validate($imovel)) {
                continue;
            }

            switch ($portal) {

                case 'zap': 
                    $zapService = new ZapService($imovel);
                    
                    if($zapService->validate()) {
                        $listings[] = $zapService->getImovel();
                    }
                break;

                case 'vivareal':
                    $vivaRealService = new VivaRealService($imovel);
                    
                    if ($vivaRealService->validate()) {
                        $listings[] = $vivaRealService->getImovel();
                    }
                break;

                default:
                    abort(404);
            }
        }
        return collect($listings);
    }

    /**
     * isInsideBounding
     *
     * @param Object $imovel
     *
     * @return bool
     */
    public static function isInsideBounding(Object $imovel) : bool
    {
        if ($imovel->address->geoLocation->location->lat >= env('BOUNDING_BOX_MIN_LAT') 
            && $imovel->address->geoLocation->location->lat <= env('BOUNDING_BOX_MAX_LAT') 
            && $imovel->address->geoLocation->location->lon >= env('BOUNDING_BOX_MIN_LONG') 
            && $imovel->address->geoLocation->location->lon <= env('BOUNDING_BOX_MAX_LONG')) {
            return true;
        }
        return false;
    }

    /**
     * validate
     *
     * @param Object $imovel
     *
     * @return bool
     */
    public function validate(Object $imovel) : bool
    {
        if ($imovel->address->geoLocation->location->lon == 0 
            || $imovel->address->geoLocation->location->lat == 0 
            || (float)$imovel->usableAreas == 0) {
            return false;
        }
        return true;
    }

}