<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE {$this->getTable('mgs_theme_home_blocks')} ADD `block_position` INT(11) NULL default '0' AFTER `store_id`;
");

if(version_compare('1.9.0.1', Mage::getVersion()) == -1){
	$setup = new Mage_Catalog_Model_Resource_Eav_Mysql4_Setup;
	
	$attributes = array(
		'thumbnail' => array(
			'type'       => 'varchar',
			'label'      => 'Thumbnail Image',
			'input'      => 'image',
			'backend'    => 'catalog/category_attribute_backend_image',
			'required'   => false,
			'sort_order' => 5,
			'global'     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
			'group'      => 'General Information',
		),
	);

	foreach ($attributes as $code => $data) {
		$setup->addAttribute(Mage_Catalog_Model_Category::ENTITY, $code, $data);
	}
}

$installer->endSetup();