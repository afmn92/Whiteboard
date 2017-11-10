<html>
	<head>
		<title>FYP Whiteboard - <?=$this->e($title)?></title>
		<script type="text/javascript" src="/js/utils.js"></script>
		<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
	</head>
	<body>
		<div class="pure-menu pure-menu-horizontal pure-menu-scrollable" style='height:3em;'>
			<a class="pure-menu-link pure-menu-heading">Welcome <?php if(isset($_SESSION['USERNAME'])){ echo $_SESSION['USERNAME'];}else{ echo 'Guest';}?></a>
			<ul class="pure-menu-list">
				<li class="pure-menu-item"><a href="/board" class="pure-menu-link">Board</a></li>
				<li class="pure-menu-item"><a href="/register" class="pure-menu-link">Register</a></li>
				<?php if(!isset($_SESSION['USERNAME'])){ ?>
				<li class="pure-menu-item"><a href="/login" class="pure-menu-link">Login</a></li>
				<?php }else{ ?>
				<li class="pure-menu-item"><a href="/logout" class="pure-menu-link">Logout</a></li>
				<?php } ?>
				<li class="pure-menu-item"><a href="/about" class="pure-menu-link">About</a></li>
				<?php if (isset($_SESSION['TYPE'])) if ($_SESSION['TYPE']==='admin'){ ?>
				<li class="pure-menu-item"><a href="/admin" class="pure-menu-link">Admin</a></li>
				<?php } ?>
			</ul>
		</div>		
		<div class="main">
			<?=$this->section('content')?>
		</div>
	</body>
</html>
