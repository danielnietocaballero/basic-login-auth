<?php

namespace helper;

use Model\User as UserModel;

class Session
{
	public function __construct()
	{
	}

	public function init()
	{
	}

	/**
	 * @param string $uri
	 */
	public function redirectTo($uri = '/')
	{
		if ($uri != '/') {
			unset($_SESSION['url']);
		}
		header('Location: ' . $uri);
		exit();
	}

	/**
	 * Ends user session
	 */
	public function killSession()
	{
		session_destroy();
		header('Location: /');
		exit();
	}

	/**
	 * @return bool
	 */
	public function isSessionEmpty()
	{
		return empty($_SESSION);
	}

	/**
	 * @return bool
	 */
	public function isSessionAlive()
	{
		if (!isset($_SESSION['username'])) {
			return false;
		}
		$now = time();
		if ($now > $_SESSION['expire']) {
			$this->killSession();
			return false;
		}
		$this->_increaseSession();

		return true;
	}

	/**
	 * Expiration date of the session will be increased in 5 more minutes
	 * @param int $duration
	 */
	protected function _increaseSession($duration = 5)
	{
		$_SESSION['expire'] = $_SESSION['expire'] + ($duration * 60);
	}

	/**
	 * @return UserModel
	 */
	protected function _getLoggedUser()
	{
		$user = UserModel::getByUsername($_SESSION['username']);
		return $user;
	}

	/**
	 * If any user is logged, redirect to Login
	 * @return UserModel
	 */
	public function checkCredentials()
	{
		$user = $this->_getLoggedUser();
		if (null === $user) {
			$_SESSION['url'] = $_SERVER['REQUEST_URI'];
			$this->redirectTo();
		}
		return $user;
	}

	/**
	 * Starts all needed for a session data storage
	 * @param UserModel $user
	 */
	public function startSession(UserModel $user)
	{
		$_SESSION['username'] = $user->getUsername();
		$_SESSION['start'] = time(); // Taking now logged in time.
		// Ending a session in 30 minutes from the starting time.
		$_SESSION['expire'] = $_SESSION['start'] + (5 * 60);
	}

}
