<?php
/**
 * Store locator admin helper
 * 
 * @category    MGS
 * @package     MGS_Storelocator
 * @author      MGS Magento Team
 */
class MGS_Storelocator_Helper_Admin extends Mage_Core_Helper_Abstract
{
    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    public function isActionAllowed($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('mgs_storelocator/mgs_manage_storelocator/' . $action);
    }
}