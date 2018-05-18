<?php

class MGS_Deals_Block_Product_List extends Mage_Catalog_Block_Product_List {

    public function getDeals() {
        $deal = Mage::getModel('deals/deals')
                ->getCollection()
                ->addFieldToFilter('status', 2);
        return $deal;
    }

    public function getProductIds() {
        $deals = $this->getDeals();
        $arrIds = array();
        if (count($deals) > 0) {
            foreach ($deals as $deal) {
                $arrIds[] = $deal->getProductId();
            }
        }
        return $arrIds;
    }

    public function getCollection() {
        $order = "name";
        $dir = "asc";
        if ($this->getRequest()->getParam('order')) {
            $order = $this->getRequest()->getParam('order');
        }
        if ($this->getRequest()->getParam('dir')) {
            $dir = $this->getRequest()->getParam('dir');
        }

        $productIds = $this->getProductIds();
        $collection = Mage::getModel('catalog/product')
                ->getCollection()
                ->addAttributeToFilter('entity_id', array('in' => $productIds))
                ->addAttributeToSort($order, $dir);

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        return $collection;
    }

    protected function getProductCollection() {
        $perPage = str_replace(' ', '', $this->getConfig('grid_per_page_values'));
        $arr = explode(',', $perPage);

        $pageSize = $arr[0];
        $curPage = 1;
        if ($this->getRequest()->getParam('limit')) {
            $pageSize = $this->getRequest()->getParam('limit');
        }
        if ($this->getRequest()->getParam('p')) {
            $curPage = $this->getRequest()->getParam('p');
        }
        $collection = $this->getCollection();
        $collection->setPageSize($pageSize)->setCurPage($curPage);
        ;
        return $collection;
    }
	
	public function getMode(){
		$mode = 'list';
		if($this->getRequest()->getParam('mode')){
			$mode = $this->getRequest()->getParam('mode');
		}
		return $mode;
	}

    protected function _prepareLayout() {
        parent::_prepareLayout();

//        $perPage = str_replace(' ', '', $this->getConfig('grid_per_page_values'));
//        $arr = explode(',', $perPage);
//        $arrPerPage = array();
//        foreach ($arr as $page) {
//            $arrPerPage[$page] = $page;
//        }
//        if ($this->getConfig('all')) {
//            $arrPerPage['all'] = $this->__('All');
//        }
//
//        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
//        $pager->setAvailableLimit($arrPerPage);
//        $pager->setCollection($this->getCollection());
//        $this->setChild('pager', $pager);
//        $this->getCollection()->load();
//        return $this;
    }

    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }

    public function getToolbarHtml() {
        return $this->getChildHtml('toolbar');
    }

    public function getDeal($productId) {
        $deal = Mage::getModel('deals/deals')
                ->getCollection()
                ->addFieldToFilter('product_id', $productId)
                ->addFieldToFilter('status', 2)
                ->getFirstItem();
        return $deal;
    }

    public function getSold($productId) {
        return $this->getDeal($productId)->getSold();
    }

    public function getItemLeft($productId) {
        return $this->getDeal($productId)->getMaxDealQty();
    }

    public function getConfig($key) {
        return Mage::getStoreConfig('deals/deals_page/' . $key);
    }

    public function getToolbarBlock() {
        if ($blockName = $this->getToolbarBlockName()) {
            echo $this->getLayout()->getBlock($blockName);
            if ($block = $this->getLayout()->getBlock($blockName)) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, microtime());
        return $block;
    }

}