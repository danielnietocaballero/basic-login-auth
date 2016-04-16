<?php

namespace Controller;

use Model\User as UserModel;

class Generic
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
	protected function _killSession()
	{
		session_destroy();
		header('Location: /');
		exit();
	}

	/**
	 * @return bool
	 */
	protected function _isSessionEmpty()
	{
		return empty($_SESSION);
	}

	/**
	 * @return bool
	 */
	protected function _isSessionAlive()
	{
		if (!isset($_SESSION['username'])) {
			return false;
		}
		$now = time();
		if ($now > $_SESSION['expire']) {
			$this->_killSession();
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
	protected function _checkCredentials()
	{
		$user = $this->_getLoggedUser();
		if (null === $user) {
			$_SESSION['url'] = $_SERVER['REQUEST_URI'];
			$this->redirectTo();
		}
		return $user;
	}
}
