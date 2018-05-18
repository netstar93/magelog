<?php
class MGS_Portfolio_Block_Portfolio extends Mage_Core_Block_Template
{
	public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::helper('portfolio')->getEnabled()) {
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
        }
    }
	public function getMenu(){
		$menu = Mage::getModel('portfolio/category')->getCollection();

		foreach ($menu as $cate) {
            $cate->setLinkCate($this->getUrl($this->helper('portfolio')->getPortfolioUrl($cate)));
        }
		return $menu;
	}
	
	public function getPortfolio(){
		$id = $this->getRequest()->getParam('id');
		$model = Mage::getModel('portfolio/portfolio')->load($id);
		return $model;
	}
	
	public function getPortfolios(){
		$portfolios = Mage::getModel('portfolio/portfolio')
			->getCollection()
			->addFieldToFilter('status', 1);
		
		if($this->getPortfolioCount()>0){
			$portfolios->setPageSize($this->getPortfolioCount());
		}
		
		/* $category = $this->getCategories();
			
		if($category != ''){
			$storeTable = Mage::getSingleton('core/resource')->getTableName('mgs_portfolio_category_items');
			$portfolios->getSelect()->distinct()->joinLeft(array('store'=>$storeTable), 'main_table.portfolio_id = store.portfolio_id', array(''))
				->where('category_id in ('.$category.')');
		} */
		foreach ($portfolios as $portfolio) {
            $portfolio->setAddress($this->getUrl($this->helper('portfolio')->getPortfolioUrl($portfolio)));
        }

		return $portfolios;
	}
	public function getPortfoliosForCate($id){
		$portfolios = Mage::getModel('portfolio/portfolio')
			->getCollection()
			->addFieldToFilter('status', 1);
			
			$storeTable = Mage::getSingleton('core/resource')->getTableName('mgs_portfolio_category_items');
			$portfolios->getSelect()->joinLeft(array('store'=>$storeTable), 'main_table.portfolio_id = store.portfolio_id', array('store.category_id'))
				->where('category_id = '.$id);
		foreach ($portfolios as $portfolio) {
            $portfolio->setAddress($this->getUrl($this->helper('portfolio')->getPortfolioUrl($portfolio)));
        }
		
		return $portfolios;
	}
}