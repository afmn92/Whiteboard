<?php $this->layout('template', ['title' => $this->e($title)]) ?>
<h2 class="content-subhead"><?=$this->e($notice)?></h2>
<form method="post" action="/register" class="pure-form pure-form-aligned">
	<fieldset>
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