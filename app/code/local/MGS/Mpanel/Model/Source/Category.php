<?php

class MGS_Mpanel_Model_Source_Category {

    public function toOptionArray() {
        $arr = array(array('value' => 0, 'label' => 'Root Category'));
        $helper = Mage::helper('catalog/category');
        $categories = $helper->getStoreCategories();
        if (count($categories) > 0) {
            foreach ($categories as $category) {
                $arr[] = array(
                    'value' => $category->getId(),
                    'label' => $category->getName()
                );
            }
        }
        return $arr;
    }

}
