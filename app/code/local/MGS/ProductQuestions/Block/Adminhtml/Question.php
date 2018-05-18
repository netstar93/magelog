<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Adminhtml_Question extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_question';
        $this->_blockGroup = 'productquestions';
        $this->_headerText = Mage::helper('productquestions')->__('Question Manager');
        $this->_addButtonLabel = Mage::helper('productquestions')->__('Add Question');
        parent::__construct();
    }

}
