<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="navigation">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a class="active">Register</a>

    </div>
<?php

if (isset($_POST['submitted'])){
 

  require_once('connectdb.php');
	//checks that data is inputted into the fields to register. Also hashes the password to protect it.
  $username=isset($_POST['username'])?$_POST['username']:false;
  $password=isset($_POST['password'])?password_hash($_POST['password'],PASSWORD_DEFAULT):false;
  $email = isset($_POST['email']) ?$_POST['email'] : false;
  
  if (!($username)){
	echo "Username wrong!";
    exit;
	}
  if (!($password)){
	exit("password wrong!");
	}
  if (!($email)){
	exit("email wrong!");
	}


 try{
	
	//registers the user by inserting the values inputted into the sql table
	$stat=$db->prepare("insert into user values(default,?,?,?)");
	$stat->execute(array($username, $password, $email));
	
  //confirmation message
	$id=$db->lastInsertId();
	echo "Congratulations! You are now registered. Your ID is: $id  ";  	
	
 }
 catch (PDOexception $ex){
	echo "Sorry, a database error occurred! <br>";
	echo "Error details: <em>". $ex->getMessage()."</em>";
 }

 
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration System </title>
</head>
<body>
  <h2>Register</h2>
  <div class="login">
  <form method = "post" action="register.php">
	Username: <input type="text" name="username" /><br><br>
	Password: <input type="password" name="password" /><br><br>
	Email: <input type="email" name="email" /><br><br>

	<input type="submit" value="Register" /> 
	<input type="reset" value="clear"/>
	<input type="hidden" name="submitted" value="true"/>
  </form>  
</div>
  <br><p> Already a user? <a href="login.php">Log in</a>  </p>

</body>
</html>