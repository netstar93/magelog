<?php

class MGS_Abrands_Block_Adminhtml_Abrands_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('abrandsGrid');
      $this->setDefaultSort('abrands_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('abrands/abrands')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('abrands_id', array(
          'header'    => Mage::helper('abrands')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'abrands_id',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('abrands')->__('Brand'),
          'align'     =>'left',
          'index'     => 'title',
      ));
	  
	  $this->addColumn('content', array(
          'header'    => Mage::helper('abrands')->__('Link'),
          'align'     =>'left',
          'index'     => 'content',
      ));

	  
      $this->addColumn('filename', array(
			'header'    => Mage::helper('abrands')->__('Image'),
			'width'     => '150px',
			'index'     => 'filename',
			'renderer'	=> new MGS_Abrands_Block_Adminhtml_Abrands_Renderer_Image,
			'filter'    => false,
			'sortable'  => false,
      ));
	  

      $this->addColumn('status', array(
          'header'    => Mage::helper('abrands')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('abrands')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('abrands')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('abrands')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('abrands')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('abrands_id');
        $this->getMassactionBlock()->setFormFieldName('abrands');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('abrands')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('abrands')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('abrands/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('abrands')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('abrands')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}