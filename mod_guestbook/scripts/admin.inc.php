<?php

$localTpl = $napt->getTemplate( 'admin' );
$forwardTpl = $localTpl->getTemplate( 'forward' );
if( isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'add' ) ) Misc::forward( $forwardTpl );
$localDb  = $napt->getDatabase( $cfg[$napt->module]['dsn'] );


$data = array();
$data['user'] = array();
if ( isset( $_POST['name'] ) )     $data['user']['name']     = $_POST['name'];     else $data['user']['name']     = '';
if ( isset( $_POST['email'] ) )    $data['user']['email']    = $_POST['email'];    else $data['user']['email']    = '';
if ( isset( $_POST['webseite'] ) ) $data['user']['webseite'] = $_POST['webseite']; else $data['user']['webseite'] = '';
if ( isset( $_POST['eintrag'] ) )  $data['user']['eintrag']  = $_POST['eintrag'];  else $data['user']['eintrag']  = '';
if( $_SESSION['login'] ) {
   $data['user']['name'] = $_SESSION['name'];
   $data['user']['email']= $_SESSION['email'];
}
		      
$query = array();
$query['gbshow'] = 'SELECT
      guestbook_guestbook.id as id,
      guestbook_guestbook.zeit as zeit,
      guestbook_guestbook.text as text,
      guestbook_guestbook.autor as name,
      guestbook_guestbook.email as email,
      guestbook_guestbook.webseite as webseite
   FROM guestbook_guestbook
   ORDER BY zeit DESC';
		      
$result = array();
$result['gb'] = $localDb->queryAssocs( $query['gbshow'] );

$localTpl->replaceArray2( $data );
$localTpl->setConfig( 'has', true );
$localTpl->replaceList( $result );
$localTpl->setConfig( 'has', false );

$napt->setGlobal( 'content', $localTpl->getContent() );
$napt->setGlobal( 'title', 'G&auml;stebuch - Administration' );

?>
