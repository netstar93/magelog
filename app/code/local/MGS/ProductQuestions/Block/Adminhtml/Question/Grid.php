<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Adminhtml_Question_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('questionGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('productquestions/question')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('id', array(
            'header' => Mage::helper('productquestions')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'id',
        ));

        $this->addColumn('content', array(
            'header' => Mage::helper('productquestions')->__('Question Content'),
            'align' => 'left',
            'index' => 'content',
        ));

        $this->addColumn('topic_id', array(
            'header' => Mage::helper('productquestions')->__('Topic'),
            'align' => 'left',
            'index' => 'topic_id',
            'type' => 'options',
            'options' => Mage::getSingleton('productquestions/topic')->toOptionArray(),
        ));

        $this->addColumn('product_name', array(
            'header' => Mage::helper('productquestions')->__('Product Name'),
            'align' => 'left',
            'index' => 'product_name',
        ));

        $this->addColumn('customer_name', array(
            'header' => Mage::helper('productquestions')->__('Customer Name'),
            'align' => 'left',
            'index' => 'customer_name',
        ));

        $this->addColumn('customer_email', array(
            'header' => Mage::helper('productquestions')->__('Customer Email'),
            'align' => 'left',
            'index' => 'customer_email',
        ));

        $this->addColumn('created_at', array(
            'header' => Mage::helper('productquestions')->__('Created At'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '100px',
        ));

        $this->addColumn('updated_at', array(
            'header' => Mage::helper('productquestions')->__('Updated At'),
            'index' => 'updated_at',
            'type' => 'datetime',
            'width' => '100px',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('productquestions')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Pending',
                2 => 'Approved',
                3 => 'Declined',
            ),
        ));

        $this->addColumn('visibility', array(
            'header' => Mage::helper('productquestions')->__('Visibility'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'visibility',
            'type' => 'options',
            'options' => array(
                1 => 'Public',
                2 => 'Private',
            ),
        ));

        $this->addColumn('score', array(
            'header' => Mage::helper('productquestions')->__('Score'),
            'align' => 'left',
            'index' => 'score',
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('productquestions')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('productquestions')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('productquestions')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('productquestions')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('questions');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('productquestions')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('productquestions')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('productquestions/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('productquestions')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('productquestions')->__('Status'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

}
