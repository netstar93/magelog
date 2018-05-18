<?php

/* * ****************************************************
 * Package   : Social
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

$installer = $this;

$installer->startSetup();

$setup = Mage::getModel('customer/entity_setup', 'core_setup');
$setup->addAttribute('customer', 'mgs_social_gid', array(
    'type' => 'text',
    'visible' => false,
    'required' => false,
    'user_defined' => true,
));
$setup->addAttribute('customer', 'mgs_social_gtoken', array(
    'type' => 'text',
    'visible' => false,
    'required' => false,
    'user_defined' => true,
));


$installer->endSetup();
