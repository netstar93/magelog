<?php

class MGS_Lookbook_Model_Entity_Attribute_Backend_Lookbook
    extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{    
    /**
     * Get list of available block column proportions
     */
    public function getAllOptions()
    {
        if (!$this->_options)
        {
			$this->_options = array(
				array('value'=> '', 'label'=> '')
			);
			$lookbooks = Mage::getModel('lookbook/lookbook')->getCollection();
			if(count($lookbooks)>0){
				foreach($lookbooks as $lookbook){
					$this->_options[] = array(
						'value' => $lookbook->getId(),
						'label' => $lookbook->getName()
					);
				}
			}
        }
        return $this->_options;
    }
}