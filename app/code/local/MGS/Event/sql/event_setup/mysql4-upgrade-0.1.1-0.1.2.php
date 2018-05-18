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
DROP TABLE IF EXISTS {$this->getTable('mgs_event_store')};
CREATE TABLE {$this->getTable('mgs_event_store')} (
  `event_id` int(11) unsigned NOT NULL,
  `store_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`event_id`,`store_id`),
  CONSTRAINT `FK_mgs_event_store_store` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_mgs_event_store_event` FOREIGN KEY (`event_id`) REFERENCES `{$this->getTable('mgs_event')}` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
$installer->endSetup();
