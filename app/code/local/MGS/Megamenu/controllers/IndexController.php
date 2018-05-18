<?php
class MGS_Megamenu_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/megamenu?id=15 
    	 *  or
    	 * http://site.com/megamenu/id/15 	
    	 */
    	/* 
		$megamenu_id = $this->getRequest()->getParam('id');

  		if($megamenu_id != null && $megamenu_id != '')	{
			$megamenu = Mage::getModel('megamenu/megamenu')->load($megamenu_id)->getData();
		} else {
			$megamenu = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($megamenu == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$megamenuTable = $resource->getTableName('megamenu');
			
			$select = $read->select()
			   ->from($megamenuTable,array('megamenu_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$megamenu = $read->fetchRow($select);
		}
		Mage::register('megamenu', $megamenu);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}