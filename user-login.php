<?php

// Include the database connection file
include 'db_connection.php';

// Get username and password from the form
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare SQL query to check if the user exists
$stmt = $conn->prepare("SELECT id, first_name, password FROM users WHERE username = ?");
//$conn->prepare(...): Prepares a SQL query to select the id, first_name, and password from the users table where username matches the provided username.
//? is a placeholder for the username parameter.

$stmt->bind_param("s", $username);
$stmt->execute();//Executes the prepared statement.
$result = $stmt->get_result();//Fetches the result of the executed query. $result will contain any matching rows.

// Check if a user with the provided username exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Verify the entered password with the hashed password stored in the database
    if (password_verify($password, $user['password'])) {
        // Successful login
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Login Successful</title>
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
                <h2>Welcome, ' . htmlspecialchars($user['first_name']) . '!</h2>
                <p>You have successfully logged in.</p>
                <a href="user-account.html" class="account-link">Go to Your Account</a>
            </div>
        </body>
        </html>
        ';
    } else {
        // Incorrect password or username
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Login Error</title>
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
                <h2>Login Error</h2>
                <p>Incorrect username or password. Please try again.</p>
                <p><a href="user-login.html">Back to Login</a></p>
            </div>
        </body>
        </html>
        ';
    }
} else {
    // Username does not exist
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Error</title>
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
            <h2>Login Error</h2>
            <p>No account found with this username. Please register first.</p>
            <p><a href="user-register.html">Register Here</a> <p>Or</p> <a href="user-login.html">Retry Login</a>
</p>
        </div>
    </body>
    </html>
    ';
}

$stmt->close();
$conn->close();
?>
