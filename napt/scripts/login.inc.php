<?php

if( $cfg['napt']['authType'] == 'html' ) {
   $napt->login( $_POST['user'], $_POST['passwd'] );
}

?>