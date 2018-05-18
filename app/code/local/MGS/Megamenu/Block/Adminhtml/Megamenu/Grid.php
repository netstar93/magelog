<?php

class MGS_Megamenu_Block_Adminhtml_Megamenu_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('megamenuGrid');
      $this->setDefaultSort('position');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('megamenu/megamenu')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
	  $this->addColumn('position', array(
          'header'    => Mage::helper('megamenu')->__('Position'),
          'align'     =>'left',
          'width'     => '80px',
          'index'     => 'position',
      ));
	  
	  $parents = Mage::getModel('megamenu/parent')->getCollection();
	  $arrParent = array();
	  foreach($parents as $_parent){
		$arrParent[$_parent->getId()] = $_parent->getTitle();
	  }
	  
	  $this->addColumn('title', array(
          'header'    => Mage::helper('megamenu')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));
	  
	  $this->addColumn('parent_id', array(
          'header'    => Mage::helper('megamenu')->__('Menu'),
          'align'     => 'left',
          'width'     => '400px',
          'index'     => 'parent_id',
          'type'      => 'options',
          'options'   => $arrParent
      ));
	  

      
	  
	  $arr_type = array(1 => Mage::helper('megamenu')->__('Catalog Category'), 2 => Mage::helper('megamenu')->__('Static Content'));
	  
	  $this->addColumn('menu_type', array(
          'header'    => Mage::helper('megamenu')->__('Menu Type'),
          'align'     => 'left',
          'width'     => '150px',
          'index'     => 'menu_type',
          'type'      => 'options',
          'options'   => $arr_type
      ));
	  
	  $this->addColumn('columns', array(
          'header'    => Mage::helper('megamenu')->__('Columns'),
          'align'     =>'left',
          'width'     => '80px',
          'index'     => 'columns',
      ));
	  
	  /* $arr_align = array('left' => Mage::helper('megamenu')->__('Align Left'), 'right' => Mage::helper('megamenu')->__('Align Right'));
	  
	  $this->addColumn('align_menu', array(
          'header'    => Mage::helper('megamenu')->__('Align Menu Item'),
          'align'     => 'left',
          'width'     => '200px',
          'index'     => 'align_menu',
          'type'      => 'options',
          'options'   => $arr_align
      )); */

      $this->addColumn('status', array(
          'header'    => Mage::helper('megamenu')->__('Status'),
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
                'header'    =>  Mage::helper('megamenu')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true,
				'renderer'  => new MGS_Megamenu_Block_Adminhtml_Megamenu_Renderer_Action
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('megamenu')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('megamenu')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('megamenu_id');
        $this->getMassactionBlock()->setFormFieldName('megamenu');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('megamenu')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('megamenu')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('megamenu/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('megamenu')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('megamenu')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId(), 'store'=>$row->getStore()));
  }

}