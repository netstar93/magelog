<?php
class MGS_Promobanners_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/promobanners?id=15 
    	 *  or
    	 * http://site.com/promobanners/id/15 	
    	 */
    	/* 
		$promobanners_id = $this->getRequest()->getParam('id');

  		if($promobanners_id != null && $promobanners_id != '')	{
			$promobanners = Mage::getModel('promobanners/promobanners')->load($promobanners_id)->getData();
		} else {
			$promobanners = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($promobanners == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$promobannersTable = $resource->getTableName('promobanners');
			
			$select = $read->select()
			   ->from($promobannersTable,array('promobanners_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$promobanners = $read->fetchRow($select);
		}
		Mage::register('promobanners', $promobanners);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}