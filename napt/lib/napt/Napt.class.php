<?php

class Napt {

   var $_cfg;
   var $_modules;
   var $_rights;
   var $_global;
   var $_error;
   /* Seitenaufrufinfo zentralisieren */
   var $module;
   var $action;
   var $view;
   var $isLogged;

   function Napt() {
   
      global $cfg;
      global $rights;
      
      $this->_error = new Error();
      $this->isLogged = false;
      $this->_cfg = $cfg;
      $this->_global = array();
      /* WICHTIG: Damit content zuerst ersetzt wird, und dann 
       * auch der Rest in Inhalt ersetzt wird. Rest sollte immer
       * ersetzt bzw. entfernt werden.
       */
      $this->_global['content'] = "";
      $this->_global['title'] = "";
      $this->_global['menu'] = "";
      $this->_global['user'] = "";
   
      session_start();
      $this->_loadUser();
     
      $this->_loadModules();
      $this->_rights = $rights;

      /* Seiteninfo */
      if( !isset( $_REQUEST['module'] ) ) {
         $this->module = $this->_cfg['napt']['defaultModule']; //Default Module
      } else {
         $this->module = $_REQUEST['module'];
      }
      if( !isset( $_REQUEST['action'] ) ) {
         $this->action = ''; //Default NoAction
      } else {
         $this->action = $_REQUEST['action'];
      }
      if( !isset( $_REQUEST['view'] ) ) {
         $this->view = $this->_cfg['napt']['defaultView']; //Default View
      } else {
         $this->view = $_REQUEST['view'];
      }  
      $this->setGlobal( 'module', $this->module ); 
      $this->setGlobal( 'action', $this->action ); 
      $this->setGlobal( 'view', $this->view ); 
   }
   
   /*
    * Funktionen für Benutzer-Aktivitäten
    */
    
   function _loadUser() {
      if( !isset( $_SESSION['login'] ) ) $_SESSION['login'] = false;
      if( !$_SESSION['login'] ) {
         $_SESSION['login']     = false;
         $_SESSION['user']      = $this->_cfg['napt']['defaultUser'];
         $_SESSION['groups']    = array();
         $_SESSION['groups'][0] = $this->_cfg['napt']['defaultGroup'];
         $_SESSION['name']      = $this->_cfg['napt']['defaultName'];
      }
      /* $_SESSION['login'] wurde oben gesetzt */
      $this->isLogged = $_SESSION['login'];
      $this->setGlobal( 'user', $_SESSION['user'] );
   }

   function login( $user, $passwd, $f=__FILE__,$l=__LINE__ ) {
      if( $this->_cfg['napt']['authData'] == 'db' ) {
         $db = $this->getDatabase( $this->_cfg['napt']['authDataDsn'], $f,$l );
         if( $data = $db->queryRowArray( "SELECT id,user,name,email"
                                        ." FROM ".$this->_cfg['napt']['dbPrefix']."users"
                                        ." WHERE user='".$user."'"
                                        ." AND passwd=md5('".$passwd."')" ) ) {
            $_SESSION['login']  = true;
            $_SESSION['id']     = $data['id'];
            $_SESSION['user']   = $data['user'];
            $_SESSION['name']   = $data['name'];
            $_SESSION['email']  = $data['email'];
            $groups = $db->queryColumn( "SELECT groups.group"
                                       ." FROM groups,group_members"
                                       ." WHERE group_members.user=".$data['id']
                                       ." AND groups.id=group_members.group" );
            $_SESSION['groups'] = array();
            foreach( $groups as $group ) {
               $_SESSION['groups'][] = $group;
            }
            $this->isLogged = true;
         } else {
            $this->isLogged = false;
         }
         $this->setGlobal( 'user', $_SESSION['user'] );
         return $this->isLogged;
      }
   }
   
   function logout( $f=__FILE__,$l=__LINE__ ) {
      $_SESSION['login'] = false;
      $this->_loadUser();
      return true;
   }

