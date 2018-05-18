<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Block_Brand_Widget extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface {

    public function _construct() {
        parent::_construct();
    }

    public function getBrandCollection() {
        $ids = $this->getIds();
        $collection = Mage::getModel('brand/brand')->getCollection()
                ->addFieldToFilter('status', array('eq' => 1));
        $collection->addStoreFilter(Mage::app()->getStore()->getStoreId());
        if (count($ids)) {
            $collection->addFieldToFilter('id', array('in' => $ids));
        }
        return $collection;
    }

    public function getIds() {
        return explode(',', $this->getData('ids'));
    }

    public function getTitle() {
        return $this->getData('title');
    }

    public function getUrlKey() {
        $helper = Mage::helper('brand');
        if ($helper->urlKey() != '') {
            $urlKey = Mage::helper('brand')->urlKey();
        } else {
            $urlKey = 'brand';
        }
        return $urlKey;
    }

    public function showWidgetAs() {
        return $this->getData('show_widget_as');
    }

}
