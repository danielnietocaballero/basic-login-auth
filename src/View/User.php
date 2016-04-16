<?php

namespace View;

class User
{
	public function __construct()
	{
	}

	/**
	 * @param array $params
	 * @param string|null $file
	 */
	public function render($params = array(), $file = 'user/index.php')
	{
		extract($params);
		include __DIR__ . '/../public/html/' . $file;
	}
}
