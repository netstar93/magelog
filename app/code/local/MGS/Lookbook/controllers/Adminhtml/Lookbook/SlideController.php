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
class MGS_Lookbook_Adminhtml_Lookbook_SlideController extends Mage_Adminhtml_Controller_Action
{
	protected function _isAllowed() {
        return true;
    }

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('mgscore')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Lookbook Slide'), Mage::helper('adminhtml')->__('Lookbook Slide'));
		
		return $this;
	}   
    

	public function indexAction() {
		$this->_initAction();
		$this->getLayout()->getBlock('head')->setTitle($this->__('Manage Slide'));
		$this->renderLayout();
	}

	public function editAction() {

        $id     = $this->getRequest()->getParam('id'); 

		$model  = Mage::getModel('lookbook/slide')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('slide_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('mgscore');
			
			if($model->getTitle()){
				$this->getLayout()->getBlock('head')->setTitle($this->__('%s / Lookbook Slider', $model->getTitle()));
			}
			else{
				$this->getLayout()->getBlock('head')->setTitle($this->__('New Lookbook Slider'));
			}

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Lookbook'), Mage::helper('adminhtml')->__('Lookbook'));

			$this->_addContent($this->getLayout()->createBlock('lookbook/adminhtml_slide_edit'))
				->_addLeft($this->getLayout()->createBlock('lookbook/adminhtml_slide_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lookbook')->__('Slide does not exist'));
			$this->_redirect('*/*/');
		}
      
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			if (isset($_FILES['next_image']['name']) && $_FILES['next_image']['name'] != '') {
                try {
                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('next_image');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png', 'JPG', 'JPEG', 'GIF', 'PNG'));
                    $uploader->setAllowRenameFiles(false);

                    // Set the file upload mode 
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders 
                    //	(file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);

                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS . 'lookbook' . DS . 'icons' . DS;
                    $uploader->save($path, $_FILES['next_image']['name']);
                } catch (Exception $e) {
                    
                }

                //this way the name is saved in DB
                $data['next_image'] = 'lookbook/icons/' . str_replace(' ', '_', $_FILES['next_image']['name']);
            } else {
                if (isset($data['next_image']['delete']) && $data['next_image']['delete'] == 1) {
                    $data['next_image'] = '';
                } else {
                    unset($data['next_image']);
                }
            }
			
			if (isset($_FILES['prev_image']['name']) && $_FILES['prev_image']['name'] != '') {
                try {
                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('prev_image');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png', 'JPG', 'JPEG', 'GIF', 'PNG'));
                    $uploader->setAllowRenameFiles(false);

                    // Set the file upload mode 
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders 
                    //	(file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);

                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS . 'lookbook' . DS . 'icons' . DS;
                    $uploader->save($path, $_FILES['prev_image']['name']);
                } catch (Exception $e) {
                    
                }

                //this way the name is saved in DB
                $data['prev_image'] = 'lookbook/icons/' . str_replace(' ', '_', $_FILES['prev_image']['name']);
            } else {
                if (isset($data['prev_image']['delete']) && $data['prev_image']['delete'] == 1) {
                    $data['prev_image'] = '';
                } else {
                    unset($data['prev_image']);
                }
            }
	  			
			$data['status'] = $data['slider_status'];
			$model = Mage::getModel('lookbook/slide');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
				
				
				if (isset($data['slider']['lookbook_ids'])) {
					$decode = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['slider']['lookbook_ids']);
					$model->setLookbooks($decode);
				}
			
			try {
				$model->save();
				
				
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('lookbook')->__('Slide was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lookbook')->__('Unable to find slide.'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('lookbook/slide');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
				
				$items = Mage::getModel('lookbook/item')
					->getCollection()
					->addFieldToFilter('slide_id', $this->getRequest()->getParam('id'));
				if(count($items)>0){
					foreach($items as $item){
						$item->delete();
					}
				}
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Slide was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $sliderIds = $this->getRequest()->getParam('lookbook');
        if(!is_array($sliderIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select slide(s)'));
        } else {
            try {
                foreach ($sliderIds as $slideId) {
                    $lookbook = Mage::getModel('lookbook/slide')->load($slideId);
                    $lookbook->delete();
					
					$items = Mage::getModel('lookbook/item')
						->getCollection()
						->addFieldToFilter('slide_id', $slideId);
					if(count($items)>0){
						foreach($items as $item){
							$item->delete();
						}
					}
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($sliderIds)
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
        $sliderIds = $this->getRequest()->getParam('lookbook');
        if(!is_array($sliderIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select slide(s)'));
        } else {
            try {
                foreach ($sliderIds as $sliderId) {
                    $lookbook = Mage::getSingleton('lookbook/slide')
                        ->load($sliderId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($sliderIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
	public function itemsAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('lookbook.edit.tab.items')
            ->setLookbookIds($this->getRequest()->getPost('lookbook_ids', null));
        $this->renderLayout();
    }
	
	public function lookbookGridAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('lookbook.edit.tab.items')
            ->setLookbookIds($this->getRequest()->getPost('lookbook_ids', null))
		;
        $this->renderLayout();
    }
	
	public function chooserlookbookAction()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $pagesGrid = $this->getLayout()->createBlock('lookbook/adminhtml_widget_lookbook', '', array(
            'id' => $uniqId,
        ));
        $this->getResponse()->setBody($pagesGrid->toHtml());
    }
	
	public function choosersliderAction()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $pagesGrid = $this->getLayout()->createBlock('lookbook/adminhtml_widget_slider', '', array(
            'id' => $uniqId,
        ));
        $this->getResponse()->setBody($pagesGrid->toHtml());
    }
}
