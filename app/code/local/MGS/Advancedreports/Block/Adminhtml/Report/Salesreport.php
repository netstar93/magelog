<?php

/* * ****************************************************
 * Package   : Advancedreports
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_Advancedreports_Block_Adminhtml_Report_Salesreport extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_blockGroup = 'advancedreports';
        $this->_controller = 'adminhtml_report_salesreport';
        $this->_headerText = Mage::helper('advancedreports')->__('Sales Report');
        parent::__construct();
        $this->_removeButton('add');
    }

}