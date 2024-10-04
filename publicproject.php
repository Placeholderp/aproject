<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public project view</title>

    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="navigation">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </div>

    <body>
        <?php
        
        require_once('connectdb.php');

        if (isset($_GET['pid'])) {
            $project_id = $_GET['pid'];

            // Get information about projects from their project id
            $query = "SELECT * FROM projects WHERE pid = :pid";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':pid', $project_id);
            $stmt->execute();

            // Check that the project is there
            if ($row = $stmt->fetch()) {
                // Prints the table
                echo "<h1>" . $row['title'] . "</h1>";
                echo "<p><b>Project ID:</b> " . $row['pid'] . "</p>";
                echo "<p><b>Start Date:</b> " . $row['start_date'] . "</p>";
                echo "<p><b>End Date:</b> " . $row['end_date'] . "</p>";
                echo "<p><b>Phase:</b> " . $row['phase'] . "</p>";
                echo "<p><b>Description:</b> " . $row['description'] . "</p>";

                // Get the user details
                $user_query = "SELECT * FROM user WHERE uid = :uid";
                $user_stmt = $db->prepare($user_query);
                $user_stmt->bindParam(':uid', $row['uid']);
                $user_stmt->execute();

                
                if ($user_row = $user_stmt->fetch()) {
                    echo "<p><b>User ID:</b> " . $row['uid'] . "</p>";
                    echo "<p><b>Username:</b> " . $user_row['username'] . "</p>";
                    echo "<p><b>Email:</b> " . $user_row['email'] . "</p>";
                } else {
                    echo "<p>User details not found.</p>";
                }
            } else {
                echo "<p>Project not found.</p>";
            }
        } else {
            echo "<p>Invalid project ID.</p>";
        }
        ?>

        <a href="index.php">Go back</a>
    </body>
</html>
