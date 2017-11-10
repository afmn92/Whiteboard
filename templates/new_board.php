<?php $this->layout('template', ['title' => $this->e($title)]) ?>
<h2 class="content-subhead">Please enter a name for your new shared whiteboard:</h2>
<form method="post" action="/board" class="pure-form pure-form-aligned">
	<fieldset>
		<div class="pure-control-group">
			<label for="board">Username</label>
			<input id="board" type="text" placeholder="Board ID" name="board_id">
		</div>
		<div class="pure-controls">
			<input type="submit" name="submit" value="Submit" class="pure-button pure-button-primary">
		</div>
	<fieldset>
</form>
