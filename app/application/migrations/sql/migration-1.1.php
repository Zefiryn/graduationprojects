<?php
$installer = $this->getAdapter();

$installer->query('ALTER TABLE ' . $this->getTable('Application_Model_Stages') . ' ADD  `final` BOOLEAN NOT NULL');