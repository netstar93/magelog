<?php

class AW_Blog_Block_Tab extends AW_Blog_Block_Abstract
{
    public function getRecent($cat)
    {
        $collection = Mage::getModel('blog/blog')->getCollection()
            ->addEnableFilter(AW_Blog_Model_Status::STATUS_ENABLED)
            ->addStoreFilter()
            ->setOrder('created_time', 'desc')
        ;

        if ($this->getBlogCount()) {
            $collection->setPageSize($this->getBlogCount());
        }

		$collection->addCatsFilter($cat);

        foreach ($collection as $item) {
            $item->setAddress($this->getBlogUrl($item->getIdentifier()));
        }
        return $collection;
    }
	
	public function getCategoryTabs(){
		$categories = $this->getCategories();
		$categories = explode(',',$categories);
		return $categories;
	}
	
	public function getCategoryName($catId){
		$cat = Mage::getModel('blog/cat')->load($catId);
		return $cat->getTitle();
	}
}