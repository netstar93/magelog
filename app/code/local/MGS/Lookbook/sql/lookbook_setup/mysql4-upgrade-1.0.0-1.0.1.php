<?php
$installer = $this;
$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$setup->addAttribute('catalog_product', 'mgs_lookbook', 
	array(
		'type' => 'int',
		'input' => 'select',
		'label' => 'Lookbook',
		'class' => '',
		'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
		'source' => 'lookbook/entity_attribute_backend_lookbook',
		'visible'  => true,
		'required' => false,
		'user_defined' => false,
		'default'  => '',
		'searchable' => false,
		'filterable' => false,
		'comparable' => false,
		'visible_on_front' => false,
		'unique' => false
	)
);

$attributeSetCollection = Mage::getResourceModel('eav/entity_attribute_set_collection')->load();
foreach ($attributeSetCollection as $id => $attributeSet) {
    $setup->addAttributeGroup('catalog_product', $attributeSet->getAttributeSetName(), 'Lookbook', 1000);
	$setup->addAttributeToGroup  ('catalog_product',  $attributeSet->getAttributeSetName(),  'Lookbook',  'mgs_lookbook');
}

$installer->endSetup();