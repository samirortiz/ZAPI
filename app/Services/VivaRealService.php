<?php

namespace App\Services;

use App\Services\PortalService;
use App\Services\ImovelService;
use Illuminate\Support\Str;

class VivaRealService
{
    private $imovel;
    private $neighborhood;

    private $vivaRealMaxRental = 4000;
    private $vivaRealMaxSale = 700000;
    private $vivaRealBoundingMaxRental = 0.5;
    private $vivaRealDiscount = 0.3;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct(Object $imovel, string $neighborhood = null) 
    {
        $this->imovel = $imovel;
        $this->neighborhood = ImovelService::tirarAcentos($neighborhood) ?? null;
    }

    /**
     * validate
     *
     * elegible validation
     * 
     * @return bool
     */
    public function validate() : bool
    {
        if ($this->imovel->pricingInfos->businessType == 'RENTAL') {
            if (ImovelService::isInsideBounding($this->imovel)) {
                $updatedVivaRealMaxRental = $this->vivaRealMaxRental + ($this->vivaRealMaxRental * $this->vivaRealBoundingMaxRental);
            } else {
                $updatedVivaRealMaxRental = $this->vivaRealMaxRental;
            }

            if ((float)$this->imovel->pricingInfos->rentalTotalPrice <= $updatedVivaRealMaxRental) {
                if (property_exists($this->imovel->pricingInfos, 'monthlyCondoFee')) {
                    if (is_numeric($this->imovel->pricingInfos->monthlyCondoFee) && (float)$this->imovel->pricingInfos->monthlyCondoFee > 0 
                        && ((float)$this->imovel->pricingInfos->monthlyCondoFee < ((float)$this->imovel->pricingInfos->price * $this->vivaRealDiscount))) {
                        if (!empty($this->neighborhood) && !\is_null($this->neighborhood)) {
                            if (Str::slug(ImovelService::tirarAcentos($this->imovel->address->neighborhood)) == Str::slug($this->neighborhood)) {
                                return true;
                            } else {
                                return false;
                            }
                        } 
                        return true;
                    }
                }
            }
        }

        if ($this->imovel->pricingInfos->businessType == 'SALE' 
            && (float)$this->imovel->pricingInfos->price <= $this->vivaRealMaxSale) {
            if (!empty($this->neighborhood) && !\is_null($this->neighborhood)) {
                if(Str::slug(ImovelService::tirarAcentos($this->imovel->address->neighborhood)) == Str::slug($this->neighborhood)) {
                    return true;
                } else {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * get
     *
     * @return object
     */
    public function getImovel() : object
    {
        return $this->imovel;
    }

}