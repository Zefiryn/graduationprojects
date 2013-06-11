<?php

$installer = $this->getAdapter();

$installer->query('ALTER TABLE ' . $this->getTable('Application_Model_Applications') . ' ADD  `work_desc_eng` TEXT(2000) CHARACTER SET UTF8');
$installer->query('ALTER TABLE ' . $this->getTable('Application_Model_Applications') . ' ADD  `model_3d` boolean');
$installer->query('ALTER TABLE ' . $this->getTable('Application_Model_Applications') . ' ADD  `model_scale` VARCHAR(20)');
