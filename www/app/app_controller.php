<?php
/*
 * Full Metal Jewelry website
 * Copyright (C) 2010 Stichting Lone Wolves
 * Written by Sander Marechal <s.marechal@jejik.com>
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

/**
 * The global AppController.
 *
 * Takes care of authentication for all controllers
 *
 * The authentication model comes from Studio Canaria:
 * http://www.studiocanaria.com/articles/cakephp_auth_component_users_groups_permissions_revisited
 */
class AppController extends Controller
{
	/**
	 * @var $components array Array of components to load for every controller in the application
	 */
	public $components = array('Auth', 'Session');
	
	/** @var array The helpers that will be available on the view */
	public $helpers = array('Html', 'Form', 'Button', 'Letter', 'Session');

	/**
	 * Application hook which runs prior to each controller action
	 */
	public function beforeFilter()
	{
		// Override default fields used by Auth component
		$this->Auth->fields = array('username' => 'email_address', 'password' => 'password');
		// Set application wide actions which do not require authentication
		$this->Auth->allow(array());
		// Set the path to the login action
		$this->Auth->loginAction = array('admin' => false, 'controller' => 'users', 'action' => 'login');
		// Set the default redirect for users who logout
		$this->Auth->logoutRedirect = '/';
		// Set the default redirect for users who login
		$this->Auth->loginRedirect = '/';
		// The error displayed when a login error occurs
		$this->Auth->loginError = __('Login failed. Wrong e-mail address or password.', true);
		// Extend auth component to include authorisation via isAuthorized action
		$this->Auth->authorize = 'controller';
		// Restrict access to only users with an active account
		$this->Auth->userScope = array('User.active = 1');
		// Pass auth component data over to view files
		$this->set('Auth', $this->Auth->user());
	}

	/**
	 * Build a list of all accessible admin controllers
	 */
	public function beforeRender()
	{
		$adminControllers = array();
		$adminBlacklist = array('Addresses', 'App', 'Pages', 'Permissions'); // Never show these
		if ($this->Auth->user()) {
			$controllers = Configure::listObjects('controller');
			sort($controllers);
			foreach ($controllers as $controller) {
				if (!in_array($controller, $adminBlacklist) && $this->__permitted($controller, 'admin_index')) {
					$adminControllers[] = $controller;
				}
			}
		}

		$this->set(compact('adminControllers'));
	}

	/**
	 * Called by Auth component for establishing whether the current authenticated 
	 * user has authorization to access the current controller:action
	 * 
	 * @return true if authorised/false if not authorized
	 */
	public function isAuthorized()
	{
		return $this->__permitted($this->name, $this->action);
	}

	/**
	 * Helper function returns true if the currently authenticated user has permission 
	 * to access the controller:action specified by $controllerName:$actionName
	 *
	 * @param $controllerName Object
	 * @param $actionName Object
	 * @return 
	 */
	public function __permitted($controllerName, $actionName)
	{
		if (!$user_id = $this->Auth->user('id')) {
			return false;
		}

		// Ensure checks are all made lower case
		$controllerName = low($controllerName);
		$actionName = low($actionName);
		
		// If permissions have not been cached to session...
		if (!$this->Session->check('Permissions')) {
			// ...then build permissions array and cache it
			
			// Set the global permissions for all users that are logged in
			// TODO: Read this from the permission table
			$permissions = array();

			// Import the User Model so we can build up the permission cache
			App::import('Model', 'User');
			$thisUser = new User();

			// Now bring in the current users full record along with groups
			$thisGroups = $thisUser->find(array('User.id' => $user_id));
			$thisGroups = $thisGroups['Group'];
			foreach ($thisGroups as $thisGroup) {
				$thisPermissions = $thisUser->Group->find(array('Group.id' => $thisGroup['id']));
				$thisPermissions = $thisPermissions['Permission'];
				foreach ($thisPermissions as $thisPermission) {
					$permissions[] = $thisPermission['name'];
				}
			}

			// write the permissions array to session
			$this->Session->write('Permissions', $permissions);
		} else {
			// ...they have been cached already, so retrieve them
			$permissions = $this->Session->read('Permissions');
		}

		// Now iterate through permissions for a positive match
		foreach ($permissions as $permission) {
			if ($permission == '*') {
				return true; // Super Admin Bypass Found
			}
			if ($permission == $controllerName.':*') {
				return true; // Controller Wide Bypass Found
			}
			if ($permission == $controllerName.':'.$actionName) {
				return true; // Specific permission found
			}
		}

		return false;
	}
}

?>
