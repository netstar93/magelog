<?php
$installer = new Mage_Catalog_Model_Resource_Eav_Mysql4_Setup;

$installer->startSetup();

$attribute  = array(
    'type'          =>  'text',
    'label'         =>  'MGS Megamenu Label',
    'input'         =>  'text',
    'global'        =>  Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'       =>  true,
    'required'      =>  false,
    'user_defined'  =>  true,
    'default'       =>  "",
    'group'         =>  "MGS Megamenu"
);

$installer->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'mgs_megamenu_label', $attribute);

$installer->endSetup();