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
    public static function validate(Object $imovel) : bool
    {
        if ($imovel->address->geoLocation->location->lon == 0 
            || $imovel->address->geoLocation->location->lat == 0 
            || (float)$imovel->usableAreas == 0) {
            return false;
        }
        return true;
    }

    /**
     * remove accents
     *
     * @param string $neighborhood
     *
     * @return bool
     */
    public static function tirarAcentos($neighborhood) : string
    {
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"), $neighborhood);
    }
}