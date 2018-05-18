<?php

class MGS_Deals_Model_System_Update {

    public function toOptionArray() {
        return array(
			array('value' => 'order', 'label' => Mage::helper('deals')->__('Order Complete')),
			array('value' => 'checkout', 'label' => Mage::helper('deals')->__('Checkout Complete'))
        );
    }

}