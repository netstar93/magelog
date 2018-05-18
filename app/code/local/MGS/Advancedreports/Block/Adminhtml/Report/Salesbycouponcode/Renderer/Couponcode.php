<?php

/* * ****************************************************
 * Package   : Advancedreports
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_Advancedreports_Block_Adminhtml_Report_Salesbycouponcode_Renderer_Couponcode extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $couponCode = $row->getData('coupon_code');
        if ($couponCode != null) {
            $html = $couponCode;
        } else {
            $html = 'Not set';
        }
        return $html;
    }

}