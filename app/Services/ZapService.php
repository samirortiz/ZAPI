<?php

namespace App\Services;

use App\Services\PortalService;
use App\Services\ImovelService;
use Illuminate\Support\Str;

class ZapService
{
    private $imovel;
    private $neighborhood;

    private $zapMinSquareValue = 3500;
    private $zapMinSale = 600000;
    private $zapMinRental = 3500;
    private $zapDiscount = 0.1;

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
        if ($this->imovel->pricingInfos->businessType == 'RENTAL' 
            && (float)$this->imovel->pricingInfos->rentalTotalPrice >= $this->zapMinRental) {
            if (!empty($this->neighborhood) && !\is_null($this->neighborhood)) {
                if (Str::slug(ImovelService::tirarAcentos($this->imovel->address->neighborhood)) == Str::slug($this->neighborhood)) {
                    return true;
                } else {
                    return false;
                }
            }
            return true;
        }
                
        if ($this->imovel->pricingInfos->businessType == 'SALE') {
            if (ImovelService::isInsideBounding($this->imovel)) {
                $updatedZapMinSale = $this->zapMinSale - ($this->zapMinSale * $this->zapDiscount);
            } else {
                $updatedZapMinSale = $this->zapMinSale;
            }
            
            if ((float)$this->imovel->pricingInfos->price >= $updatedZapMinSale) {
                if ((float)$this->imovel->pricingInfos->price / (float)$this->imovel->usableAreas > $this->zapMinSquareValue) {
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