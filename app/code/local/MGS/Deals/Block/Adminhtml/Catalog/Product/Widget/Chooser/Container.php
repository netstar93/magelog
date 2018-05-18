<?php
class MGS_Deals_Block_Adminhtml_Catalog_Product_Widget_Chooser_Container extends Mage_Adminhtml_Block_Catalog_Product_Widget_Chooser_Container
{
    /**
     * Block construction
     *
     * @param array $arguments Object data
     */
    public function __construct($arguments=array())
    {
        parent::__construct($arguments);
        $this->setTemplate('deals/container.phtml');
    }
}