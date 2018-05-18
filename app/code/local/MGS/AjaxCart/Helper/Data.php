<?php

/* * ****************************************************
 * Package   : AjaxCart
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_AjaxCart_Helper_Data extends MGS_Mgscore_Helper_Data {

    public function isActive() {
        return Mage::getStoreConfig('ajaxcart/general/active');
    }              

}
