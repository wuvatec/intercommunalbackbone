<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<?php require_once '../../resources/inc/_styles.inc'; ?>
</head>
<body class="">
<div style="font-family: 'Raleway', sans-serif; font-weight: bold; color: #F00; max-width: 300px; margin: 100px auto; text-align: center;">
<h1><span style="font-size: 50px;" class="fa fa-lock"></span></h1>
<?php

	if (isset($_SESSION['flash_message'])) {
		echo $_SESSION['flash_message'];
	}

?>
</div>
</body>
</html>