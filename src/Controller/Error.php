<?php

namespace Controller;
use View\Error as ErrorView;

class Error extends Generic
{
	/** @var string */
	protected $code;
	/** @var string */
	protected $message;

	public function __construct($code, $message)
	{
		parent::__construct();
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
	 *
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
