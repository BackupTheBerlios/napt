<?php

include( 'modules/'.$napt->module.'/config/smile.cfg.php' );

$localTpl     = $napt->getTemplate( 'overview' );
$forwardTpl   = $localTpl->getTemplate( 'forward' );
$smilieImgTpl = $localTpl->getTemplate( 'smilie' );
/* Folgendes Konstrukt verhindert doppeltes Senden */
//if( isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'done' ) ) Misc::forward( $forwardTpl );
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

$smilieData = array();
$smSrc = array_merge( $smilie, $smilieAlias );
foreach( $smSrc as $asmilie ) {
   $smilieData[] = array( $asmilie['name'], str_replace( '@SMILIENAME', $asmilie['name'], str_replace( '@SMILIEIMG@', $asmilie['img'], $smilieImgTpl ) ) );
}

$result = array();
$result['gb']    = $localDb->queryAssocs( $query['gbshow'] );
$localTpl->replaceArray2( $data );
$localTpl->setConfig( 'has', true );
$localTpl->setConfig( 'smilie', true );
$localTpl->setConfig( 'bbcode', true );
$localTpl->setConfig( 'bb_nolink', true );
$localTpl->setConfig( 'smiliedata', $smilieData );
$localTpl->replaceList( $result );
$localTpl->setConfig( 'bb_nolink', false );
$localTpl->setConfig( 'bbcode', false );
$localTpl->setConfig( 'smilie', false );
$localTpl->setConfig( 'has', false );
$result = array();
$result['smilie']= $smilie;
$localTpl->replaceList( $result );

$napt->setGlobal( 'content', $localTpl->getContent() );
$napt->setGlobal( 'title', 'G&auml;stebuch' );

?>
