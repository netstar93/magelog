<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Block_Adminhtml_Brand_Renderer_Icon extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $icon = $row->getData('icon');
        if ($icon != '') {
            return '<img width="70px" height="70px" src="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $icon . '" />';
        } else {
            return '';
        }
    }

}
