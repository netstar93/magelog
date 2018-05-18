<?php

/* * ****************************************************
 * Package   : Advancedreports
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_Advancedreports_Block_Adminhtml_Report_Salesbycustomergroup_Grid extends Mage_Adminhtml_Block_Report_Grid_Abstract {

    protected $_columnGroupBy = 'customer_group';

    public function __construct() {
        parent::__construct();
        $this->setCountTotals(true);
    }

    public function getResourceCollectionName() {
        return 'advancedreports/report_salesbycustomergroup_collection';
    }

    protected function _prepareColumns() {
        $this->addColumn('customer_group', array(
            'header' => Mage::helper('advancedreports')->__('Customer Group'),
            'index' => 'customer_group',
            'type' => 'number',
            'sortable' => false,
            'renderer' => 'MGS_Advancedreports_Block_Adminhtml_Report_Salesbycustomergroup_Renderer_Customergroup',
            'totals_label' => Mage::helper('advancedreports')->__('Total'),
            'html_decorators' => array('nobr')
        ));

        $this->addColumn('percent', array(
            'header' => Mage::helper('advancedreports')->__('Percent'),
            'align' => 'right',
            'index' => 'percent',
            'type' => 'text',
            'renderer' => 'MGS_Advancedreports_Block_Adminhtml_Report_Salesbycustomergroup_Renderer_Percent',
            'sortable' => false,
            'totals_label' => Mage::helper('advancedreports')->__(null),
        ));

        $this->addColumn('total_item_count', array(
            'header' => Mage::helper('advancedreports')->__('Number of Orders'),
            'align' => 'right',
            'index' => 'total_item_count',
            'type' => 'number',
            'total' => 'sum',
            'sortable' => false
        ));

        $currencyCode = $this->getCurrentCurrencyCode();

        $this->addColumn('grand_total', array(
            'header' => Mage::helper('advancedreports')->__('Total'),
            'align' => 'right',
            'index' => 'grand_total',
            'currency_code' => $currencyCode,
            'width' => '100px',
            'type' => 'currency',
            'total' => 'sum',
            'sortable' => false
        ));

        $this->addExportType('*/*/exportSalesByCustomerGroupCsv', Mage::helper('advancedreports')->__('CSV'));
        $this->addExportType('*/*/exportSalesByCustomerGroupExcel', Mage::helper('advancedreports')->__('Excel XML'));

        return parent::_prepareColumns();
    }

}