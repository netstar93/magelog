<?php

/* * ****************************************************
 * Package   : Event
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Event_Block_Event_View extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getEvent() {
        $params = $this->getRequest()->getParams();
        $model = Mage::getModel('event/event')->load($params['id']);
        return $model;
    }

}
