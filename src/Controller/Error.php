<?php

namespace Controller;

use View\Error as ErrorView;

class Error
{
	/** @var string */
	protected $code;
	/** @var string */
	protected $message;

	public function __construct($code, $message)
	{
		$this->code = $code;
		$this->message = $message;
	}

	/**
	 * @return string
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Render error in the view
	 */
	public function render()
	{
		$view = new ErrorView();
		$view->render(array(
			'code' => $this->getCode(),
			'message' => $this->getMessage(),
		));
		exit();
	}

}
