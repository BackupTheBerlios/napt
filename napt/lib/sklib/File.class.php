<?php

class File {
	/* thomas hat anpassungen mit $error gemacht! */
   function getFile( $filename, $error=false ) {
      $return = false;
      if( file_exists( $filename ) ) {
         if( $fp = fopen( $filename, 'r' ) ) {
            if( $content = fread( $fp, filesize( $filename ) ) ) {
               $return = $content;
            }
            fclose( $fp );
      	} else {
	      	if ( $error ) $error->addError( 'Datei "'.$filename.'" kann nicht gr&ouml;ffnet werden' );
         }
      } else {
      	if ( $error ) $error->addError( 'Datei "'.$filename.'" existiert nicht' );
      }
      return $return;
   }

}

?>
