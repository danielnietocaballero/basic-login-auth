<?php

namespace Api\Http;

class Utils
{
	public function __construct()
	{
	}

	/**
	 * Performs API response
	 *
	 * @param array|string $data
	 * @param int $status
	 * @return string
	 */
	public function response($data, $status = 200)
	{
		header('Content-Type: application/json');
		header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
		return json_encode($data);
	}

	/**
	 * From the given HTTP code, returns the related message
	 * @param int $code
	 * @return string
	 */
	protected function _requestStatus($code)
	{
		$status = array(
			200 => 'OK',
			401 => 'Access not allowed',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			500 => 'Internal Server Error',
		);
		return (isset($status[$code])) ? $status[$code] : $status[500];
	}

}
