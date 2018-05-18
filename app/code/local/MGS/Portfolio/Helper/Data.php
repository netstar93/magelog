<?php

class MGS_Portfolio_Helper_Data extends MGS_Mgscore_Helper_Data
{
	public function getThumbnail($portfolio){
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$portfolio->getThumbnailImage();
	}
	
	public function getBaseImage($portfolio){
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$portfolio->getBaseImage();
	}
	
	public function getCategories($portfolio){
		$collection = Mage::getModel('portfolio/stores')
			->getCollection()
			->addFieldToFilter('portfolio_id', $portfolio->getId());
		$catTable = Mage::getSingleton('core/resource')->getTableName('mgs_portfolio_category');
		$collection->getSelect()->joinLeft(array('cat'=>$catTable), 'main_table.category_id = cat.category_id', array('cat.category_name as name'));
		
		return $collection;
	}
	
	public function getCategoriesText($portfolio){
		$collection = $this->getCategories($portfolio);
		
		if(count($collection)>0){
			$arrResult = array();
			foreach($collection as $item){
				$arrResult[] = $item->getName();
			}
			return implode(', ', $arrResult);
		}
		return '';
	}
	
	public function getCategoriesLink($portfolio){
		$collection = $this->getCategories($portfolio);
		$html = '';
		if(count($collection)>0){
			$i=0;
			foreach($collection as $item){
				//print_r($item->getCategoryId());
				$cate = Mage::getModel('portfolio/category')->getCollection()->addFieldToFilter('category_id', array('eq' => $item->getCategoryId()))->getFirstItem();
				$i++;
				$html .= '<a href="'.Mage::getUrl('portfolios/').$cate->getIdentifier().'">'.$item->getName().'</a>';
				if($i<count($collection)){
					$html .= ', ';
				}
			}
		}
		return $html;
	}
	
	public function getRelatedPortfolio($portfolio){
		$collection = $this->getCategories($portfolio);
		if(count($collection)>0){
			$arrResult = array();
			foreach($collection as $item){
				$arrResult[] = $item->getCategoryId();
			}
			$catString = implode(', ', $arrResult);
			
			$portfolios = Mage::getModel('portfolio/portfolio')
				->getCollection()
				->addFieldToFilter('status', 1);
				
			if($catString != ''){
				$storeTable = Mage::getSingleton('core/resource')->getTableName('mgs_portfolio_category_items');
				$portfolios->getSelect()->distinct()->joinLeft(array('store'=>$storeTable), 'main_table.portfolio_id = store.portfolio_id', array(''))
					->where('category_id in ('.$catString.')')
					->where('main_table.portfolio_id <> '.$portfolio->getId());
			}
			return $portfolios;
		}
		return false;
	}
	
	public function getPortfolioUrl($portfolio = NUll) {
		return 'portfolios/'.$portfolio->getIdentifier();
	}
}