<?php
$installer = $this->getAdapter();

//add version column to settings
$installer->query('ALTER TABLE ' . $this->getTable('Application_Model_Settings') . ' ADD COLUMN version VARCHAR(12)');

//add show email to diploma
$installer->query('ALTER TABLE ' . $this->getTable('Application_Model_Diplomas') . ' ADD COLUMN show_email BOOLEAN NOT NULL DEFAULT 0 AFTER email');

//set diplomas show_emai field
$userTbl = $this->getTable('Application_Model_Users');
$diplomaTbl = $this->getTable('Application_Model_Diplomas');
$sql = 'UPDATE '.$diplomaTbl.' SET show_email = (SELECT show_email FROM '.$userTbl.' WHERE '.$diplomaTbl.'.email='.$userTbl.'.email)';
$installer->query($sql);
