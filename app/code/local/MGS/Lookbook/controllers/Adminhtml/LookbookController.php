<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 */
class MGS_Lookbook_Adminhtml_LookbookController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed() {
        return true;
    }

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('mgscore')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Lookbook'), Mage::helper('adminhtml')->__('Lookbook'));
		
		return $this;
	}   
    

	public function indexAction() {
		$this->_initAction();
		$this->getLayout()->getBlock('head')->setTitle($this->__('Manage Lookbook'));
		$this->renderLayout();
	}

	public function editAction() {

        $id     = $this->getRequest()->getParam('id'); 

		$model  = Mage::getModel('lookbook/lookbook')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('lookbook_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('mgscore');
			
			if($model->getName()){
				$this->getLayout()->getBlock('head')->setTitle($this->__('%s / Lookbook', $model->getName()));
			}
			else{
				$this->getLayout()->getBlock('head')->setTitle($this->__('New Lookbook'));
			}

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Lookbook'), Mage::helper('adminhtml')->__('Lookbook'));

			$this->_addContent($this->getLayout()->createBlock('lookbook/adminhtml_lookbook_edit'))
				->_addLeft($this->getLayout()->createBlock('lookbook/adminhtml_lookbook_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lookbook')->__('Lookbook does not exist'));
			$this->_redirect('*/*/');
		}
      
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {	
	  			
			$model = Mage::getModel('lookbook/lookbook');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
			 
                 if ($model->getId() && isset($data['identifier_create_redirect']))
                 {
                        $model->setData('save_rewrites_history', (bool)$data['identifier_create_redirect']);
                 }
             
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('lookbook')->__('Lookbook was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lookbook')->__('Unable to find lookbook.'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('lookbook/lookbook');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
				
				$items = Mage::getModel('lookbook/item')
					->getCollection()
					->addFieldToFilter('lookbook_id', $this->getRequest()->getParam('id'));
				if(count($items)>0){
					foreach($items as $item){
						$item->delete();
					}
				}
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Lookbook was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function uploadAction()
	{

           $upload_dir  = Mage::getBaseDir('media').'/lookbook/';
           if (!file_exists($upload_dir)) mkdir($upload_dir, 0755, true);
            
            $uploader = Mage::getModel('lookbook/fileuploader');

            $config_check = $uploader->checkServerSettings();

            if ($config_check === true){
               $result = $uploader->handleUpload($upload_dir); 
            } 
            else
            {
                $result = $config_check;
            }

            // to pass data through iframe you will need to encode all html tags
            $this->getResponse()->setBody(htmlspecialchars(json_encode($result), ENT_NOQUOTES));
	}

    
    public function checkproductAction(){
        	$sku     = $this->getRequest()->getParam('text');
            $product_id = Mage::getModel('catalog/product')->getIdBySku($sku);
			$product = Mage::getModel('catalog/product')->load($product_id);
            $status =  $product->getStatus();
			
			$result['label'] = 0;
			$labelPost = $this->getRequest()->getPost('label');
			$defaultPinText = Mage::getStoreConfig('lookbook/general/pin_default');
			
			if(Mage::getStoreConfig('lookbook/general/pin_price')){
				$price = strip_tags(Mage::helper('core')->currency($product->getFinalPrice()));
				$price = str_replace('.00','',$price);
				$result['label'] = $price;

				if($labelPost != ''){
					if(Mage::getStoreConfig('lookbook/general/pin_price') && ($labelPost != $price)){
						$result['label'] = $labelPost;
					}
				}
			}else{
				if($labelPost!=''){
					$result['label'] = $labelPost;
				}else{
					$result['label'] = $defaultPinText;
				}
			}
			
			
			
            if ($product_id) {
                if ($status==1) 
                {
                  $result['status'] = 1;
                }
                else
                {
                  $result['status'] = "is disabled";  
                }
                
            }
            else
            {
                $result['status'] = "doesn't exists"; 
            }
			$result = json_encode($result);
		$this->getResponse()->setBody($result);
    }
    
    public function massDeleteAction() {
        $lookbookIds = $this->getRequest()->getParam('lookbook');
        if(!is_array($lookbookIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select lookbook(s)'));
        } else {
            try {
                foreach ($lookbookIds as $lookbookId) {
                    $lookbook = Mage::getModel('lookbook/lookbook')->load($lookbookId);
                    $lookbook->delete();
					$items = Mage::getModel('lookbook/item')
						->getCollection()
						->addFieldToFilter('lookbook_id', $lookbookId);
					if(count($items)>0){
						foreach($items as $item){
							$item->delete();
						}
					}
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($lookbookIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $lookbookIds = $this->getRequest()->getParam('lookbook');
        if(!is_array($lookbookIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select lookbook(s)'));
        } else {
            try {
                foreach ($lookbookIds as $lookbookId) {
                    $lookbook = Mage::getSingleton('lookbook/lookbook')
                        ->load($lookbookId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($lookbookIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function loadproductAction(){
		$data = $this->getRequest()->getPost();
		$sku = $data['text'];
        
        $products = Mage::getModel('catalog/product')->getCollection()
			->addStoreFilter(Mage::app()->getStore()->getId())
			->addAttributeToFilter('status',1)
			->addAttributeToFilter('visibility',array('neq'=>1))
			->addFieldToFilter('sku', array('like' => '%' . $sku . '%'));
        $li = '';
        if (count($products) > 0) {
            foreach ($products as $product) {
                $li .= "<li onclick='setBlankPinLabel()'>" . $product->getSku() . "</li>" . "\n";
            }
            print "<ul>$li</ul>";
        }
	}
}
