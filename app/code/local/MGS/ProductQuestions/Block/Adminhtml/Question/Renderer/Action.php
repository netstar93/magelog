<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Adminhtml_Question_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $html = '<a href="#" onclick="editPopup(\'' . $row->getId() . '\'); return false;">' . $this->__('Edit') . '</a>';
        $html .= ' | ';
        $html .= '<a href="#" onclick="deleteAnswer(\'' . $row->getId() . '\'); return false;">' . $this->__('Delete') . '</a>';
        return $html;
    }

}
