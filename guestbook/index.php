<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>IAMIT</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js" type="text/javascript"></script> 
</head>
<body>
	<div class="container">
		<div class="form-container">
		<form action="index.php" method="post" id="messageform">
			<div class="row">
				<input placeholder="Name" type="text" name="name">
			</div>
			<div class="row">
				<input placeholder="E-mail" type="text" name="email">
			</div>
			<div class="row">
				<textarea id="textarea" placeholder="Your message" name="message"></textarea>
			</div>
		</form>
		<button id="btn" action="submit" value="submit" form="messageform">Send</button>
	</div>
	<div class="messages">
	
<?php 
$path = "messages";
$reg = "/txt/i";
readFiles();

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {
	if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && preg_match("/[a-zA-Z]/i", $_POST['name'])) {
		$time = date("H.i.s");
		$file = fopen("messages/".$time.".txt", "w");
		$text = $_POST['name'] . "\r\n" . $_POST['email'] . "\r\n" . $_POST['message'];
		$result = file_put_contents("messages/".$time.".txt", $text);
		echo '<script>$(".messages").html("");</script>';
		readFiles();
		header('location:'.$_SERVER['PHP_SELF']);
     	die();
	}
	else {
		$_SESSION['Error'] = '<h3 class="error">Error while adding your message</h3>';
		echo $_SESSION['Error'];
		unset ($_SESSION['Error']);
	}
}

function readFiles() {
	echo '<script>$(".messages").html("");</script>';
	if (!is_dir("messages")) {
		mkdir("messages");
	}
$messages = findMessages($GLOBALS['path'], $GLOBALS['reg']);
if ($messages) {
	for ($i = 0; $i < count($messages); $i++) {
		$message = file("messages/".$messages[$i]);
		echo "<div class='message-row'>";
		for ($j = 0; $j < count($message); $j++) {
			if ($j == 0) {
				echo htmlspecialchars($message[$j]) . "<br/>";
			}
			elseif ($j == 1) {
				echo "<a href='mailto:$message[$j]'>$message[$j]</a><br/>";
			}
			else {
				echo htmlspecialchars($message[$j]);
			}
		}
		echo "<hr/>";
		echo "</div>";
	}
}
}

function findMessages($path, $reg) {
	$files = scandir($path);
	$messages = array();
	foreach ($files as $file) {
		if (preg_match($reg, pathinfo($file, PATHINFO_EXTENSION))){
				array_push($messages, $file);
		}
	}
	return $messages;
}
?>
</div>	
</body>
</html>



