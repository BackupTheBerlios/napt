<?php

$localDb  = $napt->getDatabase( $cfg[$napt->module]['dsn'] );
$localTpl = $napt->getTemplate( 'overview' );

$napt->setGlobal( 'content', $localTpl->getContent() );
$napt->setGlobal( 'title', 'hbgchat &Uuml;bersicht' );

?>
