<?php
session_start();

// Start the session, check if the user is not logged in, redirect to start
if (!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

// Include the connectdb.php to connect to the database
require_once('connectdb.php');

// Function to sanitize input data to stop sql injection
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_POST['submitted'])) {
    // Form submitted, process the form data
    
    // Retrieve and sanitize form data
    $title = sanitize($_POST['title']);
    $start_date = sanitize($_POST['startDate']);
    $end_date = sanitize($_POST['endDate']);
    $phase = sanitize($_POST['phase']);
    $description = sanitize($_POST['description']);
    $user_id = sanitize($_POST['userId']); 

    try {
        // insert the project into the database
        $insert_query = "INSERT INTO projects (title, start_date, end_date, phase, description, uid) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($insert_query);
        $stmt->execute([$title, $start_date, $end_date, $phase, $description, $user_id]);

        // Reloads the user back to the page after adding the project
        header("Location: loggedin.php");
        exit();
    } catch (PDOException $ex) {
        echo "Sorry, a database error occurred! <br>";
        echo "Error details: <em>" . $ex->getMessage() . "</em>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="navigation">
        <a class="active">Logged in </a>
        <a href="logout.php">Back to home</a>
    </div>

    <div class="mainHeader">
        <?php
        $username = $_SESSION['username'];
        echo "<h2> Welcome $username! </h2>";

        try {
            //Shows the projects
            $query = "SELECT * FROM projects";
            $projects_query = $db->prepare($query);
            $projects_query->execute([]);

            echo "<br><h2>Update Project</h2><br>";

            // Display the project list in a table
            if ($projects_query->rowCount() > 0) {
                echo '<table cellspacing="0" cellpadding="5" id="myTable">
                      <tr>
                        <th align="left"><b>Title</b></th>
                        <th align="left"><b>Project ID</b></th>
                        <th align="left"><b>Start Date</b></th>
                        <th align="left"><b>End Date</b></th>
                        <th align="left"><b>Phase</b></th>
                        <th align="left"><b>Description</b></th>
                        <th align="left"><b>User ID</b></th>
                      </tr>';

                // print all the table
                while ($row = $projects_query->fetch()) {
                    echo "<tr>";
                    echo "<td align='left'><a href='project_details.php?pid=" . $row['pid'] . "'>" . $row['title'] . "</a></td>";
                    echo "<td align='left'>" . $row['pid'] . "</td>";
                    echo "<td align='left'>" . $row['start_date'] . "</td>";
                    echo "<td align='left'>" . $row['end_date'] . "</td>";
                    echo "<td align='left'>" . $row['phase'] . "</td>";
                    echo "<td align='left'>" . $row['description'] . "</td>";
                    echo "<td align='left'>" . $row['uid'] . "</td>";
                    echo "</tr>\n";
                }
                echo '</table>';
            } else {
                echo "<p>No projects in the list.</p>"; // No match found
            }
        } catch (PDOException $ex) {
            echo "Sorry, a database error occurred! <br>";
            echo "Error details: <em>" . $ex->getMessage() . "</em>";
        }
        ?>

        <br><h2>Add a project</h2><br>
        <form method="post" action="loggedin.php">
            Title: <input type="text" name="title" required><br>
            Start date: <input type="date" name="startDate" required><br>
            End date: <input type="date" name="endDate" required><br>
            Phase: <select name="phase" required>
                <option value="design">Design</option>
                <option value="development">Development</option>
                <option value="testing">Testing</option>
                <option value="deployment">Deployment</option>
                <option value="complete">Complete</option>
            </select><br>
            Description: <input type="text" name="description" required><br>
            User ID: <input type="number" name="userId" required><br><br>

            <input type="submit" value="Add">
            <input type="reset" value="Clear">
            <input type="hidden" name="submitted" value="true">
        </form>

        <br><p><a href="logout.php">Log Out</a></p>
    </div>
</body>
</html>
