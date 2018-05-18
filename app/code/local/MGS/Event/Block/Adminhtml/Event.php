<?php

/* * ****************************************************
 * Package   : Event
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Event_Block_Adminhtml_Event extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_event';
        $this->_blockGroup = 'event';
        $this->_headerText = Mage::helper('event')->__('Manage Events');
        $this->_addButtonLabel = Mage::helper('event')->__('Add Event');
        $this->_addButton('refresh', array(
            'label' => 'Refresh Url Rewrite',
            'onclick' => 'setLocation(\'' . $this->getRefreshUrl() . '\')',
            'class' => 'scalable',
        ));
        parent::__construct();
    }

    public function getRefreshUrl() {
        return $this->getUrl('*/*/refreshUrl');
    }

}
