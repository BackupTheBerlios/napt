<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   "Error" is a PHP-written class to provide global error handling               *
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
 *   http://www.gnu.org/copyleft/gpl.html  *  softwarekoch@users.sourceforge.net   *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/* $revision = '0.9'; */

/** Klasse f&uuml;r globale Fehlerbehandlung
 * @author  Thomas Ley <softwarekoch@users.sourceforge.net>
 * @date    24 Jun 2004
 * @version 0.1
 *
 * Die Klasse "Error" stellt eine zentrale Klasse für Fehler bereit. Fehler k&ouml;nnen
 * dann erstellt werden und &uuml;ber verschiedene wege dem Administrator der Website
 * mitgeteilt werden.
 */
class Error {

   var $_config;
   var $_errors;
   var $_warnings;
   var $_notices;


   function Error( $throw=false ) {
      $this->_config          = array();
      $this->_config['throw'] = $throw;
      $this->_errors          = array();
      $this->_warnings        = array();
      $this->_notices         = array();
   }

   
   /** neuen Fehler hinzuf&uuml;gen
    * @param error string Fehlertext
    */
   function addError( $error ) {
      $this->_errors[] = '[error] '.$error;
      if( $this->_config['throw'] ) {
         $this->throw();
      }
   }

   /** neue Warnung hinzuf&uuml;gen
    * @param warning string Fehlertext
    */
   function addWarning( $warning ) {
      $this->_warnings[] = '[warning] '.$warning;
      if( $this->_config['throw'] ) {
         $this->throw();
      }
   }
   
   /** neue Notiz hinzuf&uuml;gen
    * @param notice string Fehlertext
    */
   function addNotice( $notice ) {
      $this->_notices[] = '[notice] '.$notice;
      if( $this->_config['throw'] ) {
         $this->throw();
      }
   }
   
   /** Konfiguration der Klasse &auml;ndern
    * @param var string Wert, der ge&auml:ndert werden soll
    * @param value string Die neue Einstellung
    *
    * Die folgenden Konfigurationswerte werden benutzt: <br />
    * email -> e-Mail Adresse des Administrators. Default ''<br />
    * logfile -> Logdatei f&uuml;r Fehler. Default ''
    */
   function setConfig( $var, $value ) {
      $this->_config[$var] = $value;
   }
   
   function throw() {
      $debug = array_merge( $this->_errors, $this->_warnings, $this->_notices );
      $debug = implode( "\n", $debug );
      if( isset( $this->_config['mail'] ) && !empty( $this->_config['mail'] ) ) {
         mail( $this->_config['mail'], 'NAPT-Error', $debug, '' );         
      }

      if( isset( $this->_config['file'] ) && !empty( $this->_config['file'] ) ) {
         if ( $handle = fopen($filename, "a" ) && ( is_writeable( $this->_config['file'] ) ) ) {
            if ( !fwrite( $handle, $debug ) ) die( 'Datei "'.$this->_config['file'].'" konnte nicht geschrieben werden' );
            fclose( $handle );
         } else {
            die( 'Loggingdatei "'.$this->_config['file'].'" konnte nicht geoeffnet werden' );
         }
      }

      if( isset( $this->_config['debug'] ) && !empty( $this->_config['debug'] ) ) {
         return $debug;
      }
   }
}

?>
