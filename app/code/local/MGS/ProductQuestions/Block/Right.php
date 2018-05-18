<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Right extends Mage_Core_Block_Template {

    public function getTopics() {
        $collection = Mage::getModel('productquestions/topic')->getCollection()
                ->addFieldToFilter('parent_id', 0)
                ->addFieldToFilter('show_on_block', 1)
                ->addFieldToFilter('status', 1)
                ->setOrder('title', 'ASC');
        if (Mage::helper('productquestions')->numberOfTopics() != '') {
            $collection->getSelect()->limit(Mage::helper('productquestions')->numberOfTopics());
        }
        return $collection;
    }

}
