<?php
require $_SERVER['DOCUMENT_ROOT'] . '/pdo.php';
require $_SERVER['DOCUMENT_ROOT'] . '/utils.php';
if (isset($_GET['board'])){
	$board=$_GET['board'];
	try{
		$stmt = $pdo->prepare(
			'DELETE FROM chats WHERE board=?;'
		);
		$stmt->execute([$board]);
		redirect("/admin");
	}catch(PDOException $e)
	{
		echo "An Error Occured: ". $e->getMessage();
	}
}else{
	redirect("/admin");
}
?>