   /*
    * Verschiedene Funktionen zu den Modulen
    */
   function _loadModules() {
      global $rights;
      $this->_modules = array();
      $dirs = Misc::getDir( 'modules/', 'd' );
      if( is_array( $dirs ) ) {
         foreach( $dirs as $dir ) {
            $module = substr( $dir, strlen( 'modules/' ) );
            if( $this->isModule( $module ) ) {
               $this->_modules[] = $module;
               // Rechte definiert?
               if( is_file( $dir.'/config/rights.cfg.php' ) ) {
                  include( $dir.'/config/rights.cfg.php' );
                  if( !isset( $rights[$module] ) || !( is_array( $rights[$module] )
                   && count( $rights[$module] ) > 0 ) ) {
                     $rights[$module] = array();
                     $rights[$module]['*']  = '*';
                  }
               } else {   // Alles für alle
                  $rights[$module]['*'] = '*';
               }
            }
         }
      }
   }
   
   function isModule( $dir ) {
      global $cfg;
      if( substr($dir, 1 , 1) == '.' ) return false;
      if( $dir == 'napt' ) return false;
      $return = false;
		if( is_file( 'modules/'.$dir.'/index.inc.php' ) &&
		    is_file( 'modules/'.$dir.'/scripts/'.$cfg['napt']['defaultView'].'.inc.php' ) &&
		    is_file( 'modules/'.$dir.'/scripts/admin.inc.php' )) {
         $return = true;
      }
      return $return;
   }

   function getModules( ) {
      return $this->_modules;
   }


   /*
    * Datenbank-Verbindungen
    */
   
   function getDatabase( $dsn, $f=__FILE__,$l=__LINE__ ) {
      $database = false;
      if( $this->_cfg['napt']['dbType'] == 'mysql' ) {
         $database = new MysqlDb( $dsn, &$this->_error, $f,$l );
      }
      return $database;
   }
   
   /*
    * Setzt die globalen Variablen
    * i.e. pagename, menu, submenu, content
    */
   function setGlobal( $name, $value ) {
   	$this->_global[$name] = $value;
   }
   
   /*
    * Gibt die globalen Variablen zurück
    */
   function getGlobal( ) {
   	return $this->_global;
   }
   
   /*
    * Benutzerberechtigungen überprüfen
    */
   function isAllowed( $module, $execute ) {
      if( $execute == "" ) return false;
      if( isset( $_SESSION['groups']['root'] ) && $_SESSION['groups']['root'] ) {
         $return = true;
      } else {
         $return = false;
         if( isset( $this->_rights[$module][$execute] ) ) {   // Spezielle Rechte definiert!
            if( $this->_checkRights( $this->_rights[$module][$execute], $_SESSION['groups'] )
             || $this->_rights[$module][$execute] == '*' ) {
               $return = true;
            }
         } else if( isset( $this->_rights[$module]['*'] ) ) {   // Keine speziellen Rechte definiert!
            if( $this->_checkRights( $this->_rights[$module]['*'], $_SESSION['groups'] ) ) {
               $return = true;
            }
         }
      }
      return $return;
   }
   
   function _checkRights( $authList, $authGroups ) {
      foreach( $authGroups as $group ) {
         if( ereg( $group, $authList ) ) {
            return true;
         }
      }
      return false;
   }

   /*
    * Liefert ein entsprechendes Template aus "modlules/<Modul>/templates/<TplName>.tpl.html
    */
   function getTemplate( $templateName, $f=__FILE__,$l=__LINE__ ) {
   	return new Template( 'modules/'.$this->module.'/templates/'.$templateName.'.tpl.html', &$this->_error, $f,$l );
   }
   function getSysTemplate( $templateName, $f=__FILE__,$l=__LINE__ ) {
   	return  new Template( 'templates/'.$templateName.'.tpl.html', &$this->_error, $f,$l );
   }
   function getErrTemplate( $templateName, $f=__FILE__,$l=__LINE__ ) {
   	return  new Template( 'templates/errors/'.$templateName.'.tpl.html', &$this->_error, $f,$l );
   }
   
}

?>
