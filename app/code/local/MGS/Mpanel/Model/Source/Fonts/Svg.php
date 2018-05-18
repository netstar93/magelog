<?php
class MGS_Mpanel_Model_Source_Fonts_Svg extends Mage_Adminhtml_Model_System_Config_Backend_File{

    protected function _getAllowedExtensions()
    {
        return array('svg');
    }

}