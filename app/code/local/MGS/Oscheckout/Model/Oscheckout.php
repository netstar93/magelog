<?php
/**
 * @name         :  MGS One Step Checkout
 * @version      :  1.4
 * @since        :  Magento ver 1.4, 1.5, 1.6, 1.7
 * @author       :  MGS - http://www.mage-shop.com
 * @copyright    :  Copyright (C) 2011 Powered by MGS
 * @license      :  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Creation Date:  Sept 06 2012
 * 
 * */

class MGS_Oscheckout_Model_Oscheckout extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('oscheckout/oscheckout');
    }
    public function toOptionArray()
    {
        $colors = array('Country', 'Zip Code / Postal Code', 'State/region', 'City');
        $temp = array();

        foreach($colors as $color)	{
            $temp[] = array('label' => $color, 'value' => strtolower($color));
        }

        return $temp;
    }
}