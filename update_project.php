<?php

require_once('connectdb.php');

// Will sanitise input and stop injection
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check that form has been sibmitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pid = sanitize($_POST['pid']);
    $title = sanitize($_POST['title']);
    $start_date = sanitize($_POST['start_date']);
    $end_date = sanitize($_POST['end_date']);
    $phase = sanitize($_POST['phase']);
    $description = sanitize($_POST['description']);
    $username = sanitize($_POST['username']); 
    $email = sanitize($_POST['email']); 

    // Updates the changes made to the project
    $update_project_query = "UPDATE projects SET title=:title, start_date=:start_date, end_date=:end_date, phase=:phase, description=:description WHERE pid=:pid";
    $update_project_stmt = $db->prepare($update_project_query);
    $update_project_stmt->bindParam(':pid', $pid);
    $update_project_stmt->bindParam(':title', $title);
    $update_project_stmt->bindParam(':start_date', $start_date);
    $update_project_stmt->bindParam(':end_date', $end_date);
    $update_project_stmt->bindParam(':phase', $phase);
    $update_project_stmt->bindParam(':description', $description);
    $update_project_stmt->execute();

    // Will update user details
    if (!empty($username) && !empty($email)) {
        $update_user_query = "UPDATE user SET username=:username, email=:email WHERE uid=(SELECT uid FROM projects WHERE pid=:pid)";
        $update_user_stmt = $db->prepare($update_user_query);
        $update_user_stmt->bindParam(':pid', $pid);
        $update_user_stmt->bindParam(':username', $username);
        $update_user_stmt->bindParam(':email', $email);
        $update_user_stmt->execute();
    }

    //Takes back to project details page after making change
    header("Location: project_details.php?pid=" . $pid);
    exit();
} else {
    echo "Error: Form data not submitted.";
}
?>
