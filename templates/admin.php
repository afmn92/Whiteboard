<?php 
	$this->layout('template', ['title' => $this->e($title)]);
	require $_SERVER['DOCUMENT_ROOT'] . '/pdo.php';
?>
<script type="text/javascript" src="/js/admin.js"></script>
<table class="pure-table pure-table-bordered">
	<thead>
		<tr><td colspan="4">Users</td></tr>
		<tr><td>ID</td><td>Name</td><td>Admin</td><td>Action</td></tr></thead>
	<tbody>
<?php
	$stmt = $pdo->prepare(
		'SELECT * FROM users;'
	);
	$stmt->execute();
	while($data = $stmt->fetchObject()){
		echo '<tr>';
		echo "<td>$data->id</td>";
		echo "<td>$data->username</td>";
		echo "<td>$data->type</td><td>";
		if(!($data->username==$_SESSION['USERNAME'])) echo "<button type=\"button\" onclick=removeUser($data->id)>Delete</button>";
		if($data->type==='user') echo "<button type=\"button\" onclick=adminUser($data->id)>Make Admin</button>";
		if($data->type==='admin') if(!($data->username==$_SESSION['USERNAME'])) echo "<button type=\"button\" onclick=unadminUser($data->id)>Remove Admin</button>";
		echo '</td></tr>';
	}
?>	
	</tbody>
</table>
<br/>
<table class="pure-table pure-table-bordered">
	<thead>
		<tr><td colspan="2">Whiteboard Rooms</td></tr>
		<tr><td>Name</td><td>Action</td></tr>
	</thead>
<?php
	$stmt = $pdo->prepare(
		'SELECT DISTINCT board FROM chats;'
	);
	$stmt->execute();
	while($data = $stmt->fetchObject()){
		echo '<tr>';
		echo "<td>$data->board</td>";
		echo "<td><button type=\"button\" onclick=removeRoom(\"$data->board\")>Delete Room</button>";
		echo "<button type=\"button\" onclick=gotoRoom(\"$data->board\")>Goto Room</button></td>";
		echo '</tr>';
	}
?>	
</table>