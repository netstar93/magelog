<?php

class MGS_Deals_Block_Adminhtml_Deals_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('dealsGrid');
      $this->setDefaultSort('deals_id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('deals/deals')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }
  
	protected function _getStore()
	{
		$storeId = (int) $this->getRequest()->getParam('store', 0);
		return Mage::app()->getStore($storeId);
	}

	protected function _prepareColumns()
	{
		$this->addColumn('deals_id', array(
			'header'    => Mage::helper('deals')->__('ID'),
			'align'     =>'right',
			'width'     => '50px',
			'index'     => 'deals_id',
		));

		$this->addColumn('product_name', array(
			'header'    => Mage::helper('deals')->__('Product'),
			'align'     =>'left',
			'index'     => 'product_name',
		));

		$store = $this->_getStore();
		$this->addColumn('price',array(
			'header'=> Mage::helper('catalog')->__('Price'),
			'type'  => 'price',
			'currency_code' => $store->getBaseCurrency()->getCode(),
			'index' => 'price',
		));
		
		$this->addColumn('special_price',array(
			'header'=> Mage::helper('catalog')->__('Deal Price'),
			'type'  => 'price',
			'currency_code' => $store->getBaseCurrency()->getCode(),
			'index' => 'special_price',
		));
		
		$this->addColumn('start_time', array(
            'header'    => Mage::helper('cms')->__('Start Time'),
            'index'     => 'start_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('end_time', array(
            'header'    => Mage::helper('cms')->__('End Time'),
            'index'     => 'end_time',
            'type'      => 'datetime',
        ));
		
		/* $this->addColumn('qty', array(
			'header'    => Mage::helper('deals')->__('Qty'),
			'align'     =>'left',
			'index'     => 'qty',
			'width'		=>'50px',
		)); */
		
		$this->addColumn('deal_qty', array(
			'header'    => Mage::helper('deals')->__('Deal Quantity'),
			'align'     =>'left',
			'width'		=>'50px',
			'filter'    => false,
			'renderer'	=> new MGS_Deals_Block_Adminhtml_Deals_Renderer_Qty
		));
		
		/* $this->addColumn('max_deal_qty', array(
			'header'    => Mage::helper('deals')->__('Deal Quantity'),
			'align'     =>'left',
			'index'     => 'max_deal_qty',
			'width'		=>'50px',
		)); */
		
		$this->addColumn('sold', array(
			'header'    => Mage::helper('deals')->__('Sold'),
			'align'     =>'left',
			'index'     => 'sold',
			'width'		=>'50px',
		));


		$this->addColumn('status', array(
			'header'    => Mage::helper('deals')->__('Status'),
			'align'     => 'left',
			'width'     => '80px',
			'index'     => 'status',
			'type'      => 'options',
			'options'   => array(
				1 => 'Processing',
				2 => 'Running',
				3 => 'Done',
			),
			'renderer'	=> new MGS_Deals_Block_Adminhtml_Deals_Renderer_Status
		));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('deals')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
				'renderer'	=> new MGS_Deals_Block_Adminhtml_Deals_Renderer_Action,
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('deals')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('deals')->__('XML'));
	  
		return parent::_prepareColumns();
	}

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('deals_id');
        $this->getMassactionBlock()->setFormFieldName('deals');
        return $this;
    }

	public function getRowUrl($row)
	{
		$productId = $row->getProductId();
		$url =  Mage::helper('adminhtml')->getUrl('adminhtml/catalog_product/edit',array('id'=>$productId,'tab'=>'product_info_tabs_group_49', 'deal'=>1));
		return $url;
	}

}