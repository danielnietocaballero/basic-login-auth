<?php

namespace Repository;

use Model\User as UserModel;

class User extends RepositoryAbstract
{
	protected $tableName = 'Users';

	public function __construct()
	{
		parent::__construct();
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

		return !$result ? null : $this->dataMapper($result);
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

		return !$result ? null : $this->dataMapper($result);
	}

	/**
	 * @param int|string $id
	 * @return UserModel|null
	 */
	public function getById($id)
	{
		$result = $this->select(array(
			'id' => $id
		));

		return !$result ? null : $this->dataMapper($result);
	}

	/**
	 * Performs a save operation in the repository
	 *
	 * @param int|null $id
	 * @param string $userame
	 * @param string $password
	 * @param array $roles
	 * @throws \Exception
	 */
	public function save($id = null, $userame, $password, array $roles)
	{
		if (null === $id) {
			$this->insert(array(
				'username' => $userame,
				'password' => $password,
				'roles' => $roles,
			));
		} else {
			$this->update(
				$id,
				array(
					'username' => $userame,
					'password' => $password,
					'roles' => $roles,
				)
			);
		}
	}

	/**
	 * Performs a delete operation in the repository
	 * 
	 * @param int $id
	 */
	public function delete($id)
	{
		$this->delete($id);
	}

	/**
	 * @param array $data
	 * @return UserModel|null
	 */
	public function dataMapper($data = array())
	{
		if (empty($data)) {
			return null;
		}

		$id = $data['id'];
		$username = $data['username'];
		$password = $data['password'];
		$roles = $data['roles'];
		$user = new UserModel($id, $username, $password, explode('|', $roles));

		return $user;
	}
}
