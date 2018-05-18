<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Adminhtml_Question_Edit_Tab_Answer extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('answerGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {
        $questionId = $this->getRequest()->getParam('id');
        $collection = Mage::getModel('productquestions/answer')->getCollection()
                ->addFieldToFilter('question_id', array('eq' => $questionId));
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
        
        $this->addColumn('a_content', array(
            'header' => Mage::helper('productquestions')->__('Answer Content'),
            'align' => 'left',
            'index' => 'a_content',
        ));

        $this->addColumn('a_customer_name', array(
            'header' => Mage::helper('productquestions')->__('Customer Name'),
            'align' => 'left',
            'index' => 'a_customer_name',
        ));

        $this->addColumn('a_customer_email', array(
            'header' => Mage::helper('productquestions')->__('Customer Email'),
            'align' => 'left',
            'index' => 'a_customer_email',
        ));        

        $this->addColumn('a_created_at', array(
            'header' => Mage::helper('productquestions')->__('Created At'),
            'index' => 'a_created_at',
            'type' => 'datetime',
            'width' => '100px',
        ));
        
        $this->addColumn('a_updated_at', array(
            'header' => Mage::helper('productquestions')->__('Updated At'),
            'index' => 'a_updated_at',
            'type' => 'datetime',
            'width' => '100px',
        ));

        $this->addColumn('is_admin', array(
            'header' => Mage::helper('productquestions')->__('Is Admin'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'is_admin',
            'type' => 'options',
            'options' => array(
                1 => 'Yes',
                0 => 'No',
            ),
        ));

        $this->addColumn('a_status', array(
            'header' => Mage::helper('productquestions')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'a_status',
            'type' => 'options',
            'options' => array(
                1 => 'Pending',
                2 => 'Approved',
                3 => 'Declined',
            ),
        ));

        $this->addColumn('a_score', array(
            'header' => Mage::helper('productquestions')->__('Score'),
            'align' => 'left',
            'index' => 'a_score',
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('productquestions')->__('Action'),
            'width' => '100',
            'filter' => false,
            'sortable' => false,
            'renderer' => 'productquestions/adminhtml_question_renderer_action',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return 'javascript:editPopup(' . $row->getId() . ');';
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/answerGrid', array('_current' => true));
    }

    public function getMainButtonsHtml() {
        $html = parent::getMainButtonsHtml();
        $addButton = $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('productquestions')->__('Add New'),
                            'onclick' => 'newPopup()',
                            'class' => 'scalable add'
                        ))->toHtml();
        return $addButton . $html;
    }

}
