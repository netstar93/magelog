<?php

/* * ****************************************************
 * Package   : QuickView
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_QuickView_Helper_Data extends MGS_Mgscore_Helper_Data {

    public function isActive() {
        return Mage::getStoreConfig('quickview/general/active');
    }

}
