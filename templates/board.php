<?php $this->layout('template', ['title' => $this->e($title)]) ?>
<link href="/css/board.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/canvas.js"></script>
<script type="text/javascript" src="/js/chat.js"></script>
<script type="text/javascript" src="/js/board.js"></script>
<input type="hidden" id='LOGIN_USERNAME' value="<?=$this->e($username)?>" />
<div class="left">
	<div class="wrapper">
		<canvas id="personal_board" width="800" height="600"></canvas>
		<canvas id="display_board"  width="800" height="600"></canvas>
	</div>
</div>
<div class="right">
	<textarea id="chat_data"></textarea><br>
	<input id="chat_input" type="text" name="chat_input" width="100%"><br>
	<button id="load_image">Upload image</button>
	<form id="file_uploader" enctype="multipart/form-data" action="/upload" method="POST">
		<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
		<input id="board_id" type="hidden" name="board_id" value="" />
		<input id="userfile" name="userfile" type="file" style="display: none;" onchange="this.form.submit();" />
	</form>
</div>
