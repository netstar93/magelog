<?php 
class MGS_Deals_Adminhtml_WidgetController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Chooser Source action
     */
    public function chooserAction()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $massAction = $this->getRequest()->getParam('use_massaction', false);
        $productTypeId = $this->getRequest()->getParam('product_type_id', null);

        $productsGrid = $this->getLayout()->createBlock('deals/adminhtml_catalog_product_widget_chooser', '', array(
            'id'                => $uniqId,
            'use_massaction' => $massAction,
            'product_type_id' => $productTypeId,
            'category_id'       => $this->getRequest()->getParam('category_id')
        ));

        $html = $productsGrid->toHtml();

        if (!$this->getRequest()->getParam('products_grid')) {
			$block = $this->getLayout()->createBlock('adminhtml/widget_grid_serializer');
			$block = $block->initSerializerBlock('adminhtml.catalog.product.widget.chooser', 'getSelectedRelatedProducts', 'widget[deals]', 'widget_deals');
            $html = $this->getLayout()->createBlock('deals/adminhtml_catalog_product_widget_chooser_container')
                ->setGridHtml($html)
				->append($block)
                ->toHtml();
        }

        $this->getResponse()->setBody($html);
    }
	public function chooserGridAction()
    {
		$uniqId = $this->getRequest()->getParam('uniq_id');
        $massAction = $this->getRequest()->getParam('use_massaction', false);
        $productTypeId = $this->getRequest()->getParam('product_type_id', null);
        $this->loadLayout();
        $this->getLayout()->getBlock('adminhtml.catalog.product.widget.chooser', '', array(
            'id'                => $uniqId,
            'use_massaction' => $massAction,
            'product_type_id' => $productTypeId,
            'category_id'       => $this->getRequest()->getParam('category_id')
        ));
        $this->renderLayout();
    }
}