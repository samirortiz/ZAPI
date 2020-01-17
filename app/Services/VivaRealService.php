<?php

namespace App\Services;

use App\Services\PortalService;
use App\Services\ImovelService;

class VivaRealService
{
    private $imovel;

    private $vivaRealMaxRental = 4000;
    private $vivaRealMaxSale = 700000;
    private $vivaRealBoundingMaxRental = 0.5;
    private $vivaRealDiscount = 0.3;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct(Object $imovel) 
    {
        $this->imovel = $imovel;
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
                        return true;
                    }
                }
            }
        }

        if ($this->imovel->pricingInfos->businessType == 'SALE' 
            && (float)$this->imovel->pricingInfos->price <= $this->vivaRealMaxSale) {
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