<?php

include( 'modules/'.$napt->module.'/config/init.inc.php' );

$napt->setGlobal( "title", "Heinrich B&ouml;ll Gynmasium" );

if ( $napt->isAllowed( $napt->module, $napt->action ) && ( $napt->action != "" ) ) {
	include( 'scripts/'.$napt->action.'.inc.php' );
} 

if ( $napt->isAllowed( $napt->module, $napt->view ) && ( $napt->view != "" ) ) {
	include( 'scripts/'.$napt->view.'.inc.php' );
} else {
   $localTpl = $napt->getErrTemplate( 'notAllowed' );
   $napt->setGlobal( 'content', $localTpl->getContent() );
   $napt->setGlobal( 'title', 'hbgchat - Fehler' );
};

?>