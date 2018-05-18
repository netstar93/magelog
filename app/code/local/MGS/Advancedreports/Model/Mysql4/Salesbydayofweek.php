<?php

/* * ****************************************************
 * Package   : Advancedreports
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_Advancedreports_Model_Mysql4_Salesbydayofweek extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        $this->_init('advancedreports/salesbyhour', 'd');
    }

    public function init($table, $field = 'd') {
        $this->_init($table, $field);
        return $this;
    }

}