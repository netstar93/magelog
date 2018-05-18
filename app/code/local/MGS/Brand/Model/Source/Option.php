<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Model_Source_Option extends Mage_Eav_Model_Entity_Attribute_Source_Table {

    public function toOptionArray() {
        $collection = Mage::getModel('brand/brand')->getCollection()
                ->addFieldToFilter('status', array('eq' => 1));
        $options = array();
        if (count($collection)) {
            foreach ($collection as $brand) {
                $options[] = array(
                    'label' => $brand->getTitle(),
                    'value' => $brand->getId()
                );
            }
        }
        return $options;
    }

}
