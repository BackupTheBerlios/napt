<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                                                                 *
 *   Template - PHP-written class to handle a HTML-template (replace-functions)    *
 *   Copyright (C) 2004  Max Loeffler (Heinrich-Boell-Gymnasium Troisdorf)        *
 *                                                                                 *
 *   This program is free software; you can redistribute it and/or modify it       *
 *   under the terms of the GNU General Public License as published by the Free    *
 *   Software Foundation; either version 2 of the License, or (at your option)     *
 *   any later version.                                                            *
 *                                                                                 *
 *   This program is distributed in the hope that it will be useful, but WITHOUT   *
 *   ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or         *
 *   FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for      *
 *   more details.                                                                 *
 *                                                                                 *
 *   You should have received a copy of the GNU General Public License along with  *
 *   this program; if not, write to the Free Software Foundation, Inc., 59 Temple  *
 *   Place, Suite 330, Boston, MA 02111-1307, USA.                                 *
 *                                                                                 *
 *   http://www.gnu.org/copyleft/gpl.html    *    max.loeffler@hbg-troisdorf.de    *
 *                                                                                 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   $revision = '1.0a';                                        // 17th June 2004  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Template {
   var $_template;  //content|filename
   var $_config;    //reg
	/* reg=true => ereg_replace statt str_replace */
   var $_error;
   var $_pre = '<!-- ';  //Kommentar Prefix
   var $_post = ' -->';  //Kommentar Postfix

   function Template( $filename, $error, $f=__FILE__,$l=__LINE__ ) {
      $this->_error = $error;
      $this->_config = array();
      $this->_config['reg'] = false;
      $this->_template = array();
      $this->_template['filename'] = $filename;
      $this->_template['content']  = File::getFile( $filename, &$this->_error );
   }

	// ( $pre . $pattern . $post ) wird ersetzt! also nur das Muster übergeben!
   function replaceString( $pattern, $replacement, $string = false ) {
      $myPattern = $this->_pre.$pattern.$this->_post;
      // Private Variable oder übergebenen String ersetzen?
      if ( !$string ) $tpl = $this->_template['content']; else $tpl = $string;

      // Ersetzung durchführen
      if( $this->_config['reg'] ) {
         $tpl = ereg_replace( $myPattern, $replacement, $tpl );
      } else {
         $tpl = str_replace( $myPattern, $replacement, $tpl );
      }
      
      // Geänderten String zurückgeben oder private Variable ersetzen
      if ( !$string ) {
         $this->_template['content'] = $tpl;
         return false;
      } else {
         return $tpl;
      }
   }

	// <!-- $kategorie.$dataArray_Key -->  mit $dataArray_Wert
   function replaceArray( $dataArray, $kategorie, $string=false, $f=__FILE__,$l=__LINE__ ) {
      // Private Variable oder übergebenen String ersetzen?
      if ( !$string ) $tpl = $this->_template['content']; else $tpl = $string;

      // Ersetzung durchführen
      if( is_array( $dataArray ) ) {
         foreach( $dataArray as $pattern => $replacement ) {
            $tpl = $this->replaceString( $kategorie.'.'.$pattern, $replacement, $tpl );
         }
      } else {
         $this->_error->addError( "Falsches Datenformat ('.$f.' ['.$l.'])" );
         return false;
      }
      
      // Geänderten String zurückgeben oder private Variable ersetzen
      if ( !$string ) {
         $this->_template['content'] = $tpl;
         return true;
      } else {
         return $tpl;
      }
   }
   
	// dataArray[Kategorie][Feld]
   function replaceArray2( $dataArray, $string=false,$f=__FILE__,$l=__LINE__ ) {
      // Private Variable oder übergebenen String ersetzen?
      if ( !$string ) $tpl = $this->_template['content']; else $tpl = $string;

      // Ersetzung durchführen
      if( is_array( $dataArray ) ) {
         foreach( $dataArray as $kategorie => $innerArray ) {
            if ( is_array( $innerArray ) ) {
               foreach( $innerArray as $pattern => $replacement ) {
                  $this->replaceString( $kategorie.'.'.$pattern, $replacement, $tpl );
               };
            } else {
               $this->_error->addError( "Falsches Datenformat (inner Array) ('.$f.' ['.$l.'])" );
               return false;
            }
         }
      } else {
         $this->_error->addError( "Falsches Datenformat (outer Array) ('.$f.' ['.$l.'])" );
         return false;
      }
      
      // Geänderten String zurückgeben oder private Variable ersetzen
      if ( !$string ) {
         $this->_template['content'] = $tpl;
         return true;
      } else {
         return $tpl;
      }
   }
   
   function replaceList( $dataArray,$string=false,$f=__FILE__,$l=__LINE__ ) {
      // Private Variable oder übergebenen String ersetzen?
      if ( !$string ) $tpl = $this->_template['content']; else $tpl = $string;

      // Ersetzung durchführen
      if( is_array( $dataArray ) ) {
         foreach( $dataArray as $kategorie => $innerArray ) {
            $atpl = explode( $this->_pre.$kategorie.'.*'.$this->_post, $tpl );
            if ( count( $atpl ) == 3 ) {
               $ntpl = $atpl[0];
               if ( is_array( $innerArray ) ) {
                  foreach( $innerArray as $oneItem ) {
                     $ntpl .= $this->replaceArray( $oneItem, $kategorie, $atpl[1] );
                  };
               } else {
                  $this->_error->addError( "Falsches Datenformat (inner Array) ('.$f.' ['.$l.'])" );
                  return false;
               }
               $ntpl .= $atpl[2];
               $tpl = $ntpl;
            } else {
               $this->_error->addError( "Falsches Datenformat (Template) ('.$f.' ['.$l.'])" );
               return false;
            }
         }
      } else {
         $this->_error->addError( "Falsches Datenformat (outer Array) ('.$f.' ['.$l.'])" );
         return false;
      }
      
      // Geänderten String zurückgeben oder private Variable ersetzen
      if ( !$string ) {
         $this->_template['content'] = $tpl;
         return true;
      } else {
         return $tpl;
      }
   }
   
   function getContent( ) {
      return $this->_template['content'];
   }

   function cut( $keyword, $string=false ) {
      if ( ! $string ) {
   		$atpl = explode( $this->_pre.$keyword.$this->_post, $this->_template['content'] );
   	} else {
   		$atpl = explode( $this->_pre.$keyword.$this->_post, $string );
   	};
   	$ntpl = '';
   	$i = 0;
   	if ( (count( $atpl ) % 2) == 0 ) {
   	   $this->_error->addError( 'Falsches Templateformat ('.$this->_template['filename'].') [CUT: '.$keyword.']' );
         return false;
      }
   	while ( $i < count( $atpl ) ) {
   	   $ntpl .= $atpl[$i];
   	   $i = $i + 2;
   	}
   	
      if ( ! $string ) {
   		$this->_template['content'] = $ntpl;
   		return true;
   	} else {
   	   return $ntpl;
   	}
   }
}

?>