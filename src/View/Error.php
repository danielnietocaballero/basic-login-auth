<?php

namespace View;

class Error
{
	public function __construct()
	{
	}

	public function render($params = array())
	{
		//$this->view->params = $params;

		extract($params);

		include __DIR__ . '/../public/html/error/index.php';
	}
}
