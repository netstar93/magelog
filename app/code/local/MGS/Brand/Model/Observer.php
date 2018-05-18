<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Model_Observer {
    /* public function addToTopmenu(Varien_Event_Observer $observer) {
      $versionInfo = Mage::getVersionInfo();
      if ((int) $versionInfo['major'] >= 1 && (int) $versionInfo['minor'] >= 7) {
      if (Mage::helper('brand')->addToTopmenu()) {
      $params = Mage::app()->getRequest()->getParams();
      $active = (Mage::app()->getRequest()->getRouteName() == 'brand' ? 'active' : '');
      $menu = $observer->getMenu();
      $tree = $menu->getTree();
      if (Mage::helper('brand')->titleLink() != '') {
      $name = Mage::helper('brand')->titleLink();
      } else {
      $name = 'Brand';
      }
      if (Mage::helper('brand')->urlKey() != '') {
      $urlKey = Mage::helper('brand')->urlKey();
      } else {
      $urlKey = 'brand';
      }
      $node = new Varien_Data_Tree_Node(array('name' => $name, 'is_active' => $active, 'id' => 'brand', 'url' => Mage::getUrl() . $urlKey), 'id', $tree, $menu);
      $menu->addChild($node);
      $collection = Mage::getModel('brand/brand')->getCollection()
      ->addFieldToFilter('status', array('eq' => 1))
      ->setOrder('priority', 'asc');
      foreach ($collection as $brand) {
      $active = ($brand->getId() == $params['id'] ? 'active' : '');
      $tree = $node->getTree();
      $data = array(
      'name' => $brand->getTitle(),
      'is_active' => $active,
      'id' => 'brand-' . $brand->getUrlKey(),
      'url' => Mage::getUrl() . $urlKey . '/' . $brand->getUrlKey()
      );
      $subNode = new Varien_Data_Tree_Node($data, 'id', $tree, $node);
      $node->addChild($subNode);
      }
      }
      }
      } */

    public function configChangedSectionBrand($observer) {
        try {
            $urlRewriteCollection = Mage::getModel('core/url_rewrite')
                    ->getCollection()
                    ->addFieldToFilter('id_path', 'brand/index/');
            foreach ($urlRewriteCollection as $url) {
                $url->delete();
            }
            $urlRewrite = Mage::getModel('core/url_rewrite')->loadByIdPath('brand/index')
                    ->setIdPath('brand/index');
            if (Mage::helper('brand')->urlKey() != '') {
                $urlRewrite->setRequestPath(Mage::helper('brand')->urlKey());
            } else {
                $urlRewrite->setRequestPath('brand');
            }
            $urlRewrite->setTargetPath('brand/index/index');
            $urlRewrite->setIsSystem(1);
            $urlRewrite->save();
            $collection = Mage::getModel('brand/brand')->getCollection();
            foreach ($collection as $brand) {
                $urlRewriteCollection = Mage::getModel('core/url_rewrite')
                        ->getCollection()
                        ->addFieldToFilter('id_path', 'brand/view/' . $brand->getId());
                foreach ($urlRewriteCollection as $url) {
                    $url->delete();
                }
                $urlRewrite = Mage::getModel('core/url_rewrite')->loadByIdPath('brand/view/' . $brand->getId())
                        ->setIdPath('brand/view/' . $brand->getId());
                if (Mage::helper('brand')->urlKey() != '') {
                    $urlRewrite->setRequestPath(Mage::helper('brand')->urlKey() . '/' . $brand->getUrlKey());
                } else {
                    $urlRewrite->setRequestPath('brand/' . $brand->getUrlKey());
                }
                $urlRewrite->setTargetPath('brand/index/view/id/' . $brand->getId());
                $urlRewrite->setIsSystem(1);
                $urlRewrite->save();
            }
        } catch (Exception $e) {
            
        }
    }
	
	public function assignBrand(Varien_Event_Observer $observer) {
		$product = $observer->getEvent()->getProduct();
		$productId = $product->getId();
		
		$collection = Mage::getModel('brand/product')
			->getCollection()
			->addFieldToFilter('product_id', $productId);
		if(count($collection)>0){
			foreach($collection as $item){
				$item->delete();
			}
		}
		
		$brand = $product->getMgsBrand();
		if($brand != ''){
			$brandId = Mage::getModel('brand/brand')->getCollection()->addFieldToFilter('option_id', $brand)->getFirstItem()->getId();
			Mage::getModel('brand/product')->setProductId($productId)->setBrandId($brandId)->save();
		}
	}
}
