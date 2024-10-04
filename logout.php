<?php
	
	session_start();
	//destroys the session data so user has to log in to access loggedin.php again
	session_destroy();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link href="style.css" rel="stylesheet">
</head>
<body>
	


 <H2> Logged out now! </H2> 
 <p>Return to home? <a href="index.php">Click here</a>  </p>
 </body>
 </html>