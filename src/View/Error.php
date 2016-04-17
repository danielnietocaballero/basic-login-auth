<?php

namespace View;

class Error extends View
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @inheritdoc
	 */
	public function render($params = array(), $file = 'error/index.php')
	{
		parent::render($params, $file);
	}
}
