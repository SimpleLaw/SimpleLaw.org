<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* @version    2.5.1
* @copyright  Copyright (C) 2009 Andrea Parmeggiani. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* @autor      Andrea Parmeggiani http://www.textarea.it
* See COPYRIGHT.php for copyright notices and details.
*
*/

jimport('joomla.plugin.plugin');

class PlgSystemAutologin extends JPlugin
{
  
  protected $_user;
  protected $_passw;

  function __construct(& $subject, $config)
  {
    parent::__construct($subject, $config);
    $this->loadLanguage();
  }

  function onAfterInitialise()
  {
    $this->_user = JRequest::getVar('user', null);
    $this->_passw = JRequest::getVar('passw', null);

    if (!empty($this->_user) && !empty($this->_passw)) {

      if ($this->params->get('authmethod','1')==='0') {
        $result = $this->plainLogin();
      } else {
        $result = $this->encryptLogin();
      }

      // if OK go to redirect page
      if ($this->params->get('urlredirect', null)) {
        if (!JError::isError($result)) {
          $app = JFactory::getApplication();
          $app->redirect($this->params->get('urlredirect', null));
        }
      }
    }
    return true;

  }
  
  /**
   *
   * PLAIN LOGIN
   *
   */
  function plainLogin() {
    // Get the application object.
    $app = JFactory::getApplication();

    // Get the log in credentials.
    $credentials = array();
    $credentials['username'] = $this->_user;
    $credentials['password'] = $this->_passw;
    
    $options = array();
    $result = $app->login($credentials, $options);
    
  }

  /**
   *
   * ENCRYPT LOGIN
   *
   */
  function encryptLogin() {
    // Get the application object.
    $app = JFactory::getApplication();
    
    $db =& JFactory::getDBO();
    $query = 'SELECT `id`, `username`, `password`'
      . ' FROM `#__users`'
      . ' WHERE username=' . $db->Quote( $this->_user )
      . '   AND password=' . $db->Quote( $this->_passw )
      ;
    $db->setQuery( $query );
    $result = $db->loadObject();
    
    if($result) {
      JPluginHelper::importPlugin('user');
      
      $options = array();
      $options['action'] = 'core.login.site';
      
      $response['username'] = $result->username;
      $result = $app->triggerEvent('onUserLogin', array((array)$response, $options));
    }

  }

}
