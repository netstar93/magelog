<?php
class MGS_Promobanners_Block_Promobanners extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getPromobanners()     
     { 
        if (!$this->hasData('promobanners')) {
            $this->setData('promobanners', Mage::registry('promobanners'));
        }
        return $this->getData('promobanners');
        
    }
}