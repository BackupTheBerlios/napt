<?php

class Misc {

   function getDir( $path, $type='a' ) {
      $list = array();
      $dh   = dir( $path );
      while( $entry = $dh->read() ) {
         
         if( $entry != '.' && $entry != '..' ) {
            if( ( $type == 'd' && is_dir( $path.$entry ) )
             || ( $type == 'f' && is_file( $path.$entry ) )
             || ( $type == 'c' ) || ( $type == 'a' ) ) {
               $list[] = $path.$entry;
            }
         } else if( $type == 'a' ) {
            $list[] = $path.$entry;
         }

      }
      $dh->close();
      return $list;
   }
   
   function getParentDir( $location, $absolute=true ) {
      if( is_file( $location ) ) {
         $dir = dirname( $location );
      } else {
         $dir = $location;
      }
      while( substr( $dir, -1 ) == '/' ) {
         $dir = substr( $dir, 0, -1 );
      }
      $parent = strrev( substr( strrev( $dir ), strpos( strrev( $dir ), '/' ) +1 ) );
      if( !$absolute ) {
         $parent = strrev( substr( strrev( $parent ), 0, strpos( strrev( $parent ), '/' ) ) );
      }
      
      return $parent;
   }

}

?>
