<?php

/* * ****************************************************
 * Package   : Event
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('mgs_event')} ADD COLUMN `url_key` varchar(255) NOT NULL default '' AFTER `title`;

    ");

$installer->endSetup();
