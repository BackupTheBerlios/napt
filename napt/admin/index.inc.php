<?php

/* Liste der Module erstellen */
$repl = array();
$repl['menu'] = array();
$localMnu = $napt->getSysTemplate( 'admin.mnu' );
$localTpl = $napt->getSysTemplate( 'admin' );

$tmpModules = $napt->getModules();
foreach ( $tmpModules as $tmpModule ) {
   $aMenu = array();
   $aMenu['name'] = $tmpModule;
   $aMenu['title'] = $tmpModule;
   $repl['menu'][] = $aMenu;
}

$localMnu->replaceList( $repl );

$napt->setGlobal( 'menu', $localMnu->getContent() );
$napt->setGlobal( 'title', "Administration" );
$napt->setGlobal( 'content', $localTpl->getContent() );

?>
