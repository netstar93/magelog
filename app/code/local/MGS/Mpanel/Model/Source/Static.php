<?php

class MGS_Mpanel_Model_Source_Static {

    public function toOptionArray() {
        $blocks = array(array('value' => '', 'label' => ''));
        $collection = Mage::getModel('cms/block')->getCollection();
        foreach ($collection as $block) {
            $blocks[] = array('value' => $block->getData('identifier'), 'label' => $block->getData('title'));
        }
        return $blocks;
    }

}
