<?php

// Include the database connection file
include 'db_connection.php';

// Set parameters
//$_POST['field_name'] retrieves data sent via POST request
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); //password_hash(...): Hashes the password for security using the PASSWORD_DEFAULT algorithm (currently, bcrypt). 

// Check if email or username already exists
$check_query = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
//Prepares a SQL query for execution. This query selects all fields from users where email or username matches the data provided in the form.To prevent duplicate accounts.

$check_query->bind_param("ss", $email, $username);// Binds parameters to the prepared query to prevent SQL injection attacks."ss" indicates that both $email and $username are strings
$check_query->execute();//Executes the query
$result = $check_query->get_result();//Fetches the results of the executed query. This is assigned to $result, which will store any records found.

if ($result->num_rows > 0) {//$result->num_rows counts the number of rows in $result. If it’s greater than 0, then there is a matching user in the database.
    // Duplicate found - display error message
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration Error</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f0f0f0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .error-box {
                background-color: #ff4d4d;
                color: white;
                padding: 20px;
                border-radius: 5px;
                text-align: center;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }
            .error-box a {
                color: white;
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="error-box">
            <h2>Registration Error</h2>
            <p>An account with this email or username already exists. Please try a different one.</p>
            <p><a href="user-register.html">Go Back To Registration</a></p>
        </div>
    </body>
    </html>
    ';
} else {
    // Proceed with insertion as no duplicates found
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, password) VALUES (?, ?, ?, ?, ?)");// prepares the SQL query with placeholders (?) for each value
    $stmt->bind_param("sssss", $first_name, $last_name, $username, $email, $password);
    //$stmt->bind_param(...) binds the parameters to the prepared SQL query. The "sssss" argument indicates that all parameters are strings.

    
    if ($stmt->execute()) {//attempts to insert the new user data into the users table. If successful, the code outputs a success message to the user.
        // Successful registration message
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Registration Successful</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f0f0f0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .message-box {
                    background-color: #4CAF50;
                    color: white;
                    padding: 20px;
                    border-radius: 5px;
                    text-align: center;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                }
                .account-link {
                    margin-top: 15px;
                    display: inline-block;
                    padding: 10px 15px;
                    background-color: white;
                    color: #4CAF50;
                    text-decoration: none;
                    border-radius: 5px;
                }
                .account-link:hover {
                    background-color: #ddd;
                }
            </style>
        </head>
        <body>
            <div class="message-box">
                <h2>Registration Successful!</h2>
                <p>Welcome, ' . htmlspecialchars($first_name) . '! You have been registered successfully.</p>
                <a href="user-account.html" class="account-link">Proceed to Your Account</a>
            </div>
        </body>
        </html>
        ';
    } else {
        echo '<p style="color: red;">Error: ' . htmlspecialchars($stmt->error) . '</p>';
    }
    $stmt->close();//Closes the prepared statement, releasing any resources it was using.
}

$check_query->close();//Closes the check_query statement to free up server resources.
$conn->close();// Closes the database connection once all operations are complete. This helps improve server efficiency.
?>
