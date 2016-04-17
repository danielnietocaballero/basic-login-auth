<?php

namespace Api;

use Repository\User as UserRepo;

class User extends Generic
{
	/**
	 * GET verb
	 *
	 * @param array $params
	 */
	public function get($params = array())
	{
		if (empty($params['id'])) {
			$this->_response(array('ID' => 'not provided'), 401);
		}

		$repo = new UserRepo();
		if (null === ($user = $repo->getById($params['id']))) {
			$this->_response('User not found', 404);
		}

		$this->_response($user->toArray(), 200);
	}

	/**
	 * DELETE verb
	 *
	 * @param array $params
	 */
	public function delete($params = array())
	{
		$this->_genericSecurityCheck($params);

		$repo = new UserRepo();
		if (null === ($user = $repo->getById($params['id']))) {
			$this->_response('User not found', 404);
		}

		try {
			$user->delete();
		} catch (\Exception $e) {
			$this->_response($e->getMessage(), 500);
		}

		$this->_response('User updated', 200);
	}

	/**
	 * PUT verb
	 *
	 * @param array $params
	 */
	public function put($params = array())
	{
		$this->_genericSecurityCheck($params);

		$repo = new UserRepo();
		if (null === ($user = $repo->getById($params['id']))) {
			$this->_response('User not found', 404);
		}

		parse_str(file_get_contents('php://input'), $params);

		if (isset($params['username'])) {
			$user->setUsername($params['username']);
		}

		if (isset($params['password'])) {
			$user->setPassword($params['password']);
		}

		if (isset($params['roles'])) {
			$user->setRoles(explode('|', $params['roles']));
		}

		try {
			$user->save();
		} catch (\Exception $e) {
			$this->_response($e->getMessage(), 500);
		}

		$this->_response('User updated', 200);
	}

	/**
	 * POST verb
	 */
	public function post()
	{
		foreach ($_POST as $field => $value) {
			if (empty($_POST[$field])) {
				$this->_response(array($field => 'is empty'), 401);
			}
		}

		if (empty($_POST['username'])
			|| empty($_POST['password'])
			|| empty($_POST['roles'])
		) {
			$this->_response($_POST, 401);
		}

		$username = $_POST['username'];
		$password = $_POST['password'];
		$roles = explode('|', $_POST['roles']);

		try {
			$user = new \Model\User($username, $password, $roles);
			$user->save();
		} catch (\Exception $e) {
			$this->_response($e->getMessage(), 500);
		}

		$this->_response('User saved', 200);
	}

	/**
	 * @param array $params
	 */
	protected function _genericSecurityCheck($params)
	{
		if (empty($params['id'])) {
			$this->_response(array('ID' => 'not provided'), 401);
		}

		/**
		 * Check authentication
		 */
		if (empty($_SERVER['HTTP_USERNAME'])) {
			$this->_response(array('Username' => 'is empty'), 401);
		}

		if (empty($_SERVER['HTTP_PASSWORD'])) {
			$this->_response(array('Password' => 'is empty'), 401);
		}

		$username = $_SERVER['HTTP_USERNAME'];
		$password = $_SERVER['HTTP_PASSWORD'];

		$userAdmin = \Model\User::getByUsernameAndPassword($username, $password);

		if (null === $userAdmin || !in_array(\Model\User::ROLE_ADMIN, $userAdmin->getRoles())) {
			$this->_response(array('message' => 'Action not allowed'), 405);
		}
	}
}
