<?php

class MGS_Mpanel_Model_Entity_Attribute_Backend_Tab
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
				array('value'=> '', 'label'=> 'Use Default Config'),
				array('value'=> '1', 'label'=> 'Tab'),
				array('value'=> '2', 'label'=> 'Accordion After Product Detail'),
				array('value'=> '3', 'label'=> 'Accordion After Product Information'),
			);
        }
        return $this->_options;
    }
}