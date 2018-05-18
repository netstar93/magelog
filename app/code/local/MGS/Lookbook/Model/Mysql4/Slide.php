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
class MGS_Lookbook_Model_Mysql4_Slide extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the slide_id refers to the key field in your database table.
        $this->_init('lookbook/slide', 'slide_id');
    }
	
	protected function _afterSave(Mage_Core_Model_Abstract $object) {
		if(is_array($object->getLookbooks())){
			$itemTable = Mage::getSingleton('core/resource')->getTableName('mgs_lookbook_slide_items');
			$condition = $this->_getWriteAdapter()->quoteInto('slide_id = ?', $object->getId());
			$this->_getWriteAdapter()->delete($itemTable, $condition);
			
			if(count($object->getLookbooks())>0){
				foreach ($object->getLookbooks() as $lookbookId=>$lookbook) {
					$itemArray = array();
					$itemArray['slide_id'] = $object->getId();
					$itemArray['lookbook_id'] = $lookbookId;
					$itemArray['position'] = $lookbook['position'];
					$this->_getWriteAdapter()->insert($itemTable, $itemArray);
				}
			}
		}

        return parent::_afterSave($object);
    }
}