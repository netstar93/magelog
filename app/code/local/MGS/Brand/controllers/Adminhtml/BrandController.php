<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Adminhtml_BrandController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->_title($this->__('MGS'))
            ->_title($this->__('Brand'))
            ->_title($this->__('Manage Brands'));
        $this->loadLayout()
            ->_setActiveMenu('mgscore/brand/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('MGS'), Mage::helper('adminhtml')->__('MGS'))
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Brands'), Mage::helper('adminhtml')->__('Manage Brands'));
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function importAction()
    {
        $this->_title($this->__('MGS'))
            ->_title($this->__('Brand'))
            ->_title($this->__('Import'));
        $this->loadLayout()
            ->_setActiveMenu('mgscore/brand/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('MGS'), Mage::helper('adminhtml')->__('MGS'))
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Brands'), Mage::helper('adminhtml')->__('Import'));
        $this->renderLayout();
    }

    public function assignAction()
    {
        $this->_title($this->__('MGS'))
            ->_title($this->__('Brand'))
            ->_title($this->__('Assign Product(s) to Brand(s)'));
        $this->loadLayout()
            ->_setActiveMenu('mgscore/brand/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('MGS'), Mage::helper('adminhtml')->__('MGS'))
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Brands'), Mage::helper('adminhtml')->__('Assign Product(s) to Brand(s)'));
        $this->renderLayout();
    }

    public function importDataAction()
    {
        try {
            if ($this->getRequest()->getPost()) {
                if (isset($_FILES['import_file']['name']) && $_FILES['import_file']['name'] != '') {
                    $uploaderFile = new Varien_File_Uploader('import_file');
                    $uploaderFile->setAllowedExtensions(array('csv'));
                    $uploaderFile->setAllowRenameFiles(true);
                    $uploaderFile->setFilesDispersion(false);
                    $uploaderFilePath = Mage::getBaseDir('cache') . DS . 'mgs' . DS . 'brand' . DS . 'import' . DS . 'csv' . DS;
                    $uploaderFile->save($uploaderFilePath, $_FILES['import_file']['name']);
                    $filePath = $uploaderFilePath . $uploaderFile->getUploadedFileName();
                    $csv = new Varien_File_Csv();
                    $brands = $csv->getData($filePath);
                    $i = 0;
                    foreach ($brands as $brand) {
                        if ($i > 0) {
                            Mage::helper('brand')->import($brand);
                        }
                        $i++;
                    }
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
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('brand')->__('All brands were successfully imported.'));
                    $this->_redirect('*/*/index');
                }
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/import');
        }
    }

    public function assignDataAction()
    {
        try {
            if ($this->getRequest()->getPost()) {
                if (isset($_FILES['assign_file']['name']) && $_FILES['assign_file']['name'] != '') {
                    $uploaderFile = new Varien_File_Uploader('assign_file');
                    $uploaderFile->setAllowedExtensions(array('csv'));
                    $uploaderFile->setAllowRenameFiles(true);
                    $uploaderFile->setFilesDispersion(false);
                    $uploaderFilePath = Mage::getBaseDir('cache') . DS . 'mgs' . DS . 'brand' . DS . 'assign' . DS . 'csv' . DS;
                    $uploaderFile->save($uploaderFilePath, $_FILES['assign_file']['name']);
                    $filePath = $uploaderFilePath . $uploaderFile->getUploadedFileName();
                    $csv = new Varien_File_Csv();
                    $data = $csv->getData($filePath);
                    $i = 0;
                    foreach ($data as $row) {
                        if ($i > 0) {
                            Mage::helper('brand')->assign($row);
                        }
                        $i++;
                    }
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('brand')->__('All products were successfully assigned to brands.'));
                    $this->_redirect('*/*/index');
                }
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/assign');
        }
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('brand/brand')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('brand_data', $model);

            $this->_initAction();

            $this->_title($id ? $model->getTitle() : $this->__('New Brand'));

            $item = $id ? Mage::helper('catalog')->__('Edit Brand') : Mage::helper('catalog')->__('New Brand');

            $this->_addBreadcrumb($item, $item);

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('brand/adminhtml_brand_edit'))
                ->_addLeft($this->getLayout()->createBlock('brand/adminhtml_brand_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('brand')->__('Item does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            if (isset($_FILES['icon']['name']) && $_FILES['icon']['name'] != '') {
                try {
                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('icon');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(false);

                    // Set the file upload mode 
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders 
                    //	(file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);

                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS . 'mgs' . DS . 'brand' . DS;
                    $uploader->save($path, $_FILES['icon']['name']);
                } catch (Exception $e) {

                }

                //this way the name is saved in DB
                $data['icon'] = 'mgs/brand/' . str_replace(' ', '_', $_FILES['icon']['name']);
            } else {
                if (isset($data['icon']['delete']) && $data['icon']['delete'] == 1) {
                    $data['icon'] = '';
                } else {
                    unset($data['icon']);
                }
            }
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                try {
                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('image');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(false);

                    // Set the file upload mode 
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders 
                    //	(file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);

                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS . 'mgs' . DS . 'brand' . DS;
                    $uploader->save($path, $_FILES['image']['name']);
                } catch (Exception $e) {

                }

                //this way the name is saved in DB
                $data['image'] = 'mgs/brand/' . str_replace(' ', '_', $_FILES['image']['name']);
            } else {
                if (isset($data['image']['delete']) && $data['image']['delete'] == 1) {
                    $data['image'] = '';
                } else {
                    unset($data['image']);
                }
            }

            if (!isset($data['url_key']) || $data['url_key'] == '') {
                $data['url_key'] = Mage::getModel('catalog/product_url')->formatUrlKey($data['title']);
            }
            $data['status'] = $data['brand_status'];

            if ($this->getRequest()->getParam('id')) {
                $oldLabel = Mage::getModel('brand/brand')->load($this->getRequest()->getParam('id'))->getTitle();
            }

            $model = Mage::getModel('brand/brand');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            try {
                $model->save();

                // Save New Option
                if (!Mage::helper('brand')->getAttributeOptionValue('mgs_brand', $model->getTitle()) && !$this->getRequest()->getParam('id')) {
                    Mage::helper('brand')->addAttributeOption('mgs_brand', $model->getTitle());
                    $brandOption = Mage::helper('brand')->getAttributeOptionValue('mgs_brand', $model->getTitle());
                    Mage::getModel('brand/brand')->setOptionId($brandOption)->setId($model->getId())->save();
                }

                // Edit Label Of Brand
                if ($this->getRequest()->getParam('id')) {
                    $newModel = Mage::getModel('brand/brand')->load($this->getRequest()->getParam('id'));
                    $optionValue = $newModel->getOptionId();
                    if (($optionValue != NULL) && ($optionValue != 0)) {
                        if ($oldLabel != $newModel->getTitle()) {
                            Mage::helper('brand')->editAttributeOption('mgs_brand', $newModel);
                        }
                    } else {
                        $brandOption = Mage::helper('brand')->getAttributeOptionValue('mgs_brand', $oldLabel);
                        Mage::getModel('brand/brand')->setOptionId($brandOption)->setId($model->getId())->save();
                        $newModel = Mage::getModel('brand/brand')->load($this->getRequest()->getParam('id'));
                        if ($oldLabel != $newModel->getTitle()) {
                            Mage::helper('brand')->editAttributeOption('mgs_brand', $newModel);
                        }
                    }
                }

                $urlRewriteCollection = Mage::getModel('core/url_rewrite')
                    ->getCollection()
                    ->addFieldToFilter('id_path', 'brand/view/' . $model->getId());
                foreach ($urlRewriteCollection as $url) {
                    $url->delete();
                }
                $urlRewrite = Mage::getModel('core/url_rewrite')->loadByIdPath('brand/view/' . $model->getId())
                    ->setIdPath('brand/view/' . $model->getId());
                if (Mage::helper('brand')->urlKey() != '') {
                    $urlRewrite->setRequestPath(Mage::helper('brand')->urlKey() . '/' . $model->getUrlKey());
                } else {
                    $urlRewrite->setRequestPath('brand/' . $model->getUrlKey());
                }
                $urlRewrite->setTargetPath('brand/index/view/id/' . $model->getId());
                $urlRewrite->setIsSystem(1);
                $urlRewrite->save();
                if (isset($data['brand']['product_ids']) && ($data['brand']['product_ids'] != '' || $data['brand']['product_ids'] != null)) {
                    $decode = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['brand']['product_ids']);
                    $productIds = array();
                    foreach ($decode as $key => $value) {
                        $productIds[] = (int)$key;
                    }
                    $products = Mage::getModel('brand/product')->getCollection()
                        ->addFieldToFilter('brand_id', array('eq' => $model->getId()));
                    $productIdsInBrand = array();
                    foreach ($products as $product) {
                        $productIdsInBrand[] = (int)$product->getProductId();
                    }
                    $productIdsDelete = array_diff($productIdsInBrand, $productIds);
                    $productIdsInsert = array_diff($productIds, $productIdsInBrand);
                    $productsDelete = Mage::getModel('brand/product')->getCollection()
                        ->addFieldToFilter('product_id', array('in' => $productIdsDelete))
                        ->addFieldToFilter('brand_id', array('eq' => $model->getId()));
                    foreach ($productsDelete as $product) {
                        $productModel = Mage::getModel('catalog/product')->load($product->getProductId());
                        $productModel->setData('mgs_brand', '');
                        $productModel->save();
                        $product->delete();
                    }
                    foreach ($productIdsInsert as $id) {
                        $p = Mage::getModel('brand/product');
                        $p->setBrandId($model->getId());
                        $p->setProductId($id);
                        $p->save();
                        $productModel = Mage::getModel('catalog/product')->load($id);
                        $productModel->setData('mgs_brand', Mage::helper('brand')->getAttributeOptionValue('mgs_brand', $model->getTitle()));
                        $productModel->save();
                    }
                } else {
                    if (isset($data['brand']['product_ids']) && ($data['brand']['product_ids'] == '' || $data['brand']['product_ids'] == null)) {
                        $products = Mage::getModel('brand/product')->getCollection()
                            ->addFieldToFilter('brand_id', array('eq' => $model->getId()));
                        foreach ($products as $product) {
                            $productModel = Mage::getModel('catalog/product')->load($product->getProductId());
                            $productModel->setData('mgs_brand', '');
                            $productModel->save();
                            $product->delete();
                        }
                    }
                }
                $products = Mage::getModel('brand/product')->getCollection()
                    ->addFieldToFilter('brand_id', array('eq' => $model->getId()));
                $model->setData('number_of_products', (int)count($products));
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('brand')->__('Item was successfully saved.'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('brand')->__('Unable to find item to save.'));
        $this->_redirect('*/*/');
    }

    public function refreshUrlAction()
    {
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
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/');
            return;
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('brand')->__('All urls were successfully rewrited.'));
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('brand/brand')->load($this->getRequest()->getParam('id'));
                Mage::helper('brand')->deleteAttributeOptionValue('mgs_brand', $model);

                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                $urlRewriteCollection = Mage::getModel('core/url_rewrite')
                    ->getCollection()
                    ->addFieldToFilter('id_path', 'brand/view/' . $this->getRequest()->getParam('id'));
                foreach ($urlRewriteCollection as $url) {
                    $url->delete();
                }
                $productCollection = Mage::getModel('brand/product')->getCollection()
                    ->addFieldToFilter('brand_id', array('eq' => $this->getRequest()->getParam('id')));
                foreach ($productCollection as $product) {
                    $product->delete();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted.'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $brandIds = $this->getRequest()->getParam('brand');
        if (!is_array($brandIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($brandIds as $brandId) {
                    $brand = Mage::getModel('brand/brand')->load($brandId);
                    Mage::helper('brand')->deleteAttributeOptionValue('mgs_brand', $brand);
                    $urlRewriteCollection = Mage::getModel('core/url_rewrite')
                        ->getCollection()
                        ->addFieldToFilter('id_path', 'brand/view/' . $brand->getId());
                    foreach ($urlRewriteCollection as $url) {
                        $url->delete();
                    }
                    $productCollection = Mage::getModel('brand/product')->getCollection()
                        ->addFieldToFilter('brand_id', array('eq' => $brand->getId()));
                    foreach ($productCollection as $product) {
                        $product->delete();
                    }
                    $brand->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted.', count($brandIds)
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
        $brandIds = $this->getRequest()->getParam('brand');
        if (!is_array($brandIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($brandIds as $brandId) {
                    $brand = Mage::getSingleton('brand/brand')
                        ->load($brandId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated.', count($brandIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function productAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('brand.edit.tab.product')
            ->setProductIds($this->getRequest()->getPost('product_ids', null));
        $this->renderLayout();
    }

    public function productGridAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('brand.edit.tab.product')
            ->setProductIds($this->getRequest()->getPost('product_ids', null));
        $this->renderLayout();
    }

    public function exportCsvAction()
    {
        $fileName = 'brand.csv';
        $content = $this->getLayout()->createBlock('brand/adminhtml_brand_export')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

}
