
<html>
<head></head>

<body>

<form action="/login" method="POST">
	<p>
		<label for="username">Username</label>
		<input id="username" value="" name="username" type="text" required="required"/><br>
	</p>

	<p>
		<label for="password">Password</label>
		<input id="password" name="password" type="password" required="required"/>
	</p>
	<br/>
	<p>
		<button type="submit" class="" name="submit"><span>Login</span>
		</button>
		<button type="reset" class=""><span>Cancel</span></button>
	</p>

	Click <a href="/logout" title="Logout">here</a> to logout
</form>
</body>
</html>
