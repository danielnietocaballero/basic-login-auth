<?php

namespace Model;

use Repository\User as UserRepo;

class User
{
	CONST ROLE_ADMIN = 'ADMIN';
	CONST ROLE_PAGE_1 = 'PAGE_1';
	CONST ROLE_PAGE_2 = 'PAGE_2';
	CONST ROLE_PAGE_3 = 'PAGE_3';

	/** @var int */
	protected $id;

	/** @var string $username */
	protected $username;

	/** @var array $roles */
	protected $roles = array();

	/** @var string $password */
	private $password;

	public function __construct($id = null, $username, $password, $roles = array())
	{
		$this->setId($id)
			->setUsername($username)
			->setPassword($password)
			->setRoles($roles);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->username;
	}

	/**
	 * Returns the public information of the User
	 *
	 * @return array
	 */
	public function toArray()
	{
		return array(
			'id' => $this->getId(),
			'username' => $this->getUsername(),
			'roles' => $this->getRolesString(),
		);
	}

	/**
	 * @return int|null Could be null id the Entity is in creating process
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param $id
	 * @return $this
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 * @return $this
	 */
	public function setUsername($username)
	{
		$this->username = $username;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getRoles()
	{
		return $this->roles;
	}

	/**
	 * @return array
	 */
	public function getRolesString()
	{
		return implode('|', $this->getRoles());
	}

	/**
	 * @param array $roles
	 * @return $this
	 */
	public function setRoles(array $roles)
	{
		$this->roles = $roles;
		return $this;
	}

	/**
	 * @return string
	 */
	protected function _getPassword()
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 * @return $this
	 */
	public function setPassword($password)
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * Persist Entity's data in the Repo
	 */
	public function save()
	{
		$repo = new UserRepo();
		$repo->save(
			$this->getId(),
			$this->getUsername(),
			$this->_getPassword(),
			$this->getRolesString()
		);
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return self|bool
	 */
	static public function getByUsernameAndPassword($username, $password)
	{
		$repo = new UserRepo();
		return $repo->getByUsernameAndPassword($username, $password);
	}

	/**
	 * @param string $username
	 * @return self|bool
	 */
	static public function getByUsername($username)
	{
		$repo = new UserRepo();
		return $repo->getByUsername($username);
	}

	/**
	 * @param int $id
	 * @return self|bool
	 */
	static public function getById($id)
	{
		$repo = new UserRepo();
		return $repo->getById($id);
	}
}
