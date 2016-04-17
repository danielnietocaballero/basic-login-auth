<?php

namespace View;

class User extends View
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @inheritdoc
	 */
	public function render($params = array(), $file = 'user/index.php')
	{
		parent::render($params, $file);
	}
}
