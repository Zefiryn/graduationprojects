<?php
$installer = $this->getAdapter();

$installer->query('ALTER TABLE ' . $this->getTable('Application_Model_Diplomas') . ' ADD  `author_portfolio` VARCHAR(500) CHARACTER SET UTF8');
$installer->query('ALTER TABLE ' . $this->getTable('Application_Model_Diplomas') . ' ADD  `work_site` VARCHAR(500) CHARACTER SET UTF8');
$installer->query('ALTER TABLE ' . $this->getTable('Application_Model_DiplomaFields') . ' CHANGE  `entry`  `entry` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL');
$installer->query('UPDATE ' . $this->getTable('Application_Model_DiplomaFields') . ' SET entry = null where entry =""');