<?php

namespace Controller;

use Model\User as UserModel;
use View\User as UserView;

class User extends Generic
{

	public function __construct()
	{
		parent::__construct();
	}

	public function init()
	{
		parent::init();
		$view = new UserView();
		$view->render();
	}

	/**
	 * Performs a user login action
	 */
	public function login()
	{
		if (null === ($user = UserModel::getByUsernameAndPassword($_POST['username'], $_POST['password']))) {
			$error = new Error(404, 'User not found');
			$error->render();
		}

		$this->_startSession($user);
		$this->redirectToView($user);
	}

	/**
	 * Logout action
	 */
	public function logout()
	{
		$this->_killSession();
	}

	/**
	 * View for rol PAGE_1
	 */
	public function pageOne()
	{
		$user = $this->_checkCredentials();
		$this->_loadView($user, UserModel::ROLE_PAGE_1, 'page-one.php');
	}

	/**
	 * View for rol PAGE_2
	 */
	public function pageTwo()
	{
		$user = $this->_checkCredentials();
		$this->_loadView($user, UserModel::ROLE_PAGE_2, 'page-two.php');
	}

	/**
	 * View for rol PAGE_3
	 */
	public function pageThree()
	{
		$user = $this->_checkCredentials();
		$this->_loadView($user, UserModel::ROLE_PAGE_3, 'page-three.php');
	}

	/**
	 * Redirects to a defined action, depending on the user-rol.
	 *
	 * ROLE_ADMIN will be redirected to PAGE_1
	 *
	 * @param UserModel $user
	 */
	public function redirectToView(UserModel $user)
	{
		$roles = $user->getRoles();

		if (isset($_SESSION['url'])) {
			$this->_redirectUrlSession($roles);
		}

		if (in_array(UserModel::ROLE_PAGE_1, $roles)
			|| in_array(UserModel::ROLE_ADMIN, $roles)
		) {
			$this->redirectTo('/page-one');
		}

		if (in_array(UserModel::ROLE_PAGE_2, $roles)) {
			$this->redirectTo('/page-two');
		}

		if (in_array(UserModel::ROLE_PAGE_3, $roles)) {
			$this->redirectTo('/page-three');
		}
	}

	/**
	 * @param UserModel $user
	 * @param string $rol
	 * @param string $template
	 */
	protected function _loadView($user, $rol, $template)
	{
		$roles = $user->getRoles();
		if (in_array(UserModel::ROLE_ADMIN, $roles)
			|| in_array($rol, $roles)
		) {
			$this->_loadViewItems($user, $template);
		} else {
			$error = new Error(405, 'Access not allowed.');
			$error->render();
		}
	}

	/**
	 * @param UserModel $user
	 * @param string $template
	 */
	protected function _loadViewItems(UserModel $user, $template)
	{
		if ($this->_isSessionEmpty()) {
			$this->redirectTo();
		}
		if (!$this->_isSessionAlive()) {
			$error = new Error(401, 'Your session has expired.');
			$error->render();
		}

		$view = new UserView();
		$view->render(array('user' => $user), $template);
	}

	/**
	 * Starts all needed for a session data storage
	 * @param UserModel $user
	 */
	protected function _startSession(UserModel $user)
	{
		$_SESSION['username'] = $user->getUsername();
		$_SESSION['start'] = time(); // Taking now logged in time.
		// Ending a session in 30 minutes from the starting time.
		$_SESSION['expire'] = $_SESSION['start'] + (5 * 60);
	}

	/**
	 * If there is any URL stored in SESSION, redirect to it (if the ROL is the correct)
	 *
	 * @param array $roles
	 */
	protected function _redirectUrlSession(array $roles)
	{
		if (in_array(UserModel::ROLE_ADMIN, $roles)) {
			$this->redirectTo($_SESSION['url']);
		}

		switch ($_SESSION['url']) {
			case '/page-one':
				if (in_array(UserModel::ROLE_PAGE_1, $roles)) {
					$this->redirectTo('/page-one');
				}
				break;
			case '/page-two':
				if (in_array(UserModel::ROLE_PAGE_2, $roles)) {
					$this->redirectTo('/page-two');
				}
				break;
			case '/page-three':
				if (in_array(UserModel::ROLE_PAGE_3, $roles)) {
					$this->redirectTo('/page-three');
				}
				break;
		}
	}
}
