<?php

class MGS_Mpanel_Model_Source_Promo {

    public function toOptionArray() {
        $arr = array(array('value' => '', 'label' => ''));
        $promos = Mage::getModel('promobanners/promobanners')->getCollection()
                ->addFieldToFilter('status', array('eq' => 1));
        if (count($promos) > 0) {
            foreach ($promos as $promo) {
                $arr[] = array(
                    'value' => $promo->getData('promobanners_id'),
                    'label' => $promo->getData('title')
                );
            }
        }
        return $arr;
    }

}
