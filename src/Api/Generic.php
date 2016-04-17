<?php

namespace Api;


class Generic
{
	public function __construct()
	{
	}

	protected function _processResponse()
	{

	}

	/**
	 * @param $data
	 * @param int $status
	 * @return mixed
	 */
	protected function _response($data, $status = 200)
	{
		header('Content-Type: application/json');
		header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
		return json_encode($data);
	}

	protected function _requestStatus($code)
	{
		$status = array(
			200 => 'OK',
			401 => 'Access not allowed',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			500 => 'Internal Server Error',
		);
		return ($status[$code]) ? $status[$code] : $status[500];
	}
}
