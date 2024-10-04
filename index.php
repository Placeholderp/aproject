<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <!--Nav bar -->
    <div class="navigation">
        <a class="active" href="">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </div>

    <div class="mainHeader">
        <h2>Search Projects</h2>
        <div class="searchbar">
            <form method="post" action="">
                <label for="search_title">Search by Title:</label>
                <input type="text" name="search_title" id="search_title">

                <label for="search_start_date">Search by Start Date:</label>
                <input type="date" name="search_start_date" id="search_start_date">

                <input type="submit" name="search" value="Search">

                <!-- Hidden project ids -->
                <input type="hidden" name="pid" id="pid">
            </form>
        </div>

        <?php
        session_start();
        require_once('connectdb.php');

        // Handles the search form submisions
        if (isset($_POST['search'])) {
            $search_title = '%' . $_POST['search_title'] . '%';
            $search_start_date = $_POST['search_start_date'];

            // Construct the query to search for projects
            $query = "SELECT pid, title, start_date, description FROM projects WHERE title LIKE :search_title";
            
            if (!empty($search_start_date)) {
                $query .= " OR DATE(start_date) = :search_start_date";
            }
            
            $stmt = $db->prepare($query);
            $stmt->bindValue(':search_title', $search_title, PDO::PARAM_STR);
            if (!empty($search_start_date)) {
                $stmt->bindValue(':search_start_date', $search_start_date, PDO::PARAM_STR);
            }
            $stmt->execute();

            // Display thee search results in a table
            if ($stmt->rowCount() > 0) {
                echo '<br>'; 
                echo '<h2>Search Results</h2>';
                echo '<table cellspacing="0" cellpadding="5" id="searchTable">
                      <tr>
                        <th align="left"><b>Project ID</b></th>
                        <th align="left"><b>Title</b></th>
                        <th align="left"><b>Start Date</b></th>
                        <th align="left"><b>Description</b></th>
                      </tr>';

                while ($row = $stmt->fetch()) {
                    echo "<tr>";
                    echo "<td align='left'>" . $row['pid'] . "</td>";
                    echo "<td align='left'><a href='publicproject.php?pid=" . $row['pid'] . "'>" . $row['title'] . "</a></td>";
                    echo "<td align='left'>" . $row['start_date'] . "</td>";
                    echo "<td align='left'>" . $row['description'] . "</td>";
                    echo "</tr>\n";
                }
                echo '</table>';
            } else {
                echo "<p>No projects found matching the search criteria.</p>\n";
            }
        }
        ?>
        <br> 
        <?php
        try {
            // Display all projects
            $query = "SELECT pid, title, start_date, description FROM projects";
            $rows = $db->query($query);

            if ($rows && $rows->rowCount() > 0) {
                echo '<h2>All Projects</h2>';
                echo '<table cellspacing="0" cellpadding="5" id="allProjectsTable">
                      <tr>
                        <th align="left"><b>Project ID</b></th>
                        <th align="left"><b>Title</b></th>
                        <th align="left"><b>Start Date</b></th>
                        <th align="left"><b>Description</b></th>
                      </tr>';

                while ($row = $rows->fetch()) {
                    echo "<tr>";
                    echo "<td align='left'>" . $row['pid'] . "</td>";
                    echo "<td align='left'><a href='publicproject.php?pid=" . $row['pid'] . "'>" . $row['title'] . "</a></td>";
                    echo "<td align='left'>" . $row['start_date'] . "</td>";
                    echo "<td align='left'>" . $row['description'] . "</td>";
                    echo "</tr>\n";
                }
                echo '</table>';
            } else {
                echo "<p>No projects in the list.</p>\n";
            }
        } catch (PDOException $ex) {
            echo "Sorry, a database error occurred! <br>";
            echo "Error details: <em>" . $ex->getMessage() . "</em>";
        }
        ?>
    </div
