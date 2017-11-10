<?php
require $_SERVER['DOCUMENT_ROOT'] . '/pdo.php';
require $_SERVER['DOCUMENT_ROOT'] . '/utils.php';
if (isset($_GET['id'])){
	$id=$_GET['id'];
	try{
		$stmt = $pdo->prepare(
			'DELETE FROM users WHERE id=?;'
		);
		$stmt->execute([$id]);
		redirect("/admin");
	}catch(PDOException $e)
	{
		echo "An Error Occured: ". $e->getMessage();
	}
}else{
	redirect("/admin");
}
?>