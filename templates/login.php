<?php $this->layout('template', ['title' => $this->e($title)]) ?>
<h2 class="content-subhead">Please login to use this app:</h2>
<form method="post" action="/login" class="pure-form pure-form-aligned">
	<fieldset>
		<div class="pure-control-group">
			<label for="board">Board ID</label>
			<input id="board" type="text" placeholder="Board ID" name="board_id">
			<span class="pure-form-message-inline">This is an optional field.</span>
		</div>
		<div class="pure-control-group">
			<label for="name">Username</label>
			<input id="name" type="text" placeholder="Username" name="username">
		</div>
		<div class="pure-control-group">
			<label for="pass">Password</label>
			<input id="pass" type="password" placeholder="Password" name="password">
		</div>
		<div class="pure-controls">
			<input type="submit" name="submit" value="Submit" class="pure-button pure-button-primary">
		</div>
	<fieldset>
</form>
