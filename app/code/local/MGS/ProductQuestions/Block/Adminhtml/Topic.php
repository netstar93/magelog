<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Adminhtml_Topic extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_topic';
        $this->_blockGroup = 'productquestions';
        $this->_headerText = Mage::helper('productquestions')->__('Topic Manager');
        $this->_addButtonLabel = Mage::helper('productquestions')->__('Add Topic');
        parent::__construct();
    }

}
