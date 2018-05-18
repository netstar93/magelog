<?php

class MGS_Mpanel_Model_Entity_Attribute_Backend_Template
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
				array( 'value' => '', 'label'=> 'Use Default Config' ),
				array( 'value' => '0', 'label'=> 'Product standard layout' ),
				array( 'value' => '1', 'label' => 'Product gallery thumbnail' ),
				array( 'value' => '2', 'label' => 'Product with sticky info' ),
				array( 'value' => '3', 'label' => 'Product with sticky info 2' ),
				array( 'value' => '4', 'label' => 'Product with vertical thumbnail' )
			);
        }
        return $this->_options;
    }
}