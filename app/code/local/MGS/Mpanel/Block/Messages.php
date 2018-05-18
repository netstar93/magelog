<?php
/**
 * Magento
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
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Core
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Messages block
 *
 * @category   Mage
 * @package    Mage_Core
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class MGS_Mpanel_Block_Messages extends Mage_Core_Block_Messages
{
     /**
     * Retrieve messages in HTML format grouped by type
     *
     * @param   string $type
     * @return  string
     */
    public function getGroupedHtml()
    {
        $types = array(
            Mage_Core_Model_Message::ERROR,
            Mage_Core_Model_Message::WARNING,
            Mage_Core_Model_Message::NOTICE,
            Mage_Core_Model_Message::SUCCESS
        );
		$html = '';
		
		if(Mage::app()->getStore()->isAdmin()){
			foreach ($types as $type) {
				if ( $messages = $this->getMessages($type) ) {
					if ( !$html ) {
						$html .= '<' . $this->_messagesFirstLevelTagName . ' class="messages">';
					}
					$html .= '<' . $this->_messagesSecondLevelTagName . ' class="' . $type . '-msg">';
					$html .= '<' . $this->_messagesFirstLevelTagName . '>';

					foreach ( $messages as $message ) {
						$html.= '<' . $this->_messagesSecondLevelTagName . '>';
						$html.= '<' . $this->_messagesContentWrapperTagName . '>';
						$html.= ($this->_escapeMessageFlag) ? $this->escapeHtml($message->getText()) : $message->getText();
						$html.= '</' . $this->_messagesContentWrapperTagName . '>';
						$html.= '</' . $this->_messagesSecondLevelTagName . '>';
					}
					$html .= '</' . $this->_messagesFirstLevelTagName . '>';
					$html .= '</' . $this->_messagesSecondLevelTagName . '>';
				}
			}
			if ( $html) {
				$html .= '</' . $this->_messagesFirstLevelTagName . '>';
			}
		}
		else{
			if(Mage::getStoreConfig('mpanel/general/enabled')){
				foreach ($types as $type) {
					if ( $messages = $this->getMessages($type) ) {
						if ( !$html ) {
							$html .= '<div class="row"><div class="col-lg-12"><div class="alert alert-'.str_replace('error','danger',$type).'">';
						}
						
						foreach ( $messages as $message ) {
							$html.= ($this->_escapeMessageFlag) ? $this->escapeHtml($message->getText()) : $message->getText();
						}

					}
				}
				if ( $html) {
					$html .= '</div><div class="clear"></div></div></div>';
				}
			}
			else{
				foreach ($types as $type) {
					if ( $messages = $this->getMessages($type) ) {
						if ( !$html ) {
							$html .= '<' . $this->_messagesFirstLevelTagName . ' class="messages">';
						}
						$html .= '<' . $this->_messagesSecondLevelTagName . ' class="' . $type . '-msg">';
						$html .= '<' . $this->_messagesFirstLevelTagName . '>';

						foreach ( $messages as $message ) {
							$html.= '<' . $this->_messagesSecondLevelTagName . '>';
							$html.= '<' . $this->_messagesContentWrapperTagName . '>';
							$html.= ($this->_escapeMessageFlag) ? $this->escapeHtml($message->getText()) : $message->getText();
							$html.= '</' . $this->_messagesContentWrapperTagName . '>';
							$html.= '</' . $this->_messagesSecondLevelTagName . '>';
						}
						$html .= '</' . $this->_messagesFirstLevelTagName . '>';
						$html .= '</' . $this->_messagesSecondLevelTagName . '>';
					}
				}
				if ( $html) {
					$html .= '</' . $this->_messagesFirstLevelTagName . '>';
				}
			}
		}
        return $html;
    }
}
