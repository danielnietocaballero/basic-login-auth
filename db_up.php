<?php

class MyDB extends SQLite3
{
	function __construct()
	{
		$this->open('test.db');
	}
}

$db = new MyDB();
if (!$db) {
	echo $db->lastErrorMsg();
	die;
} else {
	echo "Opened database successfully\n";
}

$sql = <<<EOF
      CREATE TABLE Users
      (id INTEGER PRIMARY KEY AUTOINCREMENT,
      username TEXT NOT NULL,
      password TEXT NOT NULL,
      roles TEXT NOT NULL
      );
EOF;

$ret = $db->exec($sql);
if (!$ret) {
	echo $db->lastErrorMsg();
	die;
} else {
	echo "Table created successfully\n";
}

$sql = <<<EOF
      INSERT INTO USERS (id,username,password,roles)
      VALUES (1, 'userone', 'userone', 'PAGE_1');

      INSERT INTO USERS (id,username,password,roles)
      VALUES (2, 'usertwo', 'usertwo', 'PAGE_2');

      INSERT INTO USERS (id,username,password,roles)
      VALUES (3, 'userthree', 'userthree', 'PAGE_3');

      INSERT INTO USERS (id,username,password,roles)
      VALUES (4, 'god', 'god', 'ADMIN');
      
      INSERT INTO USERS (id,username,password,roles)
      VALUES (5, 'multirol', 'multirol', 'PAGE_1|PAGE_2');
EOF;

$ret = $db->exec($sql);
if (!$ret) {
	echo $db->lastErrorMsg();
	die;
} else {
	echo "Records created successfully\n";
}
$db->close();
