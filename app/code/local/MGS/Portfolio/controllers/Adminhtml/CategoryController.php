<?php

class MGS_Portfolio_Adminhtml_CategoryController extends Mage_Adminhtml_Controller_Action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('mgscore/portfolio/category')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Portfolio Category'), Mage::helper('adminhtml')->__('Manage Portfolio Category'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction();
		$this->_addContent($this->getLayout()->createBlock('portfolio/adminhtml_category'));
		$this->getLayout()->getBlock('head')->setTitle($this->__('Manage Portfolio Category'));
		$this->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('portfolio/category')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('category_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('mgscore/portfolio/category');

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			
			if($model->getTitle()){
				$this->getLayout()->getBlock('head')->setTitle($this->__('%s / Portfolio', $model->getCategoryName()));
			}
			else{
				$this->getLayout()->getBlock('head')->setTitle($this->__('New Category'));
			}

			$this->_addContent($this->getLayout()->createBlock('portfolio/adminhtml_category_edit'))
				->_addLeft($this->getLayout()->createBlock('portfolio/adminhtml_category_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('portfolio')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
	  			
			$model = Mage::getModel('portfolio/category');
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('portfolio')->__('Category was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('portfolio')->__('Unable to find category to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			if($this->getRequest()->getParam('id') == 1) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__("You can't delete main category."));
			}else{
				try {
					$model = Mage::getModel('portfolio/category');
					 
					$model->setId($this->getRequest()->getParam('id'))
						->delete();
						 
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Category was successfully deleted'));
					$this->_redirect('*/*/');
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				}
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $portfolioIds = $this->getRequest()->getParam('portfolio');
        if(!is_array($megamenuIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
				$i=0;
                foreach ($portfolioIds as $portfolioId) {

						$i++;
						$portfolio = Mage::getModel('portfolio/category')->load($portfolioId);
						$portfolio->delete();

                }


					Mage::getSingleton('adminhtml/session')->addSuccess(
						Mage::helper('adminhtml')->__(
							'Total of %d menu(s) were successfully deleted', $i
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
        $portfolioIds = $this->getRequest()->getParam('portfolio');
        if(!is_array($portfolioIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select category'));
        } else {
            try {
				$i=0;
                foreach ($portfolioIds as $portfolioId) {

						$i++;
						$portfolio = Mage::getSingleton('portfolio/category')
							->load($portfolioId)
							->setStatus($this->getRequest()->getParam('status'))
							->setIsMassupdate(true)
							->save();

                }
				
					$this->_getSession()->addSuccess(
						$this->__('Total of %d category(s) were successfully updated', count($portfolioIds))
					);

            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}