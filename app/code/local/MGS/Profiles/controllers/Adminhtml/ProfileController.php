<?php

class MGS_Profiles_Adminhtml_ProfileController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()->_setActiveMenu("profiles/profile")->_addBreadcrumb(Mage::helper("adminhtml")->__("Profile  Manager"), Mage::helper("adminhtml")->__("Profile Manager"));
        return $this;
    }

    public function indexAction() {
        $this->_title($this->__("Profiles"));
        $this->_title($this->__("Manager Profile"));

        $this->_initAction();
        $this->renderLayout();
    }

    public function editAction() {
        $this->_title($this->__("Profiles"));
        $this->_title($this->__("Profile"));
        $this->_title($this->__("Edit Profile"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("profiles/profile")->load($id);
        if ($model->getId()) {
            Mage::register("profile_data", $model);
            $this->loadLayout();
            $this->_setActiveMenu("profiles/profile");
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Profile Manager"), Mage::helper("adminhtml")->__("Profile Manager"));
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Profile Description"), Mage::helper("adminhtml")->__("Profile Description"));
            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock("profiles/adminhtml_profile_edit"))->_addLeft($this->getLayout()->createBlock("profiles/adminhtml_profile_edit_tabs"));
            $this->renderLayout();
        } else {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("profiles")->__("Profile does not exist."));
            $this->_redirect("*/*/");
        }
    }

    public function newAction() {

        $this->_title($this->__("Profiles"));
        $this->_title($this->__("Profile"));
        $this->_title($this->__("New Profile"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("profiles/profile")->load($id);

        $data = Mage::getSingleton("adminhtml/session")->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register("profile_data", $model);

        $this->loadLayout();
        $this->_setActiveMenu("profiles/profile");

        $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Profile Manager"), Mage::helper("adminhtml")->__("Profile Manager"));
        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Profile Description"), Mage::helper("adminhtml")->__("Profile Description"));


        $this->_addContent($this->getLayout()->createBlock("profiles/adminhtml_profile_edit"))->_addLeft($this->getLayout()->createBlock("profiles/adminhtml_profile_edit_tabs"));

        $this->renderLayout();
    }

    public function saveAction() {

        $post_data = $this->getRequest()->getPost();


        if ($post_data) {

            try {


                //save image
                try {

                    if ((bool) $post_data['photo']['delete'] == 1) {

                        $post_data['photo'] = '';
                    } else {

                        unset($post_data['photo']);

                        if (isset($_FILES)) {

                            if ($_FILES['photo']['name']) {

                                if ($this->getRequest()->getParam("id")) {
                                    $model = Mage::getModel("profiles/profile")->load($this->getRequest()->getParam("id"));
                                    if ($model->getData('photo')) {
                                        $io = new Varien_Io_File();
                                        $io->rm(Mage::getBaseDir('media') . DS . implode(DS, explode('/', $model->getData('photo'))));
                                    }
                                }
                                $path = Mage::getBaseDir('media') . DS . 'profiles' . DS . 'profile' . DS;
                                $uploader = new Varien_File_Uploader('photo');
                                $uploader->setAllowedExtensions(array('jpg', 'png', 'gif'));
                                $uploader->setAllowRenameFiles(false);
                                $uploader->setFilesDispersion(false);
                                $destFile = $path . $_FILES['photo']['name'];
                                $filename = $uploader->getNewFileName($destFile);
                                $uploader->save($path, $filename);

                                $post_data['photo'] = 'profiles/profile/' . $filename;
                            }
                        }
                    }
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
//save image


                $model = Mage::getModel("profiles/profile")
                        ->addData($post_data)
                        ->setId($this->getRequest()->getParam("id"))
                        ->save();

                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Profile was successfully saved"));
                Mage::getSingleton("adminhtml/session")->setProfileData(false);

                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/edit", array("id" => $model->getId()));
                    return;
                }
                $this->_redirect("*/*/");
                return;
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                Mage::getSingleton("adminhtml/session")->setProfileData($this->getRequest()->getPost());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
                return;
            }
        }
        $this->_redirect("*/*/");
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam("id") > 0) {
            try {
                $model = Mage::getModel("profiles/profile");
                $model->setId($this->getRequest()->getParam("id"))->delete();
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Profile was successfully deleted"));
                $this->_redirect("*/*/");
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
            }
        }
        $this->_redirect("*/*/");
    }

}
