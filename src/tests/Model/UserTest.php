<?php

namespace tests\Model;

use Model\User;
use Repository\User as UserRepo;

class UserTest extends \PHPUnit_Framework_TestCase
{
	/** @var User */
	protected $user;

	/** @var User */
	protected $userDB;

	protected function setUp()
	{
		$this->user = new User('1', 'phpunit', 'phpunit', array(User::ROLE_PAGE_1));

		$repo = new UserRepo();
		$this->userDB = $repo->getById(2);
	}

	public function testToArray()
	{
		$this->assertEquals(
			array(
				'id' => '1',
				'username' => 'phpunit',
				'roles' => User::ROLE_PAGE_1
			),
			$this->user->toArray()
		);
	}

	public function testGetId()
	{
		$this->assertEquals('1', $this->user->getId());
	}

	public function testGetUsername()
	{
		$this->assertEquals('phpunit', $this->user->getUsername());
	}

	public function testGetRoles()
	{
		$this->assertEquals(array(User::ROLE_PAGE_1), $this->user->getRoles());
	}

	public function testGetRolesString()
	{
		$this->assertEquals(User::ROLE_PAGE_1, $this->user->getRolesString());
	}

	public function testSave()
	{
		$repo = new UserRepo();
		$repo->save(null, 'devil', 'devil', array(User::ROLE_ADMIN));

		$devil = $repo->getByUsername('devil');
		$this->assertNotNull($devil);
		$this->assertEquals(User::ROLE_ADMIN, $devil->getRolesString());
	}

	public function testDelete()
	{
		$this->markTestSkipped();
		$repo = new UserRepo();
		$repo->save(null, 'rajoy', 'rajoy', array(User::ROLE_ADMIN));

		$mariano = $repo->getByUsername('rajoy');
		$this->assertNotNull($mariano);

		$repo = new UserRepo();
		$repo->delete($mariano->getId());

		$this->assertNull($repo->getById($mariano->getId()));
	}

	public function testGetByUsernameAndPassword()
	{
		$repo = new UserRepo();
		$user = $repo->getByUsernameAndPassword('god', 'god');

		$this->assertNotNull($user);
		$this->assertEquals(User::ROLE_ADMIN, $user->getRolesString());
	}

	public function testGetByUsername()
	{
		$repo = new UserRepo();
		$user = $repo->getByUsername('god');

		$this->assertNotNull($user);
		$this->assertEquals($user->getUsername(), 'god');
		$this->assertNotEquals($user->getRolesString(), User::ROLE_PAGE_1);
	}

	public function testGetById()
	{
		$repo = new UserRepo();
		$user = $repo->getById(2);

		$this->assertNotNull($user);
		$this->assertEquals($user, $this->userDB);

		$user = $repo->getById(999999999);
		$this->assertNull($user);
	}

}
