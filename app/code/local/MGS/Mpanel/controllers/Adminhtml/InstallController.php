<?php

class MGS_Mpanel_Adminhtml_InstallController extends Mage_Adminhtml_Controller_Action
{

	public function indexAction() {
		$params = $this->getRequest()->getParams();
		
		//Save theme config 
		$this->saveThemeConfig($params);
		
		//Import require static block
		$filePath = Mage::getBaseDir() . '/app/code/local/MGS/Mpanel/data/themes/' . $params['theme'] . '/theme_static.xml';
		
		$filePath1 = Mage::getBaseDir() . '/app/code/local/MGS/Mpanel/data/themes/' . $params['theme'] . '/default_config.xml';
		
		if (is_readable($filePath))
		{
			$config = new Varien_Simplexml_Config($filePath);
			try{
				foreach ($config->getNode('item') as $child)
				{
					$collection = Mage::getModel('cms/block')->getCollection()
						->addFieldToFilter('identifier', $child->identifier)
						->load();
					
					if (count($collection) > 0){
						foreach ($collection as $_item)
							$_item->delete();
					}
					
					Mage::getModel('cms/block')
						->setTitle($child->title)
						->setIdentifier($child->identifier)
						->setContent($child->content)
						->setIsActive(1)
						->setStores(array(0))
						->save();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mpanel')->__('%s theme was successfully installed.', $params['theme']));
				
				//if(Mage::getStoreConfig('mgs_theme/install/data_demo')){
					$this->installDataDemo($params['theme']);
				//}
			}catch(Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}else{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mpanel')->__('Can not install %s theme.', $params['theme']));
		}
		
		if (is_readable($filePath1))
		{
			$config1 = new Varien_Simplexml_Config($filePath1);
			
			try{
				foreach ($config1->getNode('section') as $child1)
				{
					$arrConfig = json_decode(json_encode($child1), true);
					foreach($arrConfig as $section=>$data){
						$this->saveSettings($section, $data);
					}
				}
			}catch(Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		
		
		$this->_redirectReferer();
        return;
	}
	
	public function restoreAction(){
		$params = $this->getRequest()->getParams();
		
		$filePath = Mage::getBaseDir() . '/app/code/local/MGS/Mpanel/data/themes/' . $params['theme'] . '/default_config.xml';
		if (is_readable($filePath))
		{
			$config = new Varien_Simplexml_Config($filePath);
			
			try{
				foreach ($config->getNode('section') as $child)
				{
					$arrConfig = json_decode(json_encode($child), true);
					foreach($arrConfig as $section=>$data){
						$this->saveSettings($section, $data);
					}
				}
			}catch(Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}else{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mpanel')->__('Can not restore default theme configuration.'));
		}
		$this->_redirectReferer();
        return;
	}
	
	// Save settings
	public function saveSettings($section, $data){
		$config = new Mage_Core_Model_Config();
		foreach($data as $group=>$_group){
			foreach($_group as $field=>$value){
				$config->saveConfig($section.'/'.$group.'/'.$field, $value);
			}
		}
	}
	
	public function saveThemeConfig($data){
		$groups = array();
		$groups['package']['fields']['name']['value'] = 'mgstheme';
		$groups['theme']['fields']['default']['value'] = $data['theme'];
		
		$section = 'design';
		$website = $this->getRequest()->getParam('website');
		$store   = $this->getRequest()->getParam('store');
		Mage::getSingleton('adminhtml/config_data')
			->setSection($section)
			->setWebsite($website)
			->setStore($store)
			->setGroups($groups)
			->save();
		
		$groups1 = array();
		$groups1['general']['fields']['enabled']['value'] = 1;
		
		$section1 = 'mpanel';
		Mage::getSingleton('adminhtml/config_data')
			->setSection($section1)
			->setWebsite($website)
			->setStore($store)
			->setGroups($groups1)
			->save();
	}
	
	public function installDataDemo($theme){
		// Install cms page demo
		$filePath = Mage::getBaseDir() . '/app/code/local/MGS/Mpanel/data/themes/' . $theme . '/cms_page.xml';
		if (is_readable($filePath))
		{
			$config = new Varien_Simplexml_Config($filePath);
			try{
				foreach($config->getNode('item') as $child)
				{
					$collection = Mage::getModel('cms/page')->getCollection()
						->addFieldToFilter('identifier', $child->identifier)
						->load();
					
					if (count($collection) > 0){
						foreach ($collection as $_item)
							$_item->delete();
					}
					
					Mage::getModel('cms/page')
						->setTitle($child->title)
						->setIdentifier($child->identifier)
						->setIsActive(1)
						->setContentHeading($child->content_heading)
						->setRootTemplate($child->root_template)
						->setContent($child->content)
						->setStores(array(0))
						->save();
				}
			}catch(Exception $e){
				
			}
		}
		
		// Install theme layout demo data
		$filePath = Mage::getBaseDir() . '/app/code/local/MGS/Mpanel/data/themes/' . $theme . '/theme_layout.xml';
		if (is_readable($filePath))
		{
			$config = new Varien_Simplexml_Config($filePath);
			try{
				foreach($config->getNode('item') as $child)
				{
					$collection = Mage::getModel('mpanel/block')->getCollection()
						->addFieldToFilter('page_type', $child->page_type)
						->addFieldToFilter('page_id', $child->page_id)
						->addFieldToFilter('place', $child->place)
						->load();
					
					if (count($collection) > 0){
						foreach ($collection as $_item)
							$_item->delete();
					}
					
					Mage::getModel('mpanel/block')
						->setPageType($child->page_type)
						->setPageId($child->page_id)
						->setPlace($child->place)
						->setType($child->type)
						->setTitle($child->title)
						->setOptions($child->options)
						->setSortOrder($child->sort_order)
						->save();
				}
			}catch(Exception $e){
				
			}
		}
	}
	
	
	// Set active for homepage by store 
	public function setHomeActive($storeId, $homeName){
		$homeStore = Mage::getModel('mpanel/store')
			->getCollection()
			->addFieldToFilter('store_id', $storeId);
		
		$store = Mage::getModel('mpanel/store');
		$store->setName($homeName);
		$store->setStoreId($storeId);
		$store->setStatus(1);
		if(count($homeStore)>0){
			$store->setId($homeStore->getFirstItem()->getId());
		}
		$store->save();
		return;
	}
	
	// reset data for mgs_theme_home_blocks table
	public function resetDataBlock($storeId, $homeName, $xmlObj){
		// Delete old data from mgs_theme_home_blocks table
		$deleteBlock = Mage::getModel('mpanel/blocks')
			->getCollection()
			->addFieldToFilter('store_id', $storeId)
			->addFieldToFilter('theme_name', $homeName);
			
		if(count($deleteBlock)>0){
			foreach($deleteBlock as $block){
				$block->delete();
			}
		}
		/*------------------------------------------------*/
		
		// Add new data to mgs_theme_home_blocks table
		foreach($xmlObj->getNode('block') as $_block)
		{	
			Mage::getModel('mpanel/blocks')
				->setName($_block->name)
				->setThemeName($homeName)
				->setBlockCols($_block->block_cols)
				->setBlockClass($_block->block_class)
				->setClass($_block->custom_class)
				->setBackground($_block->background)
				->setBackgroundImage($_block->background_image)
				->setBackgroundRepeat($_block->background_repeat)
				->setBackgroundCover($_block->background_cover)
				->setParallax($_block->parallax)
				->setFullwidth($_block->fullwidth)
				->setPaddingTop($_block->padding_top)
				->setPaddingBottom($_block->padding_bottom)
				->setStoreId($storeId)
				->setBlockPosition($_block->block_position)
				->save();
		}
		return;
	}
	
	//Delete all child block by store
	public function deleteChildBlock($storeId, $homeName){
		// Delete old data from mgs_theme_home_block_childs table
		$deleteChild = Mage::getModel('mpanel/childs')
			->getCollection()
			->addFieldToFilter('home_name', $homeName)
			->addFieldToFilter('store_id', $storeId);
			
		if(count($deleteChild)>0){
			foreach($deleteChild as $child){
				$child->delete();
			}
		}
		return;
	}
	
	public function setHeader($storeId, $header){
		$config = new Mage_Core_Model_Config();
		$config->saveConfig('mgs_theme/general/header', $header, 'stores', $storeId);
	}
	
	public function setFooter($storeId, $footer){
		$config = new Mage_Core_Model_Config();
		$config->saveConfig('mgs_theme/general/footer', $footer, 'stores', $storeId);
	}
	
	public function demoAction(){
		
		$allStores = Mage::app()->getStores();
		
		$storeId = 0;
		if($storeCode = $this->getRequest()->getParam('store')){
			$storeId = Mage::app()->getStore($storeCode)->getId();
		}

		$theme = $this->getRequest()->getParam('theme');
		$home = $this->getRequest()->getParam('home');
		
		$filePath = Mage::getBaseDir() . '/app/code/local/MGS/Mpanel/data/homes/'.$theme.'/'.$home.'.xml';
		
		if (is_readable($filePath))
		{
			$xmlObj = new Varien_Simplexml_Config($filePath);
			try{
				$xmlData = $xmlObj->getNode();
				$homeName = $xmlData->name;
				$header = $xmlData->header;
				$footer = $xmlData->footer;
				
				// Set home active
				if($storeId){
					$this->setHomeActive($storeId, $homeName);
					$this->resetDataBlock($storeId, $homeName, $xmlObj);
					$this->deleteChildBlock($storeId, $homeName);
					$this->setHeader($storeId, $header);
					$this->setFooter($storeId, $footer);
				}else{
					foreach ($allStores as $_eachStoreId => $val)
					{
						$this->setHomeActive($_eachStoreId, $homeName);
						$this->resetDataBlock($_eachStoreId, $homeName, $xmlObj);
						$this->deleteChildBlock($_eachStoreId, $homeName);
						$this->setHeader($_eachStoreId, $header);
						$this->setFooter($_eachStoreId, $footer);
					}
				}

				/*------------------------------------------------*/
				
				// Add new data to mgs_theme_home_blocks table
				foreach($xmlObj->getNode('child') as $_child)
				{
					$staticId = 0;
					$blockContent = $_child->block_content;
					
					$setting = $_child->setting;
					
					if($_child->type == 'promo_banner'){
						$bannerTitle = $_child->banner_title;
						$bannerFilename = $_child->banner_filename;
						$bannerUrl = $_child->banner_url;
						$bannerContent = $_child->banner_content;
						$bannerButton = $_child->banner_button;
						$bannerTextAlign = $_child->banner_text_align;
						
						$banner = Mage::getModel('promobanners/promobanners')
							->getCollection()
							->addFieldToFilter('title', $bannerTitle)
							->addFieldToFilter('filename', $bannerFilename)
							->addFieldToFilter('url', $bannerUrl)
							->addFieldToFilter('content', $bannerContent)
							->addFieldToFilter('text_align', $bannerTextAlign)
							->getFirstItem();
						if(!$banner->getId()){
							$banner = Mage::getModel('promobanners/promobanners')
								->setTitle($bannerTitle)
								->setFilename($bannerFilename)
								->setUrl($bannerUrl)
								->setContent($bannerContent)
								->setButton($bannerButton)
								->setTextAlign($bannerTextAlign)
								->save();
						}
						
						$bannerId = $banner->getId();
						$blockContent = str_replace('"%s"', '"'.$bannerId.'"', $blockContent);
						$setting = str_replace('"%s"', '"'.$bannerId.'"', $setting);
					}
					
					if($_child->type=='static'){
						$cmsBlock = array(
							'title' => $_child->static_block_title,
							'identifier' => $_child->static_block_identifier,
							'content' => $_child->static_block_content,
							'is_active' => 1,
							'stores' => array($storeId)
						);
						
						if($this->getBlockIdByIndentifier($_child->static_block_identifier, $storeId) == ''){
							$staticBlock = Mage::getModel('cms/block')->setData($cmsBlock)->save();
						}
						else{
							$staticBlock = Mage::getModel('cms/block')->setData($cmsBlock)->setId($this->getBlockIdByIndentifier($_child->static_block_identifier, $storeId))->save();
						}
						
						$staticId = $staticBlock->getId();
					}
					
					if($storeId){
						Mage::getModel('mpanel/childs')
							->setBlockName($_child->block_name)
							->setStaticBlockId($staticId)
							->setHomeName($homeName)
							->setType($_child->type)
							->setPosition($_child->position)
							->setSetting($setting)
							->setCol($_child->col)
							->setClass($_child->custom_class)
							->setBlockContent($blockContent)
							->setStoreId($storeId)
							->save();
					}else{
						foreach ($allStores as $_eachStoreId => $val)
						{
							Mage::getModel('mpanel/childs')
								->setBlockName($_child->block_name)
								->setStaticBlockId($staticId)
								->setHomeName($homeName)
								->setType($_child->type)
								->setPosition($_child->position)
								->setSetting($setting)
								->setCol($_child->col)
								->setClass($_child->custom_class)
								->setBlockContent($blockContent)
								->setStoreId($_eachStoreId)
								->save();
						}
					}
				}
				/*------------------------------------------------*/
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mpanel')->__('%s of %s theme was successfully imported.', $this->convertThemeName($home), $this->convertThemeName($theme)));
				
			}catch(Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		else{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mpanel')->__('Can not import demo data.'));
		}

		$this->_redirectReferer();
        return;
	}
	
	public function getBlockIdByIndentifier($indetifier, $storeId) {
        $collection = Mage::getModel('cms/block')->getCollection();
        $storeTable = Mage::getSingleton('core/resource')->getTableName('cms_block_store');
        $collection->getSelect()
                ->joinLeft(array('store' => $storeTable), 'main_table.block_id =store.block_id', array('store.store_id'))
                ->where('identifier="' . $indetifier . '"')
                ->where('store_id IN (?)', array(0, $storeId))
                ->order('store_id DESC');
        return $collection->getFirstItem()->getId();
    }
	
	public function convertThemeName($theme){
		$themeName = str_replace('_',' ',$theme);
		return ucfirst($themeName);
	}
}