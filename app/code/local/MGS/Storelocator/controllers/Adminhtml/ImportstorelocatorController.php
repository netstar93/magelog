<?php
/**
 * Import store locator admin controller
 * 
 * @category    MGS
 * @package     MGS_Storelocator
 * @author      MGS Magento Team <magento@mgstechnologies.co.in>
 */
class MGS_Storelocator_Adminhtml_ImportstorelocatorController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Custom constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        // Define module dependent translate
        $this->setUsedModuleName('MGS_Storelocator');
    }
    
    /**
     * Initialize layout.
     *
     * @return MGS_Storelocator_Adminhtml_ExportstorelocatorController
     */
    protected function _initAction()
    {
        $this->_title($this->__('Import/Export'))
            ->loadLayout()
            ->_setActiveMenu('mgs_storelocator/import_export');

        return $this;
    }
    
    /**
     * Check access (in the ACL) for current user
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')
                ->isAllowed('mgs_storelocator/import_export/import');
    }
    
    /**
     * Index action.
     *
     * @return void
     */
    public function indexAction()
    {
        $maxUploadSize = Mage::helper('mgs_storelocator')->getMaxUploadSize();
        $this->_getSession()->addNotice(
            $this->__('Size - Total size of uploadable files must not exceed %s', $maxUploadSize)
        );
        $this->_getSession()->addNotice(
            $this->__('Image - First upload image in the folder \'media/mgs_storelocator\' and then mention name of image in the csv file.')
        );
        $this->_getSession()->addNotice(
            $this->__('Sample CSV File Format - First export stores and use exported csv file as sample csv file.')
        );
        $this->_initAction()
            ->_title($this->__('Import'))
            ->_addBreadcrumb($this->__('Import'), $this->__('Import'));

        $this->renderLayout();
    }
    
    /**
     * Save action
     */
    public function saveAction()
    {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {
            $session    = Mage::getSingleton('adminhtml/session');
            $importFile = $_FILES['import_file'];
            try{
                if ($importFile['error'] == 0){
                    //check for csv file
                    $fileExtension = pathinfo($importFile['name'], PATHINFO_EXTENSION);
                    if(!empty($fileExtension) && $fileExtension == 'csv'){
                       $resultObj = Mage::getModel('mgs_storelocator/storelocator')->uploadAndImport($this);
                    }else{
                        Mage::throwException(Mage::helper('mgs_storelocator')->__('Invalid File Format'));
                    }
                }else{
                    Mage::throwException(Mage::helper('mgs_storelocator')->__('Error: ' . $importFile['error']));
                }
            } catch (Mage_Core_Exception $e) {
                $session->addError($e->getMessage());
            }
            $this->_redirect('*/*/');
        }
    }
}
