<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="navigation">
        <a href="index.php">Home</a>
        <a class="active">Login</a>
        <a href="register.php">Register</a>

    </div>
<?php

	//if the form has been submitted
	if (isset($_POST['submitted'])){
		if ( !isset($_POST['username'], $_POST['password']) ) {
		// if data isnt received it exits
		 exit('Please fill both the username and password fields!');
	    }
		require_once ("connectdb.php");
		try {
		//Query DB to find the matching username/password
		//stop SQL injection.
			$stat = $db->prepare('SELECT password FROM user WHERE username = ?');
			$stat->execute(array($_POST['username']));
		    
			// fetch the result row and check 
			if ($stat->rowCount()>0){  // matching username
				$row=$stat->fetch();

				if (password_verify($_POST['password'], $row['password'])){ //matching password
					
					//??recording the user session variable and go to loggedin page?? 
				  session_start();
					$_SESSION["username"]=$_POST['username'];
					header("Location:loggedin.php");
					exit();
				
				} else {
				 echo "<p style='color:red'>Error logging in, password does not match </p>";
 			    }
		    } else {
			 //else display an error
			  echo "<p style='color:red'>Error logging in, Username not found </p>";
		    }
		}
		catch(PDOException $ex) {
			echo("Failed to connect to the database.<br>");
			echo($ex->getMessage());
			exit;
		}

  }
?>

<!-- Form to login -->
<div class="login">
<form action="login.php" method="post">

	<label>User Name</label>
	<input type="text" name="username" size="15" maxlength="25" /><br><br>
    <label>Password:</label>
	<input type="password" name="password" size="15" maxlength="25" /><br><br>
	
	<input type="submit" value="Login" />
	<input type="reset" value="clear"/>
    <input type="hidden" name="submitted" value="TRUE" /><br><br>
	<p>
		Not registered yet? <a href="register.php">Register</a><br>
	</p>

</form>
</div>
</body>
</html>
