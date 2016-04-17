<?php

namespace View;

class View
{
	public function __construct()
	{
	}

	/**
	 * @param array $params
	 * @param string|null $file
	 */
	public function render($params = array(), $file)
	{
		extract($params);
		include __DIR__ . '/../public/html/' . $file;
	}
}
