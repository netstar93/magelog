<?php

$installer = $this;

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$installer->startSetup();

$setup->addAttribute('catalog_product', 'mgs_detail_template', array(
    'group'         => 'Design',
    'input'         => 'select',
    'type'          => 'text',
    'label'         => 'Template Layout',
    'backend'       => 'eav/entity_attribute_backend_array',
    'visible'       => 1,
    'required'        => 0,
    'user_defined' => 1,
    'searchable' => 0,
    'filterable' => 0,
    'comparable'    => 0,
    'visible_on_front' => 1,
    'visible_in_advanced_search'  => 0,
    'is_html_allowed_on_front' => 0,
	'source' => 'mpanel/entity_attribute_backend_template',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$setup->addAttribute('catalog_product', 'mgs_detail_tab', array(
    'group'         => 'Design',
    'input'         => 'select',
    'type'          => 'text',
    'label'         => 'Detail Tab type and position',
    'backend'       => 'eav/entity_attribute_backend_array',
    'visible'       => 1,
    'required'        => 0,
    'user_defined' => 1,
    'searchable' => 0,
    'filterable' => 0,
    'comparable'    => 0,
    'visible_on_front' => 1,
    'visible_in_advanced_search'  => 0,
    'is_html_allowed_on_front' => 0,
	'source' => 'mpanel/entity_attribute_backend_tab',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$installer->endSetup();
