<?php

$query = array();
$query['add'] = 'INSERT INTO guestbook_guestbook
                   (autor, email, webseite, zeit, text)
                 VALUES
                   ("@AUTOR@", "@EMAIL@", "@WEBSEITE@", now(), "@EINTRAG@")';

$data = array();
$data['user'] = array();
if ( isset( $_POST['name'] ) )     $data['user']['name']     = $_POST['name'];     else $data['user']['name']     = '';
if ( isset( $_POST['email'] ) )    $data['user']['email']    = $_POST['email'];    else $data['user']['email']    = '';
if ( isset( $_POST['webseite'] ) ) $data['user']['webseite'] = $_POST['webseite']; else $data['user']['webseite'] = '';
if ( isset( $_POST['eintrag'] ) )  $data['user']['eintrag']  = $_POST['eintrag'];  else $data['user']['eintrag']  = '';

if( !($data['user']['eintrag'] == '') && !($data['user']['name'] == '') ) {  
   $localDb  = $napt->getDatabase( $cfg[$napt->module]['dsn'] );
   $eintragMod = $data['user']['eintrag'];
   $eintragMod = htmlentities( $eintragMod );
   $eintragMod = nl2br( $eintragMod );
   $data['user'] = Misc::addSlashes( $data['user'] );
   $qry = str_replace( '@AUTOR@',    $data['user']['name'], $query['add'] );
   $qry = str_replace( '@EMAIL@',    $data['user']['email'], $qry );
   $qry = str_replace( '@WEBSEITE@', $data['user']['webseite'], $qry );
   $qry = str_replace( '@EINTRAG@',  $eintragMod, $qry );
   $localDb->execute( $qry );
   $_POST['name']     = '';
   $_POST['email']    = '';
   $_POST['webseite'] = '';
   $_POST['eintrag']  = '';
   $_REQUEST['action']= 'done';
} else {
   $napt->setGlobal( 'hint', $napt->getError( 'notFilled' ) );
   $_POST['name']     = $data['user']['name'];
   $_POST['email']    = $data['user']['email'];
   $_POST['webseite'] = $data['user']['webseite'];
   $_POST['eintrag']  = $data['user']['eintrag'];
}

?>
