<?php

namespace Api\Http;

use Api\Utils;
use \Model\User as UserModel;

class Security
{
	/** @var Utils */
	protected $http;

	public function __construct()
	{

	}

	/**
	 * @return Utils
	 */
	public function getHttpUtil()
	{
		if ($this->http === null) {
			$this->http = new Utils();
		}
		return $this->http;
	}

	/**
	 * This method checks if the basic http authentication is done. Also if the
	 * mandatory param ID is given.
	 *
	 * If the user is sending a valid authentication credential, check if is able
	 * to make the action (ADMIN rol)
	 *
	 * @param array $params
	 */
	public function genericSecurityCheck($params)
	{
		if (empty($params['id'])) {
			$this->getHttpUtil()->response(array('ID' => 'not provided'), 401);
		}

		/**
		 * Check authentication
		 */
		if (empty($_SERVER['HTTP_USERNAME'])) {
			$this->getHttpUtil()->response(array('Username' => 'is empty'), 401);
		}

		if (empty($_SERVER['HTTP_PASSWORD'])) {
			$this->getHttpUtil()->response(array('Password' => 'is empty'), 401);
		}

		$username = $_SERVER['HTTP_USERNAME'];
		$password = $_SERVER['HTTP_PASSWORD'];

		$userAdmin = UserModel::getByUsernameAndPassword($username, $password);

		if (null === $userAdmin || !in_array(UserModel::ROLE_ADMIN, $userAdmin->getRoles())) {
			$this->getHttpUtil()->response(array('message' => 'Action not allowed'), 405);
		}
	}
}
