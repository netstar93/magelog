<?php
class MGS_Lookbook_Block_Widget_Lookbook extends MGS_Lookbook_Block_Abstract implements Mage_Widget_Block_Interface
{
    /**
     * Storage for used widgets
     *
     * @var array
     */
    static protected $_widgetUsageMap = array();

    /**
     * Prepare block text and determine whether block output enabled or not
     * Prevent blocks recursion if needed
     *
     * @return Mage_Cms_Block_Widget_Block
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $lookbookId = $this->getData('lookbook_id');
        $blockHash = get_class($this) . $lookbookId;

        if (isset(self::$_widgetUsageMap[$blockHash])) {
            return $this;
        }
        self::$_widgetUsageMap[$blockHash] = true;

        if ($lookbookId) {
            $lookbook = Mage::getModel('lookbook/lookbook')
                ->load($lookbookId);
            if ($lookbook->getStatus()==1) {
                $this->setLookbook($lookbook);
            }
        }

        unset(self::$_widgetUsageMap[$blockHash]);
        return $this;
    }
}
