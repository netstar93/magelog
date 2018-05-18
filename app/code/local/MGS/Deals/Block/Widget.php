<?php
class MGS_Deals_Block_Widget
    extends Mage_Catalog_Block_Product
    implements Mage_Widget_Block_Interface
{

	protected function _construct()
    {
        parent::_construct();
	}
    /**
     * Produces digg link html
     *
     * @return string
     */
	protected function getDealList()
    {
		$product = array();
        $post = $this->getData('real_deal');
		$deal_ids = explode(",", $post);
		$deal = Mage::getModel('deals/deals')
			->getCollection()
			->addFieldToFilter('deals_id', array('in' => $deal_ids));
		 
		return $deal; 
		
    }
	
	public function getWidgetId() {
		$widgetTemp= $this->getData('template');
		$deal_list = $this->getData('real_deal');
		$deal_listids = explode(",", $deal_list);
		$deal_list_new = implode("",$deal_listids);
		if($widgetTemp == "mgs/deals/widget/grid.phtml")
			$widgetId = $deal_list_new."grid";
		else
			$widgetId = $deal_list_new."slide";
		return $widgetId;
	}
	
	public function getColumn() {
		$column = $this->getData('column');
		return $column;
	}
	
	public function getProductIds(){
		$deals = $this->getDealList();
		$arrIds = array();
		if(count($deals)>0){
			foreach($deals as $deal){
				$arrIds[] = $deal->getProductId();
			}
		}
		return $arrIds;
	}
	
	public function getCollection(){
		$productIds = $this->getProductIds();
        $collection = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToFilter('entity_id', array('in' => $productIds));
		
		Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
		return $collection;
	}
	
	protected function getProductCollection()
    {
		$perPage = str_replace(' ','',$this->getConfig('grid_per_page_values'));
		$arr = explode(',', $perPage);
		
		$pageSize = $arr[0];
		$curPage = 1;
		if($this->getRequest()->getParam('limit')){
			$pageSize = $this->getRequest()->getParam('limit');
		}
		if($this->getRequest()->getParam('p')){
			$curPage = $this->getRequest()->getParam('p');
		}
		$collection = $this->getCollection();
		$collection->setPageSize($pageSize)->setCurPage($curPage);
		return $collection;
    }
	
	protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getCollection()->load();
        return $this;
    }
}