<?php

$user = $params['user'];
?>

<?= "Hello {$user->getUsername()}" ?>

<br>
Click <a href="/logout" title="Logout">here</a> to logout
