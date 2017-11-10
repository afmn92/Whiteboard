<?php
session_start();
error_reporting(E_ALL);

// Require composer autoloader
require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/utils.php';
require __DIR__ . '/pdo.php';

// Create new Router & Plates instance. No need to reinvent the wheel.
$router = new \Bramus\Router\Router();
$plates = new \League\Plates\Engine('templates');

// *****************************************************************************
// *************** Page routing ************************************************
// *****************************************************************************

// $router->get('/info', function() { phpinfo(); });
$router->get('/', function() { redirect("/register"); });
$router->get('/about', function() use ($plates) 
{
	echo $plates->render('notice', [
		'title' => 'About',
		'text' => 'Shared whiteboard using Php and Javascript.',
	]);
});

// *****************************************************************************

$router->get('/login', function() use ($plates) 
{
	echo $plates->render('login', [
		'title' => 'Login',
	]);
});

$router->post('/login', function() use ($pdo)
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	$board_id = $_POST['board_id'];
	$stmt = $pdo->prepare(
		'SELECT password,type FROM users WHERE username=?;'
	);
	$stmt->execute([$username]);
	$data=$stmt->fetchObject();
	if (password_verify($password, $data->password)) {
		$_SESSION['USERNAME'] = $username;
		$_SESSION['TYPE'] = $data->type;
		redirect("/board/" . $board_id);
	}
	redirect("/login");
});

$router->get('/logout', function() 
{
	if (isset($_SESSION['USERNAME'])) {
		unset($_SESSION['USERNAME']);
		unset($_SESSION['TYPE']);
	}
	redirect("/login");
});

// *****************************************************************************

$router->get('/register', function() use ($plates) 
{
	echo $plates->render('register', [
		'title' => 'Register Account',
		'notice' => 'Please register an account to use this app:',
	]);
});

$router->post('/register', function() use ($pdo, $plates)
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	$stmt = $pdo->prepare(
		'SELECT COUNT(*) FROM users WHERE username=?;'
	);
	$stmt->execute([$username]);
	if ($stmt->fetch(PDO::FETCH_NUM)[0] == 0) {
		$password_hash = password_hash($password, PASSWORD_BCRYPT);
		$stmt = $pdo->prepare(
			'INSERT INTO users (username, password)
			 VALUES(?, ?);'
		);
		$stmt->execute([$username, $password_hash]);
		redirect("/login");
	}
	else {
		echo $plates->render('register', [
			'title' => 'Register account',
			'notice' => 'Username already in use. Please register an new account to use this app:'
		]);
	}
});

// *****************************************************************************

$router->post('/board', function() use ($plates) 
{
	if (!isset($_SESSION['USERNAME'])) redirect("/login");
	$board_id = $_POST['board_id'];
	redirect("/board/$board_id");
});

$router->get('/board(/\w+)?', function($board_id=null) use ($plates) 
{
	if (!isset($_SESSION['USERNAME'])) redirect("/login");
	// User didn't supply a board id. Create a new board for him
	if ($board_id == null) redirect("/new_board");

	// Show the board
	echo $plates->render('board', [
		'title' => 'Whiteboard',
		'username' => $_SESSION['USERNAME']
	]);
});

$router->get('/new_board', function() use ($plates)
{
	if (!isset($_SESSION['USERNAME'])) redirect("/login");
	echo $plates->render('new_board', [
		'title' => 'New Whiteboard'
	]);
});

$router->post('/upload', function() use ($pdo)
{
	if (!isset($_SESSION['USERNAME'])) redirect("/login");

	$uploadfile = basename($_FILES['userfile']['name']);
	$uploadfile = preg_replace("/[^a-zA-Z0-9\.]/", "_", $uploadfile);
	$uploadfile = '/uploads/' . $uploadfile;
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], __DIR__ . $uploadfile)) {
		$board_id = $_POST['board_id'];
		$stmt = $pdo->prepare(
			'INSERT INTO chats (board, data, action, user)
			 VALUES(?, ?, ?, ?);'
		);

		echo $board_id;
		echo $uploadfile;
		echo 'upload';
		echo $_SESSION['USERNAME'];
		$stmt->execute([$board_id, $uploadfile, 'upload', $_SESSION['USERNAME']]);
		redirect("/board/" . $board_id);
	}
});

// *****************************************************************************

$router->get('/admin', function() use ($plates) 
{
	if (!isset($_SESSION['USERNAME'])) redirect("/login");
	if ($_SESSION['TYPE']==='user') redirect("/board");
	echo $plates->render('admin', [
		'title' => 'Administrator',
	]);
});

// *****************************************************************************
// *************** Chat REST API ***********************************************
// *****************************************************************************

// A user is requesting the latest updates from his last known update id
$router->get('/update/(\w+)/(\d+)', 
	function($board_id, $last_known_update_id)
	use ($pdo)
{
	if (!isset($_SESSION['USERNAME'])) redirect("/login");
	$stmt = $pdo->prepare(
		'SELECT * FROM chats WHERE id>? AND board=? ORDER BY id ASC'
	);
	$stmt->execute([$last_known_update_id, $board_id]);
	echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
	// echo json_encode(__htmlspecialchars($stmt->fetchAll(PDO::FETCH_ASSOC)));
	http_response_code(200);
});

// A user is sending an update. It could be simple text, or a draw path
// blind save into sqlite
$router->post('/update', 
	function()
	use ($pdo)
{
	if (!isset($_SESSION['USERNAME'])) redirect("/login");
	$board_id = $_POST['board'];
	$data = $_POST['data'];
	$action = $_POST['action'];
	$user = $_POST['user'];
	$stmt = $pdo->prepare(
		'INSERT INTO chats (board, data, action, user)
		 VALUES(?, ?, ?, ?);'
	);
	$stmt->execute([$board_id, $data, $action, $user]);
	http_response_code(200);
});

// *****************************************************************************

// Run it!
$router->run();
?>
