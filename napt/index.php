<?php

ini_set( 'error_reporting', E_ALL );
ini_set( 'register_globals', '0' );

/* Konfiguration von NAPT */
include( 'config/init.inc.php' );


/* Homepage und Modul einbeziehen */
$napt->setGlobal( 'title', 'Keine &Uuml;berschrift definiert' );

if( $napt->module == 'admin' ) {
   include( 'admin/index.inc.php' );
} else if( $napt->module == 'napt' ) {
   if( isset( $napt->action ) && !empty( $napt->action ) ) {
      include( 'scripts/'.$napt->action.'.inc.php' );
   } else if( isset( $napt->view ) && !empty( $napt->view ) ) {
      $napt->setGlobal( 'content', $napt->getTemplate( $napt->view ) );
   } else {
      $napt->setGlobal( 'content', $napt->getTemplate( 'noModuleDefined' ) );
   }
} else if( $napt->isModule( $napt->module ) ) {
   include( 'modules/'.$napt->module.'/index.inc.php' );
} else {
   $napt->setGlobal( 'content', $napt->getTemplate( 'noModuleDefined', 'napt' ) );
}


/* Homepage Rahmen einlesen */
$homepage = File::getFile( 'templates/main.html' );

/* Global-Array auslesen und einsetzen,
 * Napt-Spezifische Ausschnitte wegschneiden
 */
$loc_glob = $napt->getGlobal();
foreach( $loc_glob as $key => $global ) {
   $homepage = ereg_replace( "<!-- global.$key -->", $global, $homepage );
}
$homepageTpl = $napt->getSysTemplate( 'dummy' );
$homepageTpl->_template['content'] = $homepage;
$homepageTpl->_template['filename'] = 'templates/main.html';
if( $napt->isLogged ) {
   $homepageTpl->cut( 'LOGIN' );
} else {
   $homepageTpl->cut( 'LOGOUT' );
}
echo( $homepageTpl->getContent() );


/* Debuginfo */
echo "<pre>";
/* 
$napt->_global['content'] = "";
$napt->_global['menu'] = "";
$napt->_global['menu'] = "";
echo "<pre>napt->_global AS ";print_r( $napt->_global ); echo "</pre>";
echo "<pre>napt->_modules AS ";print_r( $napt->_modules ); echo "</pre>";
*/
echo "<hr />Error::errors  ==&gt;<br />\n";
print_r($error->_errors);
echo "<hr />\$_SESSION  ==&gt;<br />\n";
print_r($_SESSION);
echo "</pre>";

?>
