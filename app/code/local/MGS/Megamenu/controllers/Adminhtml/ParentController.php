<?php

class MGS_Megamenu_Adminhtml_ParentController extends Mage_Adminhtml_Controller_Action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('mgscore/megamenu/parent')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Menu'), Mage::helper('adminhtml')->__('Manage Menu'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction();
		$this->_addContent($this->getLayout()->createBlock('megamenu/adminhtml_parent'));
		$this->getLayout()->getBlock('head')->setTitle($this->__('Manage Menu'));
		$this->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('megamenu/parent')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('parent_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('mgscore/megamenu/parent');

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			
			if($model->getTitle()){
				$this->getLayout()->getBlock('head')->setTitle($this->__('%s / Megamenu', $model->getTitle()));
			}
			else{
				$this->getLayout()->getBlock('head')->setTitle($this->__('New Megamenu'));
			}

			$this->_addContent($this->getLayout()->createBlock('megamenu/adminhtml_parent_edit'))
				->_addLeft($this->getLayout()->createBlock('megamenu/adminhtml_parent_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megamenu')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
	  			
			$model = Mage::getModel('megamenu/parent');
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('megamenu')->__('Menu was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megamenu')->__('Unable to find menu to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			if($this->getRequest()->getParam('id') == 1) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__("You can't delete main menu."));
			}else{
				try {
					$model = Mage::getModel('megamenu/parent');
					 
					$model->setId($this->getRequest()->getParam('id'))
						->delete();
						 
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Menu was successfully deleted'));
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
        $megamenuIds = $this->getRequest()->getParam('megamenu');
        if(!is_array($megamenuIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
				$i=0;
                foreach ($megamenuIds as $megamenuId) {
					if($megamenuId!=1){
						$i++;
						$megamenu = Mage::getModel('megamenu/parent')->load($megamenuId);
						$megamenu->delete();
					}
                }
				if($i==0){
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__("You can't delete main menu."));
				}
				else{
					Mage::getSingleton('adminhtml/session')->addSuccess(
						Mage::helper('adminhtml')->__(
							'Total of %d menu(s) were successfully deleted', $i
						)
					);
				}
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $megamenuIds = $this->getRequest()->getParam('megamenu');
        if(!is_array($megamenuIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select menu'));
        } else {
            try {
				$i=0;
                foreach ($megamenuIds as $megamenuId) {
					if($megamenuId!=1){
						$i++;
						$megamenu = Mage::getSingleton('megamenu/parent')
							->load($megamenuId)
							->setStatus($this->getRequest()->getParam('status'))
							->setIsMassupdate(true)
							->save();
					}
                }
				if($i==0){
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__("You can't change status of main menu."));
				}
				else{
					$this->_getSession()->addSuccess(
						$this->__('Total of %d menu(s) were successfully updated', count($megamenuIds))
					);
				}
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}