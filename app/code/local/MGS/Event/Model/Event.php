<?php

/* * ****************************************************
 * Package   : Event
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Event_Model_Event extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('event/event');
    }

}
