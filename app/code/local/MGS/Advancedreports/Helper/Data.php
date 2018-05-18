<?php

/* * ****************************************************
 * Package   : Advancedreports
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_Advancedreports_Helper_Data extends MGS_Mgscore_Helper_Data {

    public function isActiveModule() {
        return Mage::getStoreConfig('advancedreports/general/active');
    }

    public function isEnabledGoogleCharts() {
        return Mage::getStoreConfig('advancedreports/general/enable_google_charts');
    }        

}