<?php

if ( isset( $_GET['doc'] ) ) {
   $localTpl = $napt->getTemplate( $_GET['doc'] );
} else {	
   $localTpl = $napt->getTemplate( 'overview' );
}	

$napt->setGlobal( 'content', $localTpl->getContent() );
$napt->setGlobal( 'title', '' );

?>