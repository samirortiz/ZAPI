<?php

namespace App\Services;

use App\Services\PortalService;
use App\Services\ImovelService;

class ZapService
{
    private $imovel;

    private $zapMinSquareValue = 3500;
    private $zapMinSale = 600000;
    private $zapMinRental = 3500;

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
        if($this->imovel->pricingInfos->businessType == 'RENTAL' && (float)$this->imovel->pricingInfos->rentalTotalPrice >= $this->zapMinRental) {
            return true;
        }
                
        if($this->imovel->pricingInfos->businessType == 'SALE') {
            if(ImovelService::isInsideBounding($this->imovel)) {
                $updatedZapMinSale = $this->zapMinSale - ($this->zapMinSale * 0.1);
            } else {
                $updatedZapMinSale = $this->zapMinSale;
            }
            
            if((float)$this->imovel->pricingInfos->price >= $updatedZapMinSale) {
                if((float)$this->imovel->pricingInfos->price/(float)$this->imovel->usableAreas > $this->zapMinSquareValue) {
                    return true;
                }
            }
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