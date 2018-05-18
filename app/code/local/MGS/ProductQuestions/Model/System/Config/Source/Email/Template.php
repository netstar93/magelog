<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Model_System_Config_Source_Email_Template {

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {
        $templates = array();
        $collection = Mage::getModel('core/email_template')->getCollection();
        foreach ($collection as $template) {
            $templates[] = array('value' => $template->getId(), 'label' => $template->getData('template_subject'));
        }
        return $templates;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray() {
        $templates = array();
        $collection = Mage::getModel('core/email_template')->getCollection();
        foreach ($collection as $template) {
            $templates[$template->getId()] = $template->getData('template_subject');
        }
        return $templates;
    }

}
