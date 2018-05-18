<?php
class MGS_Mpanel_EditController extends Mage_Core_Controller_Front_Action
{
    public function popupAction()
    {
		$this->loadLayout();
		$this->renderLayout();
    }
	
	public function blockAction(){
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function headerAction(){
		if($data = $this->getRequest()->getPost()){
			$storeId = Mage::app()->getStore()->getId();
			$config = new Mage_Core_Model_Config();
			
			$iDefaultStoreId = Mage::app()
			->getWebsite()
			->getDefaultGroup()
			->getDefaultStoreId();
			
			if($data['header']==''){
				$data['header'] = 'header';
			}
			
			if($iDefaultStoreId == $storeId){
				$config->saveConfig('mgs_theme/general/header', $data['header']);
			}
			
			$config->saveConfig('mgs_theme/general/header', $data['header'], 'stores', $storeId);
			Mage::getSingleton('core/session')->setSaved(1);
		}
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function twitterAction(){
		if($data = $this->getRequest()->getPost()){
			$storeId = Mage::app()->getStore()->getId();
			$config = new Mage_Core_Model_Config();
			
			$iDefaultStoreId = Mage::app()
			->getWebsite()
			->getDefaultGroup()
			->getDefaultStoreId();
			
			try{
				
				if($iDefaultStoreId == $storeId){
					$config->saveConfig('mpanel/twitter/token', $data['token']);
					$config->saveConfig('mpanel/twitter/token_secret', $data['token_secret']);
					$config->saveConfig('mpanel/twitter/consumer_key', $data['consumer_key']);
					$config->saveConfig('mpanel/twitter/consumer_secret', $data['consumer_secret']);
					$config->saveConfig('mpanel/twitter/twitter_title', $data['twitter_title']);
					$config->saveConfig('mpanel/twitter/twitter_user', $data['twitter_user']);
					$config->saveConfig('mpanel/twitter/twitter_count', $data['twitter_count']);
					$config->saveConfig('mpanel/twitter/truncate', $data['truncate']);
				}
				
				$config->saveConfig('mpanel/twitter/token', $data['token'], 'stores', $storeId);
				$config->saveConfig('mpanel/twitter/token_secret', $data['token_secret'], 'stores', $storeId);
				$config->saveConfig('mpanel/twitter/consumer_key', $data['consumer_key'], 'stores', $storeId);
				$config->saveConfig('mpanel/twitter/consumer_secret', $data['consumer_secret'], 'stores', $storeId);
				
				$config->saveConfig('mpanel/twitter/twitter_title', $data['twitter_title'], 'stores', $storeId);
				$config->saveConfig('mpanel/twitter/twitter_user', $data['twitter_user'], 'stores', $storeId);
				$config->saveConfig('mpanel/twitter/twitter_count', $data['twitter_count'], 'stores', $storeId);
				$config->saveConfig('mpanel/twitter/truncate', $data['truncate'], 'stores', $storeId);
				Mage::getSingleton('core/session')->setSaved(1);
			}
			catch(Exception $e){
			
			}
		}
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function footerAction(){
		if($data = $this->getRequest()->getPost()){
			$storeId = Mage::app()->getStore()->getId();
			$config = new Mage_Core_Model_Config();
			
			$iDefaultStoreId = Mage::app()
			->getWebsite()
			->getDefaultGroup()
			->getDefaultStoreId();
			
			if($data['footer']==''){
				$data['footer'] = 'footer';
			}
			
			if($iDefaultStoreId == $storeId){
				$config->saveConfig('mgs_theme/general/footer', $data['footer']);
			}
			
			$config->saveConfig('mgs_theme/general/footer', $data['footer'], 'stores', $storeId);
			Mage::getSingleton('core/session')->setSaved(1);
		}
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function configAction(){
		$path = $this->getRequest()->getParam('path');
		$path = str_replace('-','/',$path);
		$value = $this->getRequest()->getParam('val');
		$storeId = Mage::app()->getStore()->getId();
		$config = new Mage_Core_Model_Config();
		
		$iDefaultStoreId = Mage::app()
			->getWebsite()
			->getDefaultGroup()
			->getDefaultStoreId();
		
		try{
		
			if($iDefaultStoreId == $storeId){
				$config->saveConfig($path, $value);
			}
			
			$config->saveConfig($path, $value, 'stores', $storeId);
			echo 'success';
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function colAction(){
		$id = $this->getRequest()->getParam('id');
		$col = $this->getRequest()->getParam('col');
		try{
			Mage::getModel('mpanel/childs')->setCol($col)->setId($id)->save();
		}catch(Exception $e){
			echo $e->getMessage();
		}
		if(!$this->getRequest()->isXmlHttpRequest()){
			$this->_redirectReferer();
			return;
		}
	}
	
	public function staticAction(){
		if(Mage::helper('mpanel')->acceptToUsePanel()){
			$this->loadLayout();
			$this->renderLayout();
		}else{
			$this->_redirectReferer();
		}
		
	}
	
	public function staticpostAction(){
	
		$iDefaultStoreId = Mage::app()
			->getWebsite()
			->getDefaultGroup()
			->getDefaultStoreId();
		
		$storeId = Mage::app()->getStore()->getId();
	
		$id = $this->getRequest()->getParam('id');
		$data = $this->getRequest()->getPost();
		$data['content'] = str_replace('<img', '<img class="img-responsive"', $data['content']);
		$block = Mage::getModel('cms/block');
		
		$oldBlock = Mage::getModel('cms/block')->setStoreId($storeId)->load($id);
		
		if($iDefaultStoreId == $storeId){
			$block->setData($oldBlock->getData())->setContent($data['content'])->setId($oldBlock->getId());
		}else{
			$block->setTitle($oldBlock->getTitle())
				->setIdentifier($oldBlock->getIdentifier())
				->setStores(array($storeId))
				->setStatus(1)
				->setContent($data['content']);
			
			$storeTable = Mage::getSingleton('core/resource')->getTableName('cms_block_store');
			$collection = Mage::getModel('cms/block')->getCollection()->addFieldToFilter('identifier', $oldBlock->getIdentifier());
			$collection->getSelect()
				->joinLeft(array('stores'=>$storeTable), 'main_table.block_id = stores.block_id', array('stores.store_id'))
				->where('stores.store_id = '.$storeId);
			
			if(count($collection)>0){
				$block->setId($collection->getFirstItem()->getId());
			}
		}
		
		try{
			$block->save();
			$result['content'] = $this->getLayout()->createBlock('cms/block')->setBlockId($block->getIdentifier())->toHtml();
			$result['result'] = 'success';
		}catch(Exception $e){
			$result['result'] = $e->getMessage();
		}
		echo json_encode($result);
	}
}