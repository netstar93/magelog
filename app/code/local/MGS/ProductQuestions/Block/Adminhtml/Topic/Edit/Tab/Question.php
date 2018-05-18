<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Adminhtml_Topic_Edit_Tab_Question extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('questionGrid');
        $this->setDefaultSort('content');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
    }

    protected function _getTopic() {
        $topicId = $this->getRequest()->getParam('id');
        return Mage::getModel('productquestions/topic')->load($topicId);
    }

    protected function _getStore() {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _addColumnFilterToCollection($column) {
        if ($column->getId() == 'in_questions') {
            $questionIds = $this->_getSelectedQuestions();
            if (empty($questionIds)) {
                $questionIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('id', array('in' => $questionIds));
            } else {
                if ($questionIds) {
                    $this->getCollection()->addFieldToFilter('id', array('nin' => $questionIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    protected function _prepareCollection() {
        if ($this->_getTopic() && $this->_getTopic()->getId()) {
            $collection = Mage::getModel('productquestions/question')->getCollection()
                    ->addFieldToFilter(array('topic_id', 'topic_id'), array(
                array('topic_id', 'eq' => 'NULL'),
                array('topic_id', 'eq' => $this->_getTopic()->getId())));
        } else {
            $collection = Mage::getModel('productquestions/question')->getCollection()
                    ->addFieldToFilter('topic_id', array('eq' => 'NULL'));
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('in_questions', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'in_questions',
            'values' => $this->_getSelectedQuestions(),
            'align' => 'center',
            'index' => 'id'
        ));

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

        $this->addColumn('topic_status', array(
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

        $this->addColumn('position', array(
            'header' => Mage::helper('productquestions')->__('Position'),
            'name' => 'position',
            'type' => 'number',
            'validate_class' => 'validate-number',
            'index' => 'position',
            'width' => 60,
            'sortable' => false,
            'editable' => true,
            'edit_only' => $this->_getTopic()->getId()
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/questionGrid', array('_current' => true));
    }

    protected function _getSelectedQuestions() {
        $questionIds = $this->getQuestionIds();
        if (is_null($questionIds)) {
            $questionIds = array_keys($this->getSelectedQuestions());
        }
        return $questionIds;
    }

    public function getSelectedQuestions() {
        $questionIds = array();
        if ($this->_getTopic() && $this->_getTopic()->getId()) {
            $collection = Mage::getModel('productquestions/question')->getCollection()
                    ->addFieldToFilter('topic_id', $this->_getTopic()->getId());
            foreach ($collection as $question) {
                $questionIds[$question->getId()] = array('position' => 0);
            }
        }
        return $questionIds;
    }

}
