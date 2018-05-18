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
class MGS_Lookbook_Block_Adminhtml_Lookbook extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_lookbook';
    $this->_blockGroup = 'lookbook';
    $this->_headerText = Mage::helper('lookbook')->__('Manager Lookbook');
    $this->_addButtonLabel = Mage::helper('lookbook')->__('Add Lookbook');
    parent::__construct();
  }
}