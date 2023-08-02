<?php
session_start();
include('connection.php');

if (isset($_POST['submit'])) {
    $user = $_POST['username'];
    $psd = $_POST['password'];

    // Prepare a statement to retrieve the password from the database based on the provided username
    $query = "SELECT PASSWORD FROM INFO WHERE USERNAME=?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind the parameters and execute the statement
        mysqli_stmt_bind_param($stmt, "s", $user);
        mysqli_stmt_execute($stmt);

        // Bind the result to a variable
        mysqli_stmt_bind_result($stmt, $stored_password);

        if (mysqli_stmt_fetch($stmt)) {
            // Compare the input password with the stored password (no hashing here)
            if ($psd === $stored_password) {
                // Password is correct, set up the session and redirect to a logged-in page
                $_SESSION['username'] = $user;
                header("Location: next.php");
                exit();
            } else {
                // Incorrect password, display an error message
                echo "Incorrect password";
            }
        } else {
            // No matching username found in the database, display an error message
            echo "Username not found";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Error in preparing the statement
        echo "An error occurred. Please try again later.";
    }

    // Close the connection
    mysqli_close($conn);
}
?>

<form action="" method="POST">
    username<input type="text" name="username"><br><br>
    password<input type="password" name="password"><br><br>
    <input type="submit" name="submit" value="LOGIN">
</form>
