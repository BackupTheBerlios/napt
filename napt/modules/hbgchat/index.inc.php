<?php
include( 'modules/'.$napt->module.'/lib/include.inc.php' );
include( 'modules/'.$napt->module.'/config/init.inc.php' );

$napt->setGlobal( "title", "L&uuml;ckentext" );

if( $napt->isAllowed( $napt->module, $napt->action ) ) {
   include( 'scripts/'.$napt->action.'.inc.php' );
} 

if( $napt->isAllowed( $napt->module, $napt->view ) ) {
   include( 'scripts/'.$napt->view.'.inc.php' );
} else {
   $localTpl = $napt->getErrTemplate( 'notAllowed' );
   $napt->setGlobal( 'content', $localTpl->getContent() );
   $napt->setGlobal( 'title', 'hbgchat - Fehler' );
}
?>
