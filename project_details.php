<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="navigation">
        <a class="active">Logged in </a>
        <a href="loggedin.php">Back to admin page</a>
    </div>

<?php

require_once('connectdb.php');

//Keep the data sanatised and stop sql injection
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_GET['pid'])) {
    $project_id = $_GET['pid'];
    
    //get information on project based on the project ID
    $query = "SELECT * FROM projects WHERE pid = :pid";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':pid', $project_id);
    $stmt->execute();
    
    // Check if the project exists
    if ($row = $stmt->fetch()) {
        // Shows all the information about project with inputs to allow it to be edited
        echo "<h1>Edit Project Details</h1>";
        echo "<form action='update_project.php' method='post'>";
        echo "<input type='hidden' name='pid' value='" . $row['pid'] . "'>";
        echo "<p>Title: <input type='text' name='title' value='" . sanitize($row['title']) . "'></p>";
        echo "<p>Start Date: <input type='date' name='start_date' value='" . sanitize($row['start_date']) . "'></p>";
        echo "<p>End Date: <input type='date' name='end_date' value='" . sanitize($row['end_date']) . "'></p>";
        echo "<p>Phase: <select name='phase'>";
        echo "<option value='design' " . ($row['phase'] == 'design' ? 'selected' : '') . ">Design</option>";
        echo "<option value='development' " . ($row['phase'] == 'development' ? 'selected' : '') . ">Development</option>";
        echo "<option value='testing' " . ($row['phase'] == 'testing' ? 'selected' : '') . ">Testing</option>";
        echo "<option value='deployment' " . ($row['phase'] == 'deployment' ? 'selected' : '') . ">Deployment</option>";
        echo "<option value='complete' " . ($row['phase'] == 'complete' ? 'selected' : '') . ">Complete</option>";
        echo "</select></p>";
        echo "<p>Description: <input type='text' name='description' value='" . sanitize($row['description']) ."'></p>";
        
        // Get the usr details associated with the project
        $user_query = "SELECT * FROM user WHERE uid = :uid";
        $user_stmt = $db->prepare($user_query);
        $user_stmt->bindParam(':uid', $row['uid']);
        $user_stmt->execute();
        
        if ($user_row = $user_stmt->fetch()) {
            // Display the users details
            echo "<p>Username: <input type='text' name='username' value='" . sanitize($user_row['username']) . "'></p>";
            echo "<p>Email: <input type='text' name='email' value='" . sanitize($user_row['email']) . "'></p>";
        } else {
            echo "<p>User details not found.</p>";
        }
        
        echo "<a href='index.html'><input type='submit' value='Save Changes'></a>";
        echo "</form>";
    } else {
        echo "<p>Project not found.</p>";
    }
} else {
    echo "<p>Invalid project ID.</p>";
}
?>

    <br><a href="loggedin.php">Go back</a>
</body>
</html>