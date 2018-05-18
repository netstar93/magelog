<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Block_Layer_State extends Mage_Catalog_Block_Layer_State {

    public function getLayer() {
        if (!$this->hasData('layer')) {
            $this->setLayer(Mage::getSingleton('brand/layer'));
        }
        return $this->_getData('layer');
    }

}
