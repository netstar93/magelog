<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('mgs_qa_topics')};
CREATE TABLE {$this->getTable('mgs_qa_topics')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `parent_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `identifier` varchar(255) NOT NULL default '',  
  `show_on_block` smallint(6) NOT NULL default '0',
  `status` smallint(6) NOT NULL default '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS {$this->getTable('mgs_qa_questions')};
CREATE TABLE {$this->getTable('mgs_qa_questions')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `product_id` int(11) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL default '',
  `customer_name` varchar(255) NOT NULL default '',
  `customer_email` varchar(255) NOT NULL default '',
  `content` text NOT NULL default '',
  `topic_id` int(11) unsigned NOT NULL,
  `created_at` datetime NULL,
  `updated_at` datetime NULL,
  `status` smallint(6) NOT NULL default '1',
  `visibility` smallint(6) NOT NULL default '1',
  `score` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS {$this->getTable('mgs_qa_sharing')};
CREATE TABLE {$this->getTable('mgs_qa_sharing')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `question_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS {$this->getTable('mgs_qa_answers')};
CREATE TABLE {$this->getTable('mgs_qa_answers')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `question_id` int(11) unsigned NOT NULL,
  `a_customer_name` varchar(255) NOT NULL default '',
  `a_customer_email` varchar(255) NOT NULL default '',
  `a_content` text NOT NULL default '',
  `is_admin` int(11) unsigned NOT NULL default '0',  
  `a_status` smallint(6) NOT NULL default '1',
  `a_created_at` datetime NULL, 
  `a_updated_at` datetime NULL,
  `a_score` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup();
