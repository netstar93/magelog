<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Block_Adminhtml_Brand_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('brand_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('brand')->__('Brand'));
    }

    protected function _beforeToHtml() {
        $this->addTab('general', array(
            'label' => Mage::helper('brand')->__('General'),
            'title' => Mage::helper('brand')->__('General'),
            'content' => $this->getLayout()->createBlock('brand/adminhtml_brand_edit_tab_form')->toHtml(),
        ));

        $this->addTab('product', array(
            'label' => Mage::helper('brand')->__('Products'),
            'title' => Mage::helper('brand')->__('Products'),
            'url' => $this->getUrl('*/*/product', array('_current' => true)),
            'class' => 'ajax',
        ));

        return parent::_beforeToHtml();
    }

}
