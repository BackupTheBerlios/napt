<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                                                                 *
 *   mysqldb - PHP-written class to handle a connection to a MySQL-database        *
 *    Copyright (C) 2004  Max Loeffler (Heinrich-Boell-Gymnasium Troisdorf)        *
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
 *   $revision = '1.0b';                                        // 25th June 2004  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


class MysqlDb {

   var $_server;
   var $_db;
   var $_error;


   function MysqlDb( $dsn, $error=false, $f=__FILE__,$l=__LINE__ ) {
      if( $error ) {
         $this->_error = $error;
      } else { 
         $this->_error = new Error( true );
      }

      $parts = @explode( '@', $dsn );
      if( count( $parts ) == 2 ) {
         $dbase = @explode( '/', $parts[1] );
         if( count( $dbase ) == 2 ) {
            $host = $dbase[0];
            $db   = $dbase[1];
         } else {
            $this->_error->addError( 'Falsches DSN-Format ('.$f.' ['.$l.']: '.$dsn.')' );
         }
         $login = @explode( ':', $parts[0] );
         if( count( $login ) == 2 ) {
            $user = $login[0];
            $pass = $login[1];
         } else {
            $this->_error->addError( 'Falsches DSN-Format ('.$f.' ['.$l.']: '.$dsn.')' );
         }
      } else {
         $this->_error->addError( 'Falsches DSN-Format ('.$f.' ['.$l.']: '.$dsn.')' );
      }
    
      $this->_server['host'] = $_SERVER['SERVER_NAME'];
      $this->_db['dsn']      = $dsn;
      $this->_db['host']     = $host;
      $this->_db['user']     = $user;
      $this->_db['pass']     = $pass;
      $this->_db['db']       = $db;
      if( $this->_db['con'] = mysql_pconnect( $this->_db['host'], $this->_db['user'], $this->_db['pass'] ) ) {
         if( !mysql_select_db( $this->_db['db'], $this->_db['con'] ) ) {
            $this->_error->addError( 'Konnte Datenbank nicht oeffnen ('.$f.'['.$l.']: '.$dsn.')' );
         }
      } else {
         $this->_error->addError( 'Server-Verbindung schlug fehl ('.$f.'['.$l.']: '.$dsn.')' );
      }
    
   }


   function queryField( $query, $f=__FILE__,$l=__LINE__ ) {
      if( $result = $this->_getResult( $query, $f,$l ) ) {
         $row = @mysql_fetch_array( $result, MYSQL_NUM );
         return $row[0];
      } else {
         return false;
      }
   }

   function queryColumn( $query, $f=__FILE__,$l=__LINE__ ) {
      if( $result = $this->_getResult( $query, $f,$l ) ) {
         $columns = array( );
         while( $row = @mysql_fetch_array( $result, MYSQL_NUM ) ) {
           $columns[] = $row[0];
         }
         return $columns;
      } else {
         return false;
      }
   }
   
   function queryRowObject( $query, $f=__FILE__,$l=__LINE__ ) {
      if( $result = $this->_getResult( $query, $f,$l ) ) {
         $row = @mysql_fetch_object( $result );
         return $row;
      } else {
         return false;
      }
   }
   
   function queryRowArray( $query, $f=__FILE__,$l=__LINE__ ) {
      if( $result = $this->_getResult( $query, $f,$l ) ) {
         $row = @mysql_fetch_array( $result, MYSQL_BOTH );
         return $row;
      } else {
         return false;
      }
   }   

   function queryNumRow( $query, $f=__FILE__,$l=__LINE__ ) {
      if( $result = $this->_getResult( $query, $f,$l ) ) {
         $row = @mysql_fetch_array( $result, MYSQL_NUM );
         return $row;
      } else {
         return false;
      }
   }
   
   function queryAssocRow( $query, $f=__FILE__,$l=__LINE__ ) {
      if( $result = $this->_getResult( $query, $f,$l ) ) {
         $row = @mysql_fetch_array( $result, MYSQL_ASSOC );
         return $row;
      } else {
         return false;
      }
   }
   
   function queryObjects( $query, $f=__FILE__,$l=__LINE__ ) {
      if( $result = $this->_getResult( $query, $f,$l ) ) {
         $rows = array( );
         while( $row = @mysql_fetch_object( $result ) ) {
           $rows[] = $row;
         }
         return $rows;
      } else {
         return false;
      }
   }
   
   function queryObject( $query, $f=__FILE__,$l=__LINE__ ) {
      if( $result = $this->_getResult( $query, $f,$l ) ) {
         return @mysql_fetch_object( $result );
      } else {
         return false;
      }
   }
   
   function queryArrays( $query, $f=__FILE__,$l=__LINE__ ) {
      if( $result = $this->_getResult( $query, $f,$l ) ) {
         $rows = array( );
         while( $row = @mysql_fetch_array( $result, MYSQL_NUM ) ) {
           $rows[] = $row;
         }
         return $rows;
      } else {
         return false;
      }
   }
   
   function queryAssocs( $query, $f=__FILE__,$l=__LINE__ ) {
      if( $result = $this->_getResult( $query, $f,$l ) ) {
         $rows = array( );
         while( $row = @mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
           $rows[] = $row;
         }
         return $rows;
      } else {
         return false;
      }
   }

   function getLastId( ) {
      return mysql_insert_id( $this->_db['con'] );
   }
   
   function execute( $query, $f=__FILE__,$l=__LINE__ ) {
      $this->_db['query'] = $query;
      if( @mysql_query( $this->_db['query'], $this->_db['con'] ) ) {
         return true;
      } else {
         $this->_error->addError( 'Query schlug fehl ('.$f.' ['.$l.']: '.$query.')' );
         return false;
      }
   }   
   
   
   function _getResult( $query, $f=__FILE__,$l=__LINE__ ) {
      $this->_db['query'] = $query;
      if( $result = @mysql_query( $this->_db['query'], $this->_db['con'] ) ) {
         return $result;
      } else {
         $this->_error->addError( 'Query schlug fehl ('.$f.' ['.$l.']: '.$query.')' );
         return false;
      }
   }

}

?>