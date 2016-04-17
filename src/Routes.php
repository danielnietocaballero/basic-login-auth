<?php

require __DIR__ . '/../vendor/autoload.php';

return [

	['GET', '/', ['Controller\User', 'init']],
	['POST', '/login', ['Controller\User', 'login']],
	['GET', '/logout', ['Controller\User', 'logout']],
	['GET', '/page-one', ['Controller\User', 'pageOne']],
	['GET', '/page-two', ['Controller\User', 'pageTwo']],
	['GET', '/page-three', ['Controller\User', 'pageThree']],

	['POST', '/api/user', ['Api\User', 'post']],
	['DELETE', '/api/user/{id:\d+}', ['Api\User', 'delete']],
	['PUT', '/api/user/{id:\d+}', ['Api\User', 'put']],
	['GET', '/api/user/{id:\d+}', ['Api\User', 'get']],
];

