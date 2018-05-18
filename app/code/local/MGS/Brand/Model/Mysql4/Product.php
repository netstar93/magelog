<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Model_Mysql4_Product extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        // Note that the id refers to the key field in your database table.
        $this->_init('brand/product', 'id');
    }

}
