<?php
include( 'modules/'.$napt->module.'/lib/include.inc.php' );
include( 'modules/'.$napt->module.'/config/init.inc.php' );

$napt->setGlobal( "title", "guestbook" );

if( $napt->isAllowed( $napt->module, $napt->action ) && ($napt->action != "") ) {
   include( 'modules/'.$napt->module.'/scripts/'.$napt->action.'.inc.php' );
} 
if( $napt->isAllowed( $napt->module, $napt->view ) ) {
   include( 'modules/'.$napt->module.'/scripts/'.$napt->view.'.inc.php' );
} else {
   $localTpl = $napt->getErrTemplate( 'notAllowed' );
   $napt->setGlobal( 'content', $localTpl->getContent() );
   $napt->setGlobal( 'title', 'guestbook - Fehler' );
}
?>
