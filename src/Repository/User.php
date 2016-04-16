<?php

namespace Repository;

use Model\User as UserModel;

class User extends Repository
{
	protected $tableName = 'Users';

	public function __construct()
	{
		parent::__construct();
	}

	public static function getUsers()
	{
		return array();
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return UserModel|null
	 */
	public function getByUsernameAndPassword($username, $password)
	{
		$result = $this->select(array(
			'username' => $username,
			'password' => $password
		));

		return !$result ? null : $this->recreate($result);
	}

	/**
	 * @param string $username
	 * @return UserModel|null
	 */
	public function getByUsername($username)
	{
		$result = $this->select(array(
			'username' => $username
		));

		return !$result ? null : $this->recreate($result);
	}

	/**
	 * @param array $data
	 * @return UserModel|null
	 */
	public function recreate($data = array())
	{
		if (empty($data)) {
			return null;
		}

		$username = $data['username'];
		$password = $data['password'];
		$roles = $data['roles'];
		$user = new UserModel($username, $password, explode('|', $roles));

		return $user;
	}
}
