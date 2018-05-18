<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 */
class MGS_Lookbook_Model_Mysql4_Lookbook_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('lookbook/lookbook');
    }
	
	public function addSliderFilter($sliderId) {
		$itemTable = Mage::getSingleton('core/resource')->getTableName('mgs_lookbook_slide_items');
        $this->getSelect()->join(
                        array('items' => $itemTable),
                        'main_table.lookbook_id = items.lookbook_id',
                        array()
                )
                ->where('items.slide_id = '.$sliderId)
				->order('items.position ASC');
        return $this;
    }
}