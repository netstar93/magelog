<?php

/* * ****************************************************
 * Package   : Advancedreports
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_Advancedreports_Block_Adminhtml_Report_Salesbycountry_Renderer_Country extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $id = $row->getData('country');
        $address = Mage::getModel('sales/order_address')->load($id);
        if (Mage::app()->getLocale()->getCountryTranslation($address->getCountryId()) != null) {
            $html = Mage::app()->getLocale()->getCountryTranslation($address->getCountryId());
        } else {
            $html = 'Other';
        }
        return $html;
    }

}