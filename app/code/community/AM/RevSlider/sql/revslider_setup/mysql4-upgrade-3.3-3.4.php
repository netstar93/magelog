<?php
/**
 * @category    AM
 * @package     AM_RevSlider
 * @copyright   Copyright (C) 2008-2013 ArexMage.com. All Rights Reserved.
 * @license     GNU General Public License version 2 or later
 * @author      ArexMage.com
 * @email       support@arexmage.com
 */

$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn(
    $installer->getTable('revslider/slider'),
    'styles',
    'TEXT NULL DEFAULT NULL COMMENT "Slider Static Styles"'
);

$installer->endSetup();
