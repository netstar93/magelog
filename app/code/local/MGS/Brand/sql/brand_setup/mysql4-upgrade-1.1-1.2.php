<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

$installer = $this;
$installer->startSetup();
$installer->run("
    ALTER TABLE {$this->getTable('mgs_brand')} ADD `option_id` int(11) NULL AFTER `id`;
");
$installer->endSetup();
