<?php

class MGS_Portfolio_Block_Adminhtml_Portfolio_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('portfolioGrid');
      $this->setDefaultSort('portfolio_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('portfolio/portfolio')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('portfolio_id', array(
          'header'    => Mage::helper('portfolio')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'portfolio_id',
      ));

      $this->addColumn('name', array(
          'header'    => Mage::helper('portfolio')->__('Name'),
          'align'     =>'left',
          'index'     => 'name',
      ));
	  
	  $this->addColumn('client', array(
          'header'    => Mage::helper('portfolio')->__('Client'),
          'align'     =>'left',
          'index'     => 'client',
      ));
	  
	  $this->addColumn('services', array(
          'header'    => Mage::helper('portfolio')->__('Project'),
          'align'     =>'left',
          'index'     => 'services',
      ));

      $this->addColumn('status', array(
          'header'    => Mage::helper('portfolio')->__('Status'),
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
                'header'    =>  Mage::helper('portfolio')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('portfolio')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('portfolio')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('portfolio')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('portfolio_id');
        $this->getMassactionBlock()->setFormFieldName('portfolio');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('portfolio')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('portfolio')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('portfolio/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('portfolio')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('portfolio')->__('Status'),
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