<?php

$query = array();
$query['delete'] = 'DELETE FROM guestbook_guestbook
                    WHERE id = '.$_GET['item'];
if( $_GET['item'] > 0 ) {  
  $localDb  = $napt->getDatabase( $cfg[$napt->module]['dsn'] );
  $localDb->execute( $query['delete'] );
}

?>
