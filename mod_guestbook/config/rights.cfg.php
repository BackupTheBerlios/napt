<?php

$MODULE_NAME = Misc::getParentDir( __FILE__, false );

$rights[$MODULE_NAME] = array();
$rights[$MODULE_NAME]['overview']       = '*';
$rights[$MODULE_NAME]['add']            = '*';
$rights[$MODULE_NAME]['delete']         = 'admin';
$rights[$MODULE_NAME]['clear']          = 'admin';
$rights[$MODULE_NAME]['admin']          = 'admin';

?>
