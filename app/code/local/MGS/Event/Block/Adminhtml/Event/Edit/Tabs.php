<?php

/* * ****************************************************
 * Package   : Event
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Event_Block_Adminhtml_Event_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('event_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('event')->__('Event'));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => Mage::helper('event')->__('General'),
            'title' => Mage::helper('event')->__('General'),
            'content' => $this->getLayout()->createBlock('event/adminhtml_event_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}
