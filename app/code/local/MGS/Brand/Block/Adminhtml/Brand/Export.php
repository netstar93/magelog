<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Block_Adminhtml_Brand_Export extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('brandGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('brand/brand')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('id', array(
            'header' => Mage::helper('brand')->__('id'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('brand')->__('title'),
            'align' => 'left',
            'index' => 'title',
        ));

        $this->addColumn('url_key', array(
            'header' => Mage::helper('brand')->__('url_key'),
            'align' => 'left',
            'index' => 'url_key',
        ));

        $this->addColumn('icon', array(
            'header' => Mage::helper('brand')->__('icon'),
            'align' => 'center',
            'width' => '80px',
            'index' => 'icon',
            'filter' => false,
            'sortable' => false,
        ));

        $this->addColumn('image', array(
            'header' => Mage::helper('brand')->__('image'),
            'align' => 'center',
            'width' => '80px',
            'index' => 'icon',
            'filter' => false,
            'sortable' => false,
        ));

        $this->addColumn('description', array(
            'header' => Mage::helper('brand')->__('description'),
            'align' => 'left',
            'index' => 'description',
        ));

        $this->addColumn('meta_keywords', array(
            'header' => Mage::helper('brand')->__('meta_keywords'),
            'align' => 'left',
            'index' => 'meta_keywords',
        ));

        $this->addColumn('meta_description', array(
            'header' => Mage::helper('brand')->__('meta_description'),
            'align' => 'left',
            'index' => 'meta_description',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('brand')->__('status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
        ));

        $this->addColumn('is_featured', array(
            'header' => Mage::helper('brand')->__('is_featured'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'is_featured',
        ));

        $this->addColumn('priority', array(
            'header' => Mage::helper('brand')->__('priority'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'priority',
        ));

        $this->addColumn('number_of_products', array(
            'header' => Mage::helper('brand')->__('number_of_products'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'number_of_products',
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('brand')->__('CSV'));

        return parent::_prepareColumns();
    }

}
