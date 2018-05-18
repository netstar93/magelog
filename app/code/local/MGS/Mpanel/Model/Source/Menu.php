<?php

class MGS_Mpanel_Model_Source_Menu {

    public function toOptionArray() {
        $arr = array(array('value' => '', 'label' => ''));
        $collection = Mage::getModel('megamenu/parent')
                ->getCollection()
                ->addFieldToFilter('parent_id', array('neq' => 1))
                ->addFieldToFilter('menu_type', 2)
                ->addFieldToFilter('status', 1);
        if (count($collection) > 0) {
            foreach ($collection as $menu) {
                $arr[] = array(
                    'value' => $menu->getId(),
                    'label' => $menu->getTitle()
                );
            }
        }
        return $arr;
    }

}
