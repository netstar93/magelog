<?php

class MGS_Lookbook_Block_Adminhtml_Slide_Edit_Tab_Items extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('productGrid');
        $this->setDefaultSort('lookbook_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
    }

    protected function _getSlider() {
        $sliderId = $this->getRequest()->getParam('id');
        return Mage::getModel('lookbook/slide')->load($sliderId);
    }

    protected function _addColumnFilterToCollection($column) {
        if ($column->getId() == 'in_slider') {
            $lookbookIds = $this->_getSelectedLookbooks();
            if (empty($lookbookIds)) {
                $lookbookIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('lookbook_id', array('in' => $lookbookIds));
            } else {
                if ($lookbookIds) {
                    $this->getCollection()->addFieldToFilter('lookbook_id', array('nin' => $lookbookIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    protected function _prepareCollection() {
        if ($this->_getSlider()->getId()) {
            $this->setDefaultFilter(array('in_slider' => 1));
        }

        $collection = Mage::getModel('lookbook/lookbook')->getCollection();
        
        $this->setCollection($collection);

        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns() {
        $this->addColumn('in_slider', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'in_slider',
            'values' => $this->_getSelectedLookbooks(),
            'align' => 'center',
            'index' => 'lookbook_id'
        ));

        $this->addColumn('lookbook_id', array(
            'header' => Mage::helper('lookbook')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'lookbook_id',
        ));
		
		$this->addColumn( 'image', array(
          'header' => Mage::helper( 'lookbook' )->__( 'Image' ), 
          'type' => 'image', 
          'width' => '75px', 
          'index' => 'image',
          'filter'    => false,
          'sortable'  => false,
          'renderer' => 'lookbook/adminhtml_lookbook_grid_renderer_image',
      ));

        $this->addColumn('name', array(
            'header' => Mage::helper('lookbook')->__('Name'),
            'align' => 'left',
            'index' => 'name',
        ));
		
		$this->addColumn('status', array(
          'header'    => Mage::helper('lookbook')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));

        $this->addColumn('position', array(
            'header' => Mage::helper('lookbook')->__('Position'),
            'name' => 'position',
            'type' => 'number',
            'validate_class' => 'validate-number',
            'width' => 60,
            'sortable' => false,
            'filter' => false,
            'editable' => true,
            'edit_only' => true
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/lookbookGrid', array('_current' => true));
    }

    protected function _getSelectedLookbooks() {
        $lookbookIds = $this->getLookbookIds();
        if (is_null($lookbookIds)) {
            $lookbookIds = array_keys($this->getSelectedLookbooks());
        }
        return $lookbookIds;
    }

    public function getSelectedLookbooks() {
        $lookbookIds = array();
        if ($this->_getSlider() && $this->_getSlider()->getId()) {
            $collection = Mage::getModel('lookbook/item')->getCollection()
                    ->addFieldToFilter('slide_id', $this->_getSlider()->getId());
            foreach ($collection as $item) {
                $lookbookIds[$item->getLookbookId()] = array('position' => $item->getPosition());
            }
        }
		//echo '<pre>'; print_r($lookbookIds); die();
        return $lookbookIds;
    }

}
