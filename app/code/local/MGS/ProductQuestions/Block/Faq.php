<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Faq extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        $this->getLayout()->getBlock('head')->setTitle(Mage::helper('productquestions')->faqsPageTitle());
        return parent::_prepareLayout();
    }

}
