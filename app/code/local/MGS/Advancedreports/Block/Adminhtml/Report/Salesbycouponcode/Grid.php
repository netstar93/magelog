<?php

/* * ****************************************************
 * Package   : Advancedreports
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_Advancedreports_Block_Adminhtml_Report_Salesbycouponcode_Grid extends Mage_Adminhtml_Block_Report_Grid_Abstract {

    protected $_columnGroupBy = 'coupon_code';

    public function __construct() {
        parent::__construct();
        $this->setCountTotals(true);
    }

    public function getResourceCollectionName() {
        return 'advancedreports/report_salesbycouponcode_collection';
    }

    protected function _prepareColumns() {
        $this->addColumn('coupon_code', array(
            'header' => Mage::helper('advancedreports')->__('Coupon Code'),
            'index' => 'coupon_code',
            'type' => 'number',
            'sortable' => false,
            'renderer' => 'MGS_Advancedreports_Block_Adminhtml_Report_Salesbycouponcode_Renderer_Couponcode',
            'totals_label' => Mage::helper('advancedreports')->__('Total'),
            'html_decorators' => array('nobr')
        ));

        $this->addColumn('total_item_count', array(
            'header' => Mage::helper('advancedreports')->__('Orders'),
            'align' => 'right',
            'index' => 'total_item_count',
            'type' => 'number',
            'total' => 'sum',
            'sortable' => false
        ));

        $this->addColumn('total_qty_ordered', array(
            'header' => Mage::helper('advancedreports')->__('Items'),
            'align' => 'right',
            'index' => 'total_qty_ordered',
            'type' => 'number',
            'total' => 'sum',
            'sortable' => false
        ));

        $currencyCode = $this->getCurrentCurrencyCode();

        $this->addColumn('subtotal', array(
            'header' => Mage::helper('advancedreports')->__('Subtotal'),
            'align' => 'right',
            'index' => 'subtotal',
            'currency_code' => $currencyCode,
            'width' => '100px',
            'type' => 'currency',
            'total' => 'sum',
            'sortable' => false
        ));

        $this->addColumn('tax_amount', array(
            'header' => Mage::helper('advancedreports')->__('Tax'),
            'align' => 'right',
            'index' => 'tax_amount',
            'currency_code' => $currencyCode,
            'width' => '100px',
            'type' => 'currency',
            'total' => 'sum',
            'sortable' => false
        ));

        $this->addColumn('shipping_amount', array(
            'header' => Mage::helper('advancedreports')->__('Shipping'),
            'align' => 'right',
            'index' => 'shipping_amount',
            'currency_code' => $currencyCode,
            'width' => '100px',
            'type' => 'currency',
            'total' => 'sum',
            'sortable' => false
        ));

        $this->addColumn('discount_amount', array(
            'header' => Mage::helper('advancedreports')->__('Discount'),
            'align' => 'right',
            'index' => 'discount_amount',
            'currency_code' => $currencyCode,
            'width' => '100px',
            'type' => 'currency',
            'total' => 'sum',
            'sortable' => false
        ));

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

        $this->addColumn('total_invoiced', array(
            'header' => Mage::helper('advancedreports')->__('Invoiced'),
            'align' => 'right',
            'index' => 'total_invoiced',
            'currency_code' => $currencyCode,
            'width' => '100px',
            'type' => 'currency',
            'total' => 'sum',
            'sortable' => false
        ));

        $this->addColumn('base_total_refunded', array(
            'header' => Mage::helper('advancedreports')->__('Refunded'),
            'align' => 'right',
            'index' => 'base_total_refunded',
            'currency_code' => $currencyCode,
            'width' => '100px',
            'type' => 'currency',
            'total' => 'sum',
            'sortable' => false
        ));

        $this->addExportType('*/*/exportSalesByCouponCodeCsv', Mage::helper('advancedreports')->__('CSV'));
        $this->addExportType('*/*/exportSalesByCouponCodeExcel', Mage::helper('advancedreports')->__('Excel XML'));

        return parent::_prepareColumns();
    }

}