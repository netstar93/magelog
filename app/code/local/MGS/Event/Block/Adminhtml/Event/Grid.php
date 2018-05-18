<?php

/* * ****************************************************
 * Package   : Event
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Event_Block_Adminhtml_Event_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('eventGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('event/event')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('id', array(
            'header' => Mage::helper('event')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('event')->__('Title'),
            'align' => 'left',
            'index' => 'title',
        ));

        $this->addColumn('image', array(
            'header' => Mage::helper('event')->__('Image'),
            'align' => 'center',
            'width' => '150px',
            'index' => 'image',
            'filter' => false,
            'sortable' => false,
            'renderer' => 'MGS_Event_Block_Adminhtml_Event_Renderer_Image',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('stores', array(
                'header' => Mage::helper('event')->__('Store View'),
                'index' => 'stores',
                'type' => 'store',
                'store_all' => true,
                'store_view' => true,
                'sortable' => false,
                'filter_condition_callback'
                => array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('location', array(
            'header' => Mage::helper('event')->__('Location'),
            'align' => 'left',
            'index' => 'location',
        ));

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $this->addColumn('time_from', array(
            'header' => Mage::helper('event')->__('Time From'),
            'index' => 'time_from',
            'type' => 'date',
            'width' => '100px',
            'format' => $dateFormatIso,
            'time' => true,
        ));

        $this->addColumn('time_to', array(
            'header' => Mage::helper('event')->__('Time To'),
            'index' => 'time_to',
            'type' => 'date',
            'width' => '100px',
            'format' => $dateFormatIso,
            'time' => true,
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('event')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('event')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('event')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('event');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('event')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('event')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('event/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('event')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('event')->__('Status'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }

    protected function _afterLoadCollection() {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column) {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
