<?php

$localDb  = $napt->getDatabase( $cfg[$napt->module]['dsn'] );
$localTpl = $napt->getTemplate( 'admin' );

$napt->setGlobal( 'content', $localTpl->getContent() );
$napt->setGlobal( 'title', 'hbgchat Administration' );

?>
