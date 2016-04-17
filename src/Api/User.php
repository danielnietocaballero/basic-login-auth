<?php

namespace Api;

use Api\Http\Security;
use Api\Http\Utils;

class User
{
	/** @var Utils */
	protected $http;

	/** @var Security */
	protected $security;

	/**
	 * GET verb
	 *
	 * Must receive an URL param to identify which resource is accesing to
	 *
	 * @param array $params
	 */
	public function get($params = array())
	{
		if (empty($params['id'])) {
			$this->_getHttpUtil()->response(array('ID' => 'not provided'), 401);
		}

		if (null === ($user = \Model\User::getById($params['id']))) {
			$this->_getHttpUtil()->response('User not found', 404);
		}

		$this->_getHttpUtil()->response($user->toArray(), 200);
	}

	/**
	 * DELETE verb
	 *
	 * @param array $params
	 */
	public function delete($params = array())
	{
		$this->_getSecurityUtil()->genericSecurityCheck($params);

		if (null === ($user = \Model\User::getById($params['id']))) {
			$this->_getHttpUtil()->response('User not found', 404);
		}

		try {
			// NOT IMPLEMENTED
			$user->delete();
		} catch (\Exception $e) {
			$this->_getHttpUtil()->response($e->getMessage(), 500);
		}

		$this->_getHttpUtil()->response('User updated', 200);
	}

	/**
	 * PUT verb
	 *
	 * @param array $params
	 */
	public function put($params = array())
	{
		$this->_getSecurityUtil()->genericSecurityCheck($params);

		if (null === ($user = \Model\User::getById($params['id']))) {
			$this->_getHttpUtil()->response('User not found', 404);
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
			$this->_getHttpUtil()->response($e->getMessage(), 500);
		}

		$this->_getHttpUtil()->response('User updated', 200);
	}

	/**
	 * POST verb
	 */
	public function post()
	{
		foreach ($_POST as $field => $value) {
			if (empty($_POST[$field])) {
				$this->_getHttpUtil()->response(array($field => 'is empty'), 401);
			}
		}

		if (empty($_POST['username'])
			|| empty($_POST['password'])
			|| empty($_POST['roles'])
		) {
			$this->_getHttpUtil()->response($_POST, 401);
		}

		$username = $_POST['username'];
		$password = $_POST['password'];
		$roles = explode('|', $_POST['roles']);

		try {
			$user = new \Model\User($username, $password, $roles);
			$user->save();
		} catch (\Exception $e) {
			$this->_getHttpUtil()->response($e->getMessage(), 500);
		}

		$this->_getHttpUtil()->response('User saved', 200);
	}

	/**
	 * @return Utils
	 */
	protected function _getHttpUtil()
	{
		if ($this->http === null) {
			$this->http = new Utils();
		}
		return $this->http;
	}

	/**
	 * @return Security
	 */
	protected function _getSecurityUtil()
	{
		if ($this->security === null) {
			$this->security = new Security();
		}
		return $this->security;
	}
}
