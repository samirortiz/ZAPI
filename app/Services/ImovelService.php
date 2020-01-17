<?php

namespace App\Services;

use App\Http\Resources\ResponseResource;
use Illuminate\Support\Collection;
use GuzzleHttp\Client;

class ImovelService
{

    protected $client;

    /* minlon: -46.693419
    minlat -23.568704
    maxlon: -46.641146
    maxlat: -23.546686 */

    private $zapMinSquareValue = 3500;
    private $zapMinSale = 600000;
    private $zapMinRental = 3500;
    private $vivaRealMaxRental = 4000;
    private $vivaRealMaxSale = 700000;
    private $zapBoundingDiscount = 10;
    private $vivaRealBoundingMaxRental = 50;

    public function __construct() 
    {
        $this->client = new Client(['base_uri' => 'http://grupozap-code-challenge.s3-website-us-east-1.amazonaws.com']);
    }

    /**
     * list
     *
     * @param  array $filter
     *
     * @return Collection
     */
    public function list($portal) : collection
    {
        $response = $this->client->get('/sources/source-2.json');
        $collection = collect(json_decode($response->getBody()->getContents()));

        $listings = [];

        foreach($collection as $key => $imovel) 
        {
            //LAT/LON VALIDATION
            if ($imovel->address->geoLocation->location->lon == 0 || $imovel->address->geoLocation->location->lat == 0 || (float)$imovel->usableAreas == 0.0) {
                continue;
            }

            switch ($portal) {

                case 'zap': 
                    if($imovel->pricingInfos->businessType == 'RENTAL' && (float)$imovel->pricingInfos->rentalTotalPrice >= $this->zapMinRental) {
                        $listings[] = $imovel;
                    }
                    
                    if($imovel->pricingInfos->businessType == 'SALE') {
                        if($this->isBounding($imovel->address->geoLocation->location->lat, $imovel->address->geoLocation->location->lon)) {
                            $updatedZapMinSale = $this->zapMinSale - ($this->zapMinSale * 0.1);
                        } else {
                            $updatedZapMinSale = $this->zapMinSale;
                        }

                        if((float)$imovel->pricingInfos->price >= $updatedZapMinSale) {
                            if((float)$imovel->pricingInfos->price/(float)$imovel->usableAreas > $this->zapMinSquareValue) {
                                $listings[] = $imovel;
                            }
                        }
                    }
                break;

                case 'vivareal':
                    if($imovel->pricingInfos->businessType == 'RENTAL') {
                        if($this->isBounding($imovel->address->geoLocation->location->lat, $imovel->address->geoLocation->location->lon)) {
                            $updatedVivaRealMaxRental = $this->vivaRealMaxRental  + ($this->vivaRealMaxRental * 0.5);
                        } else {
                            $updatedVivaRealMaxRental = $this->vivaRealMaxRental;
                        }

                        if((float)$imovel->pricingInfos->rentalTotalPrice <= $updatedVivaRealMaxRental) {
                            if(property_exists($imovel->pricingInfos, 'monthlyCondoFee')) {
                                if(is_numeric($imovel->pricingInfos->monthlyCondoFee) && (float)$imovel->pricingInfos->monthlyCondoFee > 0 
                                    && ((float)$imovel->pricingInfos->monthlyCondoFee < ((float)$imovel->pricingInfos->price * 0.3))) {
                                    $listings[] = $imovel;
                                }
                            }
                        }
                    }

                    if($imovel->pricingInfos->businessType == 'SALE' && (float)$imovel->pricingInfos->price <= $this->vivaRealMaxSale) {
                        $listings[] = $imovel;
                    }

                break;

                default:
                    abort(404);
            }
        }
        return collect($listings);
    }

    public function isBounding($lat, $lon) {
        if($lat >= -23.568704 && $lat <= -23.546686 && $lon >= -46.693419 && $lon <= -46.641146) {
            return true;
        }
        return false;
    }

}