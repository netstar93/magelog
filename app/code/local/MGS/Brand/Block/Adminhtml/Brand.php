<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Block_Adminhtml_Brand extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_brand';
        $this->_blockGroup = 'brand';
        $this->_headerText = Mage::helper('brand')->__('Manage Brands');
        $this->_addButtonLabel = Mage::helper('brand')->__('Add New Brand');
        $this->_addButton('import', array(
            'label' => 'Import',
            'onclick' => 'setLocation(\'' . $this->getImportUrl() . '\')',
            'class' => 'scalable',
        ));
        $this->_addButton('assign_products_to_brands', array(
            'label' => 'Assign Product(s) to Brand(s)',
            'onclick' => 'setLocation(\'' . $this->getAssignUrl() . '\')',
            'class' => 'scalable',
        ));
        $this->_addButton('refresh', array(
            'label' => 'Refresh Url Rewrite',
            'onclick' => 'setLocation(\'' . $this->getRefreshUrl() . '\')',
            'class' => 'scalable',
        ));
        parent::__construct();
    }

    public function getImportUrl()
    {
        return $this->getUrl('*/*/import');
    }

    public function getAssignUrl()
    {
        return $this->getUrl('*/*/assign');
    }

    public function getRefreshUrl()
    {
        return $this->getUrl('*/*/refreshUrl');
    }

}

