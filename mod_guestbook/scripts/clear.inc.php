<?php

$query = array();
$query['delete'] = 'TRUNCATE TABLE guestbook_guestbook';
$localDb  = $napt->getDatabase( $cfg[$napt->module]['dsn'] );
$localDb->execute( $query['delete'] );

?>
