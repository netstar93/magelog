<?php
class MGS_Portfolio_Block_Category extends Mage_Core_Block_Template
{
	public function getPortfolios(){
		$portfolios = Mage::getModel('portfolio/portfolio')
			->getCollection()
			->addFieldToFilter('status', 1);
		
		/* if($id = $this->getRequest()->getParam('id')){
			$storeTable = Mage::getSingleton('core/resource')->getTableName('mgs_portfolio_category_items');
			$portfolios->getSelect()->joinLeft(array('store'=>$storeTable), 'main_table.portfolio_id = store.portfolio_id', array('store.category_id'))
				->where('category_id = '.$id);
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