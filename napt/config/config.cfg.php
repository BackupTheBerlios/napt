<?php

/* Init */
$cfg = array();
$cfg['napt'] = array();

/* Datenbankeinstellungen */
$cfg['napt']['dbType']   = 'mysql';
$cfg['napt']['dbPrefix'] = '';


/* Logineinstellungen */
$cfg['napt']['authType']    = 'html';
$cfg['napt']['authData']    = 'db';
$cfg['napt']['authDataDsn'] = 'root:password@localhost/napt';

/* Nicht-angemeldete Benutzer sind automatisch ... */
$cfg['napt']['defaultUser']  = 'guest';
$cfg['napt']['defaultGroup'] = 'guest';
$cfg['napt']['defaultName']  = 'Gast';

/* Allgemeine diverse Einstellungen */
$cfg['napt']['defaultModule'] = 'hbg';
$cfg['napt']['defaultView']   = 'overview';

?>
