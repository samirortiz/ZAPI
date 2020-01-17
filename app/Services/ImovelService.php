<?php

namespace App\Services;

class ImovelService
{
    /**
     * isInsideBounding
     *
     * @param Object $imovel
     *
     * @return bool
     */
    public static function isInsideBounding(Object $imovel) : bool
    {
        if($imovel->address->geoLocation->location->lat >= env('BOUNDING_BOX_MIN_LAT') 
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
    public static function validate(Object $imovel) : bool
    {
        if ($imovel->address->geoLocation->location->lon == 0 
            || $imovel->address->geoLocation->location->lat == 0 
            || (float)$imovel->usableAreas == 0) {
            return false;
        }
        return true;
    }
